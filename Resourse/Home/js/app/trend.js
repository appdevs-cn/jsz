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
        ,LotteryRecord: function()
        {
            $(".J__lotteryRecord").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".lotteryRecord-list").stop(true).slideToggle(300);
            });
        }
        ,Dropdown: function()
        {
            $(".J__dropdown").hover(function(){
				$(this).addClass("active").siblings().removeClass("on");
				$(this).find(".dropdownMenu2").stop(true).slideDown(200);
			}, function(){
				$(this).removeClass("active");
				$(".J__navMenu .current").addClass("on");
				$(this).find(".dropdownMenu2").stop(true).hide();
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
            indexHandler.NoticeSlider(),indexHandler.lossLine(),indexHandler.LotteryRecord(),indexHandler.RefreshMoney(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu()
        }
        ,RefreshMoney: function()
        {
            // 金额刷新
            $(".getnewmoney").bind("click",function(){
                $.post("/getMoney",function(money){
                    var arr = money.split('-')
                    $(".usermoney").html("可用余额 ￥"+arr[0]);
                    $(".wallet_account").html("分红钱包 ￥"+arr[1]);
                },"text")
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
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})