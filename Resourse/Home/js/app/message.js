!(function(win){
    var indexHandler = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,thumbFunc: function()
        {
            $(".js-goods-pic-thumb").each(function(){
                $(this).jq_simpleStep({selector: "dl", item: "dd", visual: 4});
            });
        }
        ,TrHover: function()
        {
            $("#J__trHover tr").hover(function(){
                $(this).addClass("on");
            }, function(){
                $(this).removeClass("on");
            });
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.JszContent',function(){
                indexHandler.LoadingAfter()
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            //监听是否有新的邮件
            window.socket.on("messagemail", function(content) {
                indexHandler.GetMail()
            })
            indexHandler.NoticeSlider(), indexHandler.Logout(),
            indexHandler.xySlider(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.thumbFunc(),
            indexHandler.GetMail(),indexHandler.SendMail()
        }
        ,Logout: function()
        {
            // 退出系统
            $.dialog.close('*');
            $(document).on('click', 'a[data-method=out]', function(e) {
                $uid = $('input[name=uid]').val()
                layui.use('layer', function(layer){
                    var index = layer.confirm('确定要退出系统吗？', {
                        btn: ['退出','玩一会'] 
                        ,anim:3
                        ,btnAlign: 'c'
                        }, function(){
                            window.location.href = '/logout';
                            $('input[name=uid]').val(" ");
                            $('input[name=path]').val(" ");
                            if ($uid != "")
                                window.socket.emit("logout", $uid);
                            layer.close(index)
                        });
                })
            })
        }
        ,xySlider: function()
        {
            // 大图轮播
            var $slider = $(".js_idxSlider"), $nav = $(".slider-nav i");
            $slider.jq_xySlider({
                effect: "fade",
                autoplay:true,
                delay: 5000,
                onEnd: function(idx){
                    $nav.removeClass("on").eq(idx).addClass("on");
                },
                navigation: $nav
            });
        }
        ,Dropdown: function()
        {
            $(".J__dropdown").hover(function(){
				$(this).toggleClass("active");
				$(this).find(".dropdownMenu").stop(true).slideToggle(300);
			});
        }
        ,NavMenu: function()
        {
            $(".J__navMenu li:not('.u-info')").hover(function(){
				$(this).addClass("on").siblings().removeClass("on");
			}, function(){
				$(this).removeClass("on");
				$(".J__navMenu .current").addClass("on");
			});
        }
        ,GetMail: function()
        {
            $data = $("#messageFrom").serialize();
            $.post("/getOutboxMessage",$data,function(data){
                if(data.length==0)
                {
                    $("#mail").html(tpl("#empty_mail_list"))
                }
                else
                {
                    $("#mail").html(tpl("#mail_list",{rows:[data]}))
                    indexHandler.TrHover()
                    $(".pageCls").on("click",function(){
                        $("input[name=p]").val($(this).attr("p"));
                        indexHandler.GetMail()
                    })
                    $("a[data-method='replaymail']").on("click", function(){
                        var username = $(this).attr("data-username")
                        var uid = $(this).attr("data-uid")
                        $.dialog.util.alert({
                            title: "发送站内信",
                            width: 800,
                            height:500,
                            height: "auto"
                        }).ready(function(t) {
                            t.html(tpl("#replay_mail",{rows:{"username":username,"uid":uid}}))
                            $('button[data-method="close"]').on("click", function(){
                                $.dialog.close("*")
                            })
                            $('button[data-method="send"]').on("click",function(){
                                var tid = $("select[name=tid]").val()
                                var title = $("input[name='title']").val()
                                var content = $("#content").val()
                                $.post("/sendMail",{"tid":tid,"title":title,"content":content},function(info){
                                    alertify.success("<div class='text'><i class='ico-success'></i>"+info+"<i class='suc-close'></i></div>")
                                },'text')
                                $.dialog.close("*")
                            })
                        })
                    })
                }
            },'json')
        }
        ,SendMail: function()
        {
            $("a[data-method='sendmail']").on("click", function(){
                $.post("/selectSendmailUser", function(data){
                    $.dialog.util.alert({
                        title: "发送站内信",
                        width: 800,
                        height:500,
                        height: "auto"
                    }).ready(function(t) {
                        t.html(tpl("#send_mail",{rows:[data]}))
                        $('button[data-method="close"]').on("click", function(){
                            $.dialog.close("*")
                        })
                        $('button[data-method="send"]').on("click",function(){
                            var tid = $("select[name=tid]").val()
                            var title = $("input[name='title']").val()
                            var content = $("#content").val()
                            $.post("/sendMail",{"tid":tid,"title":title,"content":content},function(info){
                                alertify.success("<div class='text'><i class='ico-success'></i>"+info+"<i class='suc-close'></i></div>")
                            },'text')
                            $.dialog.close("*")
                        })
                    })
                })
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})