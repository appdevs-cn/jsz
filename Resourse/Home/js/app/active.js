!(function(win){
    var indexHandler = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
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
            indexHandler.xySlider(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.Chuangguan()
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
        ,Chuangguan: function()
        {
            $(".chuangguan").on("click",function () {
                var type = $(this).attr("type");
                $(".chuangguan" + type).removeClass('chuangguan');
                $(".chuangguan" + type).attr('disabled','disabled')
                $.post("/Coupon/getXiaofeiHuodong", {"type": type}, function (result) {
                    $(".chuangguan" + type).addClass('chuangguan')
                    $(".chuangguan" + type).removeAttr("disabled")
                    layui.use('layer', function(layer){
                        layer.msg(result,{time:3000,end:function(){
                            $.post("/Coupon/getXiaofeiHuodongStatus", function (type) {
                                if(type!="error") {
                                    var typeArray = type.split("-");
                                    for (var i = 0; i < typeArray.length; i++) {
                                        $(".chuangguan" + typeArray[i]).removeClass("layui-btn-danger").addClass("layui-btn-normal")
                                        $(".chuangguan" + typeArray[i]).removeClass("chuangguan")
                                        $(".chuangguan" + typeArray[i]).text("已闯关");
                                    }
                                }
                            }, 'text');
                        }})
                    })
                }, 'text');
            });
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()

    $.post("/Coupon/getXiaofeiHuodongStatus", function (type) {
        if(type!="error") {
            var typeArray = type.split("-");
            for (var i = 0; i < typeArray.length; i++) {
                $(".chuangguan" + typeArray[i]).removeClass("layui-btn-danger").addClass("layui-btn-normal")
                $(".chuangguan" + typeArray[i]).removeClass("chuangguan")
                $(".chuangguan" + typeArray[i]).text("已闯关");
            }
        }
    }, 'text');
})