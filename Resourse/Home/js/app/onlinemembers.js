var $doc = $(document)
var $contents = $('div.cont', $doc)
var $uid = $('input[name=uid]');
var $p = $('input[name=p]');
!(function(win){
    var indexHandler = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
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
                $(".panel-lkList").html(tpl('#loading_list'))
                return false;
            });
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.getList(),indexHandler.search()
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
        ,getList: function()
        {
            _page = $p.val();
            $.post('/onlineSearchMember',{"p":_page},function(data){
                if(data.length==0)
                {
                    $(".panel-lkList").html(tpl('#empty_member_list'))
                    return false;
                }
                window.onlinenowarr = new Array();
                for(var i=0; i<data.length; i++)
                {
                    window.onlinenowarr.push(data[i]['sec']);
                }
                socket.on("OnlineStatus",function(info){
                    var onlineArray = new Array();
                    for(var o in info)
                    {
                        onlineArray.push(o);
                    }
                    var nowuser = window.onlinenowarr;
                    var isHave = false;
                    var $filter = new Array();
                    for(var j=0; j<nowuser.length; j++)
                    {
                        if(jQuery.inArray( nowuser[j], onlineArray )!=-1)
                        {
                            isHave = true;
                            $filter.push(data[j]);
                        }
                    }
                    if(isHave==false)
                    {
                        $(".panel-lkList").html(tpl('#empty_member_list'))
                        return false;
                    }
                    else
                    {
                        $(".panel-lkList").html(tpl('#member_list',{rows:[$filter]}))
                        $(".pageCls").on("click",function(){
                            $p.val($(this).attr("p"));
                            indexHandler.getList();
                        })
                    }
                })
            },'json')
        }
        ,searchGetList: function()
        {
            $page = $p.val();
            $username = $('input[name=username]').val();
            $starttime = $('input[name=starttime]').val();
            $endtime = $('input[name=endtime]').val();
            if($starttime=="" && $endtime=="")
            {

            }
            else
            {
                if($starttime=="" || $endtime=="")
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>请输入开始时间和结束时间进行查询<i class='err-close'></i></div>")
                    return;
                }
            }
            $mincapital = $('input[name=mincapital]').val();
            $maxcapital = $('input[name=maxcapital]').val();
            if($mincapital=="" && $maxcapital=="")
            {

            }
            else
            {
                if($mincapital=="" || $maxcapital=="")
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>请输入最低余额和最高余额进行查询<i class='err-close'></i></div>")
                    return;
                }
            }
            $.post('/onlineSearchMember',{"p":$page,"username":$username,"starttime":$starttime,"endtime":$endtime,"mincapital":$mincapital,"maxcapital":$maxcapital},function(data){
                if(data.length==0)
                {
                    $(".panel-lkList").html(tpl('#empty_member_list'))
                    return false;
                }
                window.onlinenowarr = new Array();
                for(var i=0; i<data.length; i++)
                {
                    window.onlinenowarr.push(data[i]['sec']);
                }
                socket.on("OnlineStatus",function(info){
                    var onlineArray = new Array();
                    for(var o in info)
                    {
                        onlineArray.push(o);
                    }
                    var nowuser = window.onlinenowarr;
                    var isHave = false;
                    var $filter = new Array();
                    for(var j=0; j<nowuser.length; j++)
                    {
                        if(jQuery.inArray( nowuser[j], onlineArray )!=-1)
                        {
                            isHave = true;
                            $filter.push(data[j]);
                        }
                    }
                    if(isHave==false)
                    {
                        $(".panel-lkList").html(tpl('#empty_member_list'))
                        return false;
                    }
                    else
                    {
                        $(".panel-lkList").html(tpl('#member_list',{rows:[$filter]}))
                        $(".pageCls").on("click",function(){
                            $p.val($(this).attr("p"));
                            indexHandler.searchGetList();
                        })
                    }
                })
            },'json')
        }
        ,search: function()
        {
            $doc.on('click','button[data-method="search"]',function(e) {
                indexHandler.searchGetList()
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})