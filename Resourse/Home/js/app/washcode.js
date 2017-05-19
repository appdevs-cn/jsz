var $doc = $(document)
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
            $doc.ajaxStart(function(){
                $("#list").html(tpl('#loading_list'))
                return false;
            });
            indexHandler.NoticeSlider(), indexHandler.Logout(),indexHandler.GetWahsCode(),
            indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.thumbFunc(),indexHandler.WashCode()
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
                        ,area:['300px','180px']
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
        ,GetWahsCode: function()
        {
            $.post('/GetWahsCode',function(data){
                $("#list").html(tpl("#wash_info",{rows:[data]}))
                $("#totalWash").html(data[5]['totalBonus'])
                indexHandler.TrHover()
            },'json')
        }
        ,WashCode: function()
        {
            layui.use('layer', function(layer){
                $('a[data-method="washcode_sub"]').on("click", function(){
                    var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                    $.post("/WashCodeHandler",function(info){
                        if(info.indexOf("以上才能进行")!==false)
                            alertify.error("<div class='text'><i class='ico-error'></i>"+info+"<i class='err-close'></i></div>")
                        else
                            alertify.success("<div class='text'><i class='ico-success'></i>"+info+"<i class='suc-close'></i></div>")
                        layer.close(index)
                        indexHandler.GetWahsCode()
                    },"text")
                })
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})