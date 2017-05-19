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
            indexHandler.xySlider(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.thumbFunc(),
            indexHandler.WithDrawChange(),indexHandler.Updateloginpwdnotice(),indexHandler.Updatefundpwdnotice(),
            indexHandler.Updaterealnamenotice(),indexHandler.Bindbanknotice(),indexHandler.Updatetelnotice(),
            indexHandler.Loginnotice()
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
        ,WithDrawChange: function()
        {
            $('input[name="withdrawnotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"withdrawnotice":1})
                }
                else
                {
                    $.post("/SetSms",{"withdrawnotice":0})
                }
            })
        }
        ,Updateloginpwdnotice: function()
        {
            $('input[name="updateloginpwdnotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"updateloginpwdnotice":1})
                }
                else
                {
                    $.post("/SetSms",{"updateloginpwdnotice":0})
                }
            })
        }
        ,Updatefundpwdnotice: function()
        {
            $('input[name="updatefundpwdnotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"updatefundpwdnotice":1})
                }
                else
                {
                    $.post("/SetSms",{"updatefundpwdnotice":0})
                }
            })
        }
        ,Updaterealnamenotice: function()
        {
            $('input[name="updaterealnamenotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"updaterealnamenotice":1})
                }
                else
                {
                    $.post("/SetSms",{"updaterealnamenotice":0})
                }
            })
        }
        ,Bindbanknotice: function()
        {
            $('input[name="bindbanknotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"bindbanknotice":1})
                }
                else
                {
                    $.post("/SetSms",{"bindbanknotice":0})
                }
            })
        }
        ,Updatetelnotice: function()
        {
            $('input[name="updatetelnotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"updatetelnotice":1})
                }
                else
                {
                    $.post("/SetSms",{"updatetelnotice":0})
                }
            })
        }
        ,Loginnotice: function()
        {
            $('input[name="loginnotice"]').on('change',function(){
                if($(this).is(":checked"))
                {
                    $.post("/SetSms",{"loginnotice":1})
                }
                else
                {
                    $.post("/SetSms",{"loginnotice":0})
                }
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})