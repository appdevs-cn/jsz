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
            indexHandler.getProxyAddRecord(),indexHandler.search()
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
        ,getProxyAddRecord: function()
        {
            layui.use("layer",function(layer){
                $data = $("#recordAddFrom").serialize();
                $.post("/selectSelfAddRecord",$data,function(data){
                    if(data.length==0)
                    {
                        $("#list").html(tpl('#empty_record_list'))
                        return false;
                    }
                    else
                    {
                        $("#list").html(tpl('#add_record_list',{rows:[data]}))
                        indexHandler.TrHover()
                        $("a[data-method=detail]").on("click",function(){
                            $buyid = $(this).attr("data-field");
                            $.dialog.open({
                                title: '订单详情',
                                width: 900,
                                height:550,
                                btnText:["关 闭"],
                                type: 'alert'
                            }).ready(function(o) {
                                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                                $.post('/selfaddRecordDetail',{"id":$buyid},function(json){
                                    if(json.length==0) return false;
                                    layer.close(index)
                                    o.html(tpl('#detail_record',{rows:[json]}))
                                },'json')
                            })
                        })
                        $(".pageCls").on("click",function(){
                            $p.val($(this).attr("p"));
                            indexHandler.getProxyAddRecord();
                        })
                        $("a[data-method='list']").on("click",function(){
                            $buyid = $(this).attr("data-field");
                        $.dialog.open({
                            title: '追号列表',
                            width: 900,
                            height:500,
                            type: 'alert'
                        }).ready(function(o) {
                            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                            $.post('/addRecordList',{"id":$buyid},function(json){
                                if(json.length==0) return false;
                                o.html(tpl('#record_list',{rows:[json]}))
                                indexHandler.TrHover()
                                layer.close(index)
                                $("a[data-method='addcancel']").on("click",function(e){
                                    var $item = $(e.target).closest('tr')
                                    $addbuyid = $(this).attr("data-field");
                                    $.post("/addrecordCancel",{"id":$addbuyid},function(msg){
                                        if(msg==true)
                                        {
                                            alertify.success("<div class='text'><i class='ico-success'></i>订单取消成功<i class='suc-close'></i></div>")
                                            $item.find("td:eq(8)").html('<span style="color:red">已撤单</span>');
                                            $item.find("td:eq(9)").html('无操作');
                                        }
                                        else
                                        {
                                            alertify.error("<div class='text'><i class='ico-error'></i>订单取消失败<i class='err-close'></i></div>")
                                        }
                                    },'text')
                                })
                                $addbuyid = $("a[data-method='addcancel']:eq(0)").attr("data-field");
                                if($.cookie("allcancelno"+$addbuyid)==1)
                                {
                                    $('button[data-method="allCancel"]').remove()
                                    return false
                                }
                                $('button[data-method="allCancel"]').on('click', function(){
                                    if(typeof($addbuyid)=="undefined"){
                                        $.cookie("allcancelno"+$addbuyid, 1)
                                        $.dialog.close("*")
                                        alertify.error("<div class='text'><i class='ico-error'></i>订单已全部撤单<i class='err-close'></i></div>")
                                        return false;
                                    }
                                    else
                                    {
                                        $.post("/allrecordCancel",{"id":$addbuyid},function(msg){
                                            if(msg==true)
                                            {
                                                $.cookie("allcancelno"+$addbuyid, 1)
                                                $.dialog.close("*")
                                                alertify.success("<div class='text'><i class='ico-success'></i>追号订单撤单处理中<i class='suc-close'></i></div>")
                                            }
                                            else
                                            {
                                                $.dialog.close("*")
                                                alertify.error("<div class='text'><i class='ico-error'></i>订单取消失败<i class='err-close'></i></div>")
                                            }
                                        },'text')
                                        $.dialog.close('*');
                                        return false;
                                    }
                                })
                            },'json')
                        })
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
                indexHandler.getProxyAddRecord()
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})