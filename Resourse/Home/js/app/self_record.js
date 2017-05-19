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
            layui.use("layer", function(layer){
                $data = $("#selfRecordFrom").serialize();
                $.post("/selectSelfRecord",$data,function(data){
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
                            $.dialog.open({
                                title: '订单详情',
                                width: 800,
                                height:590,
                                btnText:["关 闭"],
                                type: 'alert'
                            }).ready(function(o) {
                                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                                $.post('/selfRecordDetail',{"id":$buyid},function(json){
                                    if(json.length==0) return false;
                                    layer.close(index)
                                    o.html(tpl('#detail_record',{rows:[json]}))
                                },'json')
                            })
                        })
                        $("a[data-method=cancel]").on("click",function(e){
                            var $item = $(e.target).closest('tr')
                            $buyid = $(this).attr("data-field");
                            $.post("/recordCancel",{"id":$buyid},function(msg){
                                if(msg==true)
                                {
                                    $.dialog.close("*")
                                    alertify.success("<div class='text'><i class='ico-success'></i>订单取消成功<i class='suc-close'></i></div>")
                                    $item.find("td:eq(7)").html('<span style="color:red">已撤单</span>');
                                    $("a[data-method=cancel]").remove();
                                }
                                else
                                {
                                    $.dialog.close("*")
                                    alertify.error("<div class='text'><i class='ico-error'></i>订单取消失败<i class='err-close'></i></div>")
                                }
                                return;
                            },'text')
                        })
                        return false;
                    }
                },'json')
            })
        }
        ,search: function()
        {
            $doc.on('click','button[data-method="search"]',function(e) {
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
            indexHandler.getProxyRecord()
        })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})