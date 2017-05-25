!(function(win){
    var indexHandler = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,lossLine: function()
        {
            $(".J__filter-lossLine").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".selectLists").stop(true).slideToggle(300);
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
            indexHandler.xySlider(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.lossLine()
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
        ,SearchPromotion: function()
        {
            $("#SearchContent").html(tpl("#promotion_search"))
        }
        ,PromotionList: function()
        {
            data = $("#PromotionSearchForm").serialize()
            $.post('/getPromotionList',data,function(data){
                if(data.length==0)
                {	return;
                    alertify.error("<div class='text'><i class='ico-error'></i>抱歉，暂无符合筛选条件的游戏::>_<::<i class='err-close'></i></div>")
                    indexHandler.PromotionList()
                }
                $('#PromotionList').html(tpl("#promotion_list",{rows:[data.list], page:[data.page]}))
                $(".pageCls").on("click",function(){
                    $('input[name=p]').val($(this).attr("p"))
                    indexHandler.PromotionList()
                })
            },'json')
        }
        ,Search: function()
        {
            $('a[data-method="SearchPromotion"]').on('click', function(){
                indexHandler.PromotionList()
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading(),index.SearchPromotion(),index.PromotionList(),index.Search()
})