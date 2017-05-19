var $doc = $(document)
var $p = $('input[name=p]')
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
                $("#list").html(tpl('#loading_list'))
                return false;
            });
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),
            indexHandler.getPtRecord(),indexHandler.search()
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
        ,getPtRecord: function()
        {
            $data = $("#PtFrom").serialize();
            $.post("/SearchPtRecord",$data,function(data){
                if(data.length==0)
                {
                    $("#list").html(tpl('#empty_pt_record'))
                    return false;
                }
                else
                {
                    $("#list").html(tpl('#pt_record',{rows:[data]}))
                    indexHandler.TrHover()
                    $(".pageCls").on("click",function(){
                        $p.val($(this).attr("p"));
                        indexHandler.getPtRecord();
                    })
                    return false;
                }
            },'json')
        }
        ,search: function()
        {
            $doc.on('click','button[data-method="search"]',function(e) {
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
            indexHandler.getPtRecord()
        })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})