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
            indexHandler.NoticeSlider(), indexHandler.Logout(),
            indexHandler.xySlider(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.thumbFunc(),indexHandler.EbetGameUrl(),
            indexHandler.CreateAgAccount(),indexHandler.AgGameUrl(),indexHandler.CreateEbetAccount()
        }
        ,EbetGameUrl: function()
        {
            $('a[data_method="ebetGame"]').on('click', function(){
                $.post("/EbetGameUrl",function(gameurl){
                    layer.msg("注意:Ebet游戏登录用户为ebet_平台用户名;初始密码为a123456!5秒后进入游戏",{time:5000,end:function(){
                            window.open(gameurl)
                        }})
                    },'text')
                })
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
        ,CreateAgAccount: function()
        {
            $('a[data-method=CreateAgAccount]').on('click', function(){
                $.post('/CreateAgAccount',function(info){
                    if(info)
                    {
                        layui.use('layer',function(layer){
                            layer.msg('开通游戏成功',{time:2000,end:function(){
                                window.location.reload();
                            }})
                        })
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,AgGameUrl: function()
        {
            $('a[data_method="agGame"]').on('click', function(){
                var gameType = $(this).attr("data-field")
                var title = $(this).attr("data-title")
                $.post("/AgGameUrl",{"gameType":gameType},function(gameurl){
                        window.open(gameurl)
                    },'text')
                })
        }
        ,CreateEbetAccount: function()
        {
            $('a[data-method=CreateEbetAccount]').on('click', function(){
                $.post('/CreateEbetAccount',function(info){
                    if(info)
                    {
                        layui.use('layer',function(layer){
                            layer.msg('开通游戏成功',{time:2000,end:function(){
                                window.location.reload();
                            }})
                        })
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})