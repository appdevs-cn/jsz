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
            indexHandler.getProxyRecord(),indexHandler.search()
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
        ,getProxyRecord: function()
        {
            $data = $("#recordForm").serialize();
            $.post("/selectProxyRecord",$data,function(data){
                if(data.length==0)
                {
                    $("#list").html(tpl('#empty_record_list'))
                    return false;
                }
                else
                {
                    $("#list").html(tpl('#record_list',{rows:[data]}))
                    indexHandler.TrHover()
                    $(".pageCls").on("click",function(){
                        $p.val($(this).attr("p"));
                        indexHandler.getProxyRecord();
                    })
                    $("a[data-method=detail]").on("click",function(){
                        $buyid = $(this).attr("data-field");
                        $.post('/recordDetail',{"id":$buyid},function(json){
                            if(json.length==0) return false;
                                $.dialog.open({
                                title: '订单详情',
                                width: 800,
                                height:590,
                                btnText:["关 闭"],
                                type: 'alert'
                            }).ready(function(o){
                                o.html(tpl('#detail_record',{rows:[json]}))
                            })
                        },'json')
                    })
                    return false;
                }
            },'json')
        }
        ,search: function()
        {
            $doc.on('click','button[data-method="search"]',function(e) {
                $username = $('input[name=username]').val();
                $starttime = $('input[name=starttime]').val();
                $endtime = $('input[name=endtime]').val();
                $serialNumber = $('input[name=serialNumber]').val();
                $lotteryName = $('select[name=lotteryName]').val();
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
                if($serialNumber!="")
                {
                    if($lotteryName=="")
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>请选择对应期号的彩票种类<i class='err-close'></i></div>")
                        return;
                    }
                }
                indexHandler.getProxyRecord()
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})