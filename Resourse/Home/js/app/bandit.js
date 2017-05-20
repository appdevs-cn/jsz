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
        ,MgSearchContent: function()
        {
            $("#searchContent").html(tpl("#mggame_search"))
        }
        ,AgSearchContent: function()
        {
            $("#searchContent").html(tpl("#aggame_search"))
        }
        ,MgGameList: function()
        {
            data = $("#MgGameForm").serialize()
            $.post('/SearchMgGame',data,function(data){
                if(data.length==0)
                {	return;
                    alertify.error("<div class='text'><i class='ico-error'></i>抱歉，暂无符合筛选条件的游戏::>_<::<i class='err-close'></i></div>")
                    $("input[name=ChineseGameName]").val("")
                    indexHandler.MgGameList()
                }
                $('#ComputerGame').html(tpl("#mggame_list",{rows:[data]}))
                indexHandler.CreateMgAccount()
                indexHandler.MgGameUrl()
                $(".pageCls").on("click",function(){
                    $('input[name=p]').val($(this).attr("p"))
                    indexHandler.MgGameList()
                })
            },'json')
        }
        ,MgGameButton: function()
        {
            $('a[data-method="SearchMgGame"]').on('click', function(){
                $('.menuList').find('a').removeClass('selected')
                $(this).addClass('selected')
                $('input[name=ChineseGameName]').val("")
                indexHandler.MgSearchContent()
                indexHandler.MgGameList()
            })
        }
        ,Search: function()
        {
            $('a[data-method="SearchMg"]').on('click', function(){
                indexHandler.MgGameList()
            })
        }
        ,PtSearchContent: function()
        {
            $("#searchContent").html(tpl("#ptgame_search"))
        }
        ,PtGameList: function()
        {
            data = $("#PtGameForm").serialize()
            $.post('/SearchPtGame',data,function(data){
                if(data.length==0)
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>抱歉，暂无符合筛选条件的游戏::>_<::<i class='err-close'></i></div>")
                    $("input[name=ChineseGameName]").val("")
                    indexHandler.PtGameList()
                }
                $('#ComputerGame').html(tpl("#ptgame_list",{rows:[data]}))
                indexHandler.PtGameUrl()
                indexHandler.CreatePtAccount()
                indexHandler.SearchPt()
                $(".pageCls").on("click",function(){
                    $('input[name=p]').val($(this).attr("p"))
                    indexHandler.PtGameList()
                })
            },'json')
        }
        ,PtGameButton: function()
        {
            $('a[data-method="SearchPtGame"]').on('click', function(){
                $('.menuList').find('a').removeClass('selected')
                $(this).addClass('selected')
                $('input[name=ChineseGameName]').val("")
                indexHandler.PtGameList()
                indexHandler.PtSearchContent()
            })
        }
        ,SearchPt: function()
        {
            $('a[data-method="SearchPt"]').on('click', function(){
                indexHandler.PtGameList()
            })
        }

        ,AgGameList: function()
        {
            data = $("#AgGameForm").serialize()
            $.post('/SearchAgGame',data,function(data){
                if(data.length==0)
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>抱歉，暂无符合筛选条件的游戏::>_<::<i class='err-close'></i></div>")
                    $("input[name=ChineseGameName]").val("")
                    indexHandler.AgGameList()
                }
                $('#ComputerGame').html(tpl("#aggame_list",{rows:[data]}))
                indexHandler.AgGameUrl()
                indexHandler.CreateAgAccount()
                indexHandler.SearchAg()
                $(".pageCls").on("click",function(){
                    $('input[name=p]').val($(this).attr("p"))
                    indexHandler.AgGameList()
                })
            },'json')
        }
        ,AgGameButton: function()
        {
            $('a[data-method="SearchAgGame"]').on('click', function(){
                $('.menuList').find('a').removeClass('selected')
                $(this).addClass('selected')
                $('input[name=ChineseGameName]').val("")
                indexHandler.AgGameList()
                indexHandler.AgSearchContent()
            })
        }
        ,SearchAg: function()
        {
            $('a[data-method="SearchAg"]').on('click', function(){
                indexHandler.AgGameList()
            })
        }

        ,CreateMgAccount: function()
        {
            $('a[data-method=CreateMgAccount]').on('click', function(){
                $.post('/CreateMgAccount',function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>开通游戏成功<i class='suc-close'></i></div>")
                        indexHandler.MgGameList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,MgGameUrl: function()
        {
            $('a[data-method="gmGame"]').on('click', function(){
                var til = $(this).attr('data-title')
                $.post('/GMGame',{"gameid":$(this).attr("data-field")},function(gameUrl){
                    if(gameUrl=="")
                    {
                        return false;
                    }
                    window.open(gameUrl)
                },'text')
            })
        }
        ,CreatePtAccount: function()
        {
            $('a[data-method=CreatePtAccount]').on('click', function(){
                $.post('/CreatePtAccount',function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>开通游戏成功<i class='suc-close'></i></div>")
                        indexHandler.PtGameList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,PtGameUrl: function()
        {
            $('a[data-method="ptGame"]').on('click', function(){
                var til = $(this).attr('data-title')
                $.post('/PtGame',{"gameid":$(this).attr("data-field")},function(gameUrl){
                    if(gameUrl=="")
                    {
                        return false;
                    }
                    window.open(gameUrl)
                },'text')
            })
        }
        ,CreateAgAccount: function()
        {
            $('a[data-method=CreateAgAccount]').on('click', function(){
                $.post('/CreateAgAccount',function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>开通游戏成功<i class='suc-close'></i></div>")
                        indexHandler.AgGameList()
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
            $('a[data-method="agGame"]').on('click', function(){
                var til = $(this).attr('data-title')
                $.post('/AgGameUrl',{"gameType":$(this).attr("data-field")},function(gameUrl){
                    if(gameUrl=="")
                    {
                        return false;
                    }
                    window.open(gameUrl)
                },'text')
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading(),index.MgSearchContent(),index.MgGameList(),index.Search(),index.MgGameButton(),index.PtGameButton(),
    index.AgGameButton()
})
