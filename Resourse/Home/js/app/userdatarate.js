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
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),
            indexHandler.getDatarateRecord(),indexHandler.search()
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
        ,getDatarateRecord: function()
        {
            $data = $("#datarateFrom").serialize();
            $.post("/SearchDatarateRecord",$data,function(data){
                if(data.length==0)
                {
                    $("#list").html(tpl('#empty_datarate_record'))
                    return false;
                }
                else
                {
                    $("#list").html(tpl('#datarate_record',{rows:[data]}))
                    indexHandler.paifa()
                    indexHandler.TrHover()
                    $(".pageCls").on("click",function(){
                        $p.val($(this).attr("p"));
                        indexHandler.getDatarateRecord();
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
            indexHandler.getDatarateRecord()
        })
        }
        ,paifa: function()
        {
            $('a[data-method="paifa_datarate"]').on('click', function(){
                $.post('/paifaDatarate',{"id":$(this).attr("pd")},function(info){
                    var infoarr = info.split("-")
                    if(infoarr[0]==1)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>"+infoarr[1]+"<i class='suc-close'></i></div>")
                        indexHandler.getDatarateRecord()
                    }
                    else if(infoarr[0]==0)
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>"+infoarr[1]+"<i class='err-close'></i></div>")
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