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
                if($("select[name=type]").val()==1)
                {
                    if($('select[name=rechargeType]').val()==1)
                    {
                        $("#list").html(tpl('#onlinepay_loading_list'))
                        return false;
                    }
                    if($('select[name=rechargeType]').val()==2)
                    {
                        $("#list").html(tpl('#bank_loading_list'))
                        return false;
                    }
                }
                if($("select[name=type]").val()==2)
                {
                    $("#list").html(tpl('#withdraw_loading_list'))
                    return false;
                }
            });
            $("select[name=type]").change(function(){
                if($(this).val()==1)
                {
                    $('select[name=rechargeType]').css("display","inline");
                    $('select[name=rechargeType]').css("height","36px");
                    $('select[name=rechargeType]').css("min-width","250px");
                    $("select[name=status]").html('<option value="1" selected>充值成功</option><option value="0">待支付</option>');
                    $('select[name=rechargeType]').on("change",function(){
                        if($(this).val()==2)
                        {
                            $('input[name=fuyan]').show();
                        }
                        else
                        {
                            $('input[name=fuyan]').hide();
                        }
                    })
                }
                if($(this).val()==2)
                {
                    $('input[name=fuyan]').hide();
                    $('select[name=rechargeType]').css("display","none");
                    $('select[name=rechargeType]').css("height","36px");
                    $('select[name=rechargeType]').css("min-width","250px");
                    $("select[name=status]").html('<option value="">全部</option><option value="1">已提交</option><option value="2">已完成</option><option value="3">处理中</option><option value="4">失败</option>');
                }
            })
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),
            indexHandler.getRechargeWithdrawRecord(),indexHandler.search()
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
        ,getRechargeWithdrawRecord: function()
        {
            $data = $("#rechargewithdrawFrom").serialize();
            $.post("/getSelfRechargeWithdrawRecord",$data,function(data){
                if($("select[name=type]").val()==1)
                {
                    if($('select[name=rechargeType]').val()==1)
                    {
                        if(data=="") {
                            $("#list").html(tpl('#empty_onlinepay_record'))
                            return false;
                        }
                        if(data.length==0)
                        {
                            $("#list").html(tpl('#empty_onlinepay_record'))
                            return false;
                        }
                        else
                        {
                            $("#list").html(tpl('#onlinepay_record',{rows:[data]}))
                            indexHandler.TrHover()
                        }
                    }
                    else if($('select[name=rechargeType]').val()==2)
                    {
                        if(data=="") {
                            $("#list").html(tpl('#empty_bank_record'))
                            return false;
                        }
                        if(data.length==0)
                        {
                            $("#list").html(tpl('#empty_bank_record'))
                            return false;
                        }
                        else
                        {
                            $("#list").html(tpl('#bank_record',{rows:[data]}))
                            indexHandler.TrHover()
                        }
                    }
                    
                }
                else if($("select[name=type]").val()==2)
                {
                    if(data==null) {
                        $("#list").html(tpl('#empty_onlinepay_record'))
                        return false;
                    }
                    if(data.length==0)
                    {
                        $("#list").html(tpl('#empty_withdraw_record'))
                        return false;
                    }
                    else
                    {
                        $("#list").html(tpl('#withdraw_record',{rows:[data]}))
                        indexHandler.TrHover()
                    }
                }
                $(".pageCls").on("click",function(){
                    $p.val($(this).attr("p"));
                    indexHandler.getRechargeWithdrawRecord();
                })
                return false;
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
                indexHandler.getRechargeWithdrawRecord();
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})