// 用户登录操作
window.isCheck=""
!(function(win){
    var indexHander = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.JszContent',function(){
                indexHander.LoadingAfter()
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            indexHander.RefreshMoney(),indexHander.Logout(),indexHander.Dropdown(),indexHander.NavMenu(),indexHander.Sidebar(),
            indexHander.NoticeSlider()
        }
        ,Sidebar: function()
        {
            $("#J__sidebar a").hover(function(){
                $(this).addClass("on").siblings().removeClass("on");
            }, function(){
                $(this).removeClass("on");
                $("#J__sidebar .current").addClass("on");
            });
        }
        ,RefreshMoney: function()
        {
            // 金额刷新
            $(".getnewmoney").bind("click",function(){
                $.post("/getMoney",function(money){
                    var arr = money.split('-')
                    $(".usermoney").text(arr[0]);
                    $(".wallet_account").text(arr[1]);
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
    };
    win.index = indexHander;
})(window);

$(function(){
    index.Loading()
})