var $doc = $(document)
var $min = $('input[name=min]');
var $max = $('input[name=max]');
var $type = $('input[name=type]');
var $rechargeMoney = $('input[name=rechargeMoney]');
var thbankId="";
var rechargebankId="";
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
        ,SecondMenu: function()
        {
            $('.navList').find('a').each(function(){
                $(this).on('click', function(){
                    $('.navList').find('a').removeClass('on')
                    $(this).addClass('on')
                    if($(this).attr('data-field')=="rechargeContent")
                    {
                        $("#rechargeContent").show()
                        $("#withDrawContent").hide()
                        $("#transferContent").hide()
                    }
                    else if($(this).attr('data-field')=="withDrawContent")
                    {
                        indexHandler.CheckUserBank()
                        $("#transferContent").hide()
                        $("#rechargeContent").hide()
                        $("#withDrawContent").show()
                    }
                    else if($(this).attr('data-field')=="transferContent")
                    {
                        var index = layer.load(2, {shade: [0.8, '#393D49'],time:10000});
                        $.post("/SearchUserThriedAccount",function(data){
                            $('.agAccount').html(data.agAccount)
                            $('.ebetAccount').html(data.ebetAccount)
                            $('.mgAccount').html(data.mgAccount)
                            $('.chessAccount').html(data.ptAccount)
                            $("#transferContent").show()
                            $("#rechargeContent").hide()
                            $("#withDrawContent").hide()
                            layer.close(index)
                        },'json')
                        
                    }
                    $(".errorMsg").html("").hide()
                })
            })
        }
        ,SelectWithDrawBank: function()
        {
            $(".withdrawBank").on("click", function(){
                $(".withdrawBank").removeClass('selected')
                $(this).addClass('selected')
            })
        }
        ,GetWithdrawMessageCode: function()
        {
            $('button[data-method="GetWithdrawMessageCode"]').on('click',function(){
                var tel = $('input[name=withDrawTelphone]').val()
                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                $.post('/GetWithdrawMessageCode',{'tel':tel},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>短信验证码已发送，注意查收!'!<i class='suc-close'></i></div>")
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>此电话号码不存在!<i class='err-close'></i></div>")
                    }
                    layer.close(index)
                },'text')
            })
        }
        ,CheckUserBank: function()
        {
            $.post('/CheckUserIsBindBank',function(info){
                if(!info)
                {
                    layui.use('layer', function(layer){
                        layer.msg('请先绑定银行卡资料', {
                            offset: 't',
                            anim: 6,
                            time:3000,
                            shade: [0.8, '#393D49'],
                            end: function(){
                                window.location.href="/User/edit/type/bindbank";
                            }
                        });
                    })
                }
            },'text')
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
            $('#payContent').html(tpl('#pay_th'));
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.LoadTh(),indexHandler.LoadThWx(),indexHandler.LoadWx(),indexHandler.LoadQq(),indexHandler.LoadZfb(), indexHandler.LoadThZfb(), indexHandler.LoadBank(),indexHandler.CheckMoney(),
            indexHandler.SelThBank(),indexHandler.SuerHandler(),indexHandler.SecondMenu(),indexHandler.SelectWithDrawBank(),indexHandler.LoadZhf(),
            indexHandler.CheckWithDrawMoney(),indexHandler.AplyWithDraw(),indexHandler.GetWithdrawMessageCode(),indexHandler.Transfer()
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
        ,LoadTh: function()
        {
            $doc.on('click', 'a[data-method="th"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_th'));
                $min.val(50);
                $max.val("");
                $type.val(4);
                $rechargeMoney.val("");
                $("#isNotWx").html('50~100000')
                $(".error_tips").hide()
                indexHandler.SelThBank()
            })
        }
        ,LoadZhf: function()
        {
            $doc.on('click', 'a[data-method="zhf"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_zhf'));
                $min.val(100);
                $max.val("");
                $type.val(12);
                $rechargeMoney.val("");
                $("#isNotWx").html('100~100000')
                $(".error_tips").hide()
                indexHandler.SelThBank()
            })
        }
        ,LoadThWx: function()
        {
            $doc.on('click', 'a[data-method="thwx"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_th_wx'));
                $min.val(50);
                $max.val(5000);
                $type.val(11);
                $rechargeMoney.val("");
                $("#isNotWx").html('50~100000')
                $(".error_tips").hide()
            })
        }
        ,LoadWx: function()
        {
            $doc.on('click', 'a[data-method="wx"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_wx'));
                $min.val(100);
                $max.val(5000);
                $type.val(8);
                $rechargeMoney.val("");
                $("#isNotWx").html('100~5000')
                $(".error_tips").hide()
            })
        }
        ,LoadQq: function()
        {
            $doc.on('click', 'a[data-method="qq"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_qq'));
                $min.val(100);
                $max.val(5000);
                $type.val(14);
                $rechargeMoney.val("");
                $("#isNotWx").html('100~5000')
                $(".error_tips").hide()
            })
        }
        ,LoadZfb: function()
        {
            $doc.on('click', 'a[data-method="zfb"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_alipay'));
                $min.val(100);
                $max.val(5000);
                $type.val(9);
                $rechargeMoney.val("");
                $("#isNotWx").html('100~5000')
                $(".error_tips").hide()
            })
        }
        ,LoadThZfb: function()
        {
            $doc.on('click', 'a[data-method="thzfb"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                var $o = $('#payContent');
                $o.html(tpl('#pay_th_alipay'));
                $min.val(50);
                $max.val(5000);
                $type.val(13);
                $rechargeMoney.val("");
                $("#isNotWx").html('50~100000')
                $(".error_tips").hide()
            })
        }
        ,LoadBank: function()
        {
            $doc.on('click', 'a[data-method="bank"]', function(e) {
                var $item = $(e.target).closest('div');
                $item.find("a").removeClass("selected")
                $(this).addClass("selected")
                $.post('/SearchBankInfo',function(data){
                    $('#payContent').html(tpl('#pay_bank',{rows:data}));
                    indexHandler.InitCopy()
                    $doc.on("click", '.rechargebank', function(e){
                        $(".rechargebank").removeClass("selected")
                        $(this).addClass("selected")
                        rechargebankId = $(this).attr("data-field")
                    })
                },'json')
                $min.val(50);
                $max.val("");
                $type.val(10);
                $rechargeMoney.val("");
                $("#isNotWx").html('50~1000000')
                $(".error_tips").hide()
            })
        }
        ,InitCopy: function()
        {
            // 复制
            var copyRealename = new Clipboard('.copybankcard');
            copyRealename.on('success',function(){
                alertify.success("<div class='text'><i class='ico-success'></i>银行卡号复制成功<i class='suc-close'></i></div>")
            })
            var copybankUser = new Clipboard('.copybankUser');
            copybankUser.on('success',function(){
                alertify.success("<div class='text'><i class='ico-success'></i>银行账户复制成功<i class='suc-close'></i></div>")
            })
            var copyFuyan = new Clipboard('.copyFuyan');
            copyFuyan.on('success',function(){
                alertify.success("<div class='text'><i class='ico-success'></i>附言复制成功<i class='suc-close'></i></div>")
            })
        }
        ,CheckMoney: function()
        {
            $("input[name='rechargeMoney']").on("blur",function(e){
                var min = $min.val();
                var max = ($max.val()=="") ? 10000000 : $max.val();
                if(parseInt($rechargeMoney.val())<parseInt(min))
                    $rechargeMoney.val(min);
                else if(parseInt($rechargeMoney.val())>parseInt(max))
                    $rechargeMoney.val(max);
            })
        }
        ,SelThBank: function()
        {
            $doc.on("click", '.bank', function(e){
                $(".bank").removeClass("selected")
                $(this).addClass("selected")
                thbankId = $(this).attr("data-field")
            })
        }
        ,showError: function(msg)
        {
            $(".errorMsg").html('<i class="icon-exclamation-sign"></i>'+msg)
        }
        ,SuerHandler: function()
        {
            $doc.on('click','button[data-method=sure]', function(e) {
                $('.bank').each(function(){
                    if($(this).hasClass("selected"))
                    {
                        thbankId = $(this).attr("data-field")
                    }
                })
                if($type.val()==4)
                {
                    if(thbankId=="" || $rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $bankval = thbankId;
                        var $moneyval = $rechargeMoney.val();

                        $.post("/payOrder",{"bank":$bankval,"money":$moneyval},function(data){
                            if(data!=null)
                            {
                                $.dialog.open({
                                    title: '订单详情',
                                    width: 500,
                                    height:280,
                                    type: 'prompt'
                                }).ready(function(o){
                                    o.html(tpl('#pay_order',{rows:[data]}))
                                    layui.use('form', function(){
                                        var form = layui.form();
                                        form.on('submit(rechargeBtn)', function(data){
                                            $('form[name=payInfo]').submit();
                                            return false;
                                        });
                                    })
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>订单生成失败<i class='err-close'></i></div>")
                                return false;
                            }
                        },'json')
                    }
                }
                if($type.val()==8)
                {
                    if($rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $moneyval = $rechargeMoney.val();
                        var index = layer.load(2, {shade: [0.8, '#393D49'],time:15000});
                        $.post("/WxPayOrder",{"money":$moneyval},function(data){
                            if(data!="")
                            {
                                $.dialog.open({
                                    title: '微信充值',
                                    width: 500,
                                    height:280,
                                    type: 'alert'
                                }).ready(function(o){
                                    o.html(tpl('#wx_pay_order',{rows:data}))
                                    layer.close(index)
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>充值繁忙<i class='err-close'></i></div>")
                                return false;
                            }
                        },'text')
                    }
                }
                if($type.val()==9)
                {
                    if($rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $moneyval = $rechargeMoney.val();
                        var index = layer.load(2, {shade: [0.8, '#393D49'],time:15000});
                        $.post("/AlipyPayOrder",{"money":$moneyval},function(data){
                            if(data!="")
                            {
                                $.dialog.open({
                                    title: '支付宝充值',
                                    width: 500,
                                    height:280,
                                    type: 'alert'
                                }).ready(function(o){
                                    o.html(tpl('#alipy_pay_order',{rows:data}))
                                    layer.close(index)
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>充值繁忙<i class='err-close'></i></div>")
                                return false;
                            }
                        },'text')
                    }
                }
                if($type.val()==10)
                {
                    var rechargeMoney = $("input[name=rechargeMoney]").val()
                    var depositor = $("input[name=depositor]").val()
                    if(rechargeMoney=="" || depositor=="")
                    {
                        indexHandler.showError('必填项不能为空')
                        return false;
                    }
                    else
                    {
                        $.post('/BankRecharge',{'money':rechargeMoney,'depositor':depositor,'bankid':rechargebankId},function(data){
                            if(data.length==0)
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>网银充值繁忙<i class='err-close'></i></div>")
                                return false;
                            }
                            else
                            {
                                $.dialog.open({
                                    title: '网银充值详情',
                                    width: 600,
                                    height:430,
                                    type: 'prompt'
                                }).ready(function(o){
                                    o.html(tpl('#pay_bank_order',{rows:data}))
                                    var bankurl = data.bankurl
                                    layui.use('form', function(){
                                        var form = layui.form();
                                        form.on('submit(rechargeBankBtn)', function(data){
                                            window.open(bankurl)
                                            return false;
                                        });
                                    })
                                })
                            }
                        },'json')
                    }
                }

                if($type.val()==11)
                {
                    if(thbankId=="" || $rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $bankval = thbankId;
                        var $moneyval = $rechargeMoney.val();

                        $.post("/thwxpayOrder",{"bank":$bankval,"money":$moneyval},function(data){
                            if(data!=null)
                            {
                                $.dialog.open({
                                    title: '订单详情',
                                    width: 500,
                                    height:280,
                                    type: 'prompt'
                                }).ready(function(o){
                                    o.html(tpl('#thwx_pay_order',{rows:[data]}))
                                    layui.use('form', function(){
                                        var form = layui.form();
                                        form.on('submit(rechargeBtn)', function(data){
                                            $('form[name=payInfo]').submit();
                                            return false;
                                        });
                                    })
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>订单生成失败<i class='err-close'></i></div>")
                                return false;
                            }
                        },'json')
                    }
                }


                if($type.val()==12)
                {
                    if(thbankId=="" || $rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $bankval = thbankId;
                        var $moneyval = $rechargeMoney.val();

                        $.post("/zhfpayOrder",{"bank":$bankval,"money":$moneyval},function(data){
                            if(data!=null)
                            {
                                $.dialog.open({
                                    title: '订单详情',
                                    width: 500,
                                    height:280,
                                    type: 'prompt'
                                }).ready(function(o){
                                    o.html(tpl('#zhf_pay_order',{rows:[data]}))
                                    layui.use('form', function(){
                                        var form = layui.form();
                                        form.on('submit(rechargeBtn)', function(data){
                                            $('form[name=payInfo]').submit();
                                            return false;
                                        });
                                    })
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>订单生成失败<i class='err-close'></i></div>")
                                return false;
                            }
                        },'json')
                    }
                }

                if($type.val()==13)
                {
                    if(thbankId=="" || $rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $bankval = thbankId;
                        var $moneyval = $rechargeMoney.val();

                        $.post("/thalipypayOrder",{"bank":$bankval,"money":$moneyval},function(data){
                            if(data!=null)
                            {
                                $.dialog.open({
                                    title: '订单详情',
                                    width: 500,
                                    height:280,
                                    type: 'prompt'
                                }).ready(function(o){
                                    o.html(tpl('#thalipy_pay_order',{rows:[data]}))
                                    layui.use('form', function(){
                                        var form = layui.form();
                                        form.on('submit(rechargeBtn)', function(data){
                                            $('form[name=payInfo]').submit();
                                            return false;
                                        });
                                    })
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>订单生成失败<i class='err-close'></i></div>")
                                return false;
                            }
                        },'json')
                    }
                }

                if($type.val()==14)
                {
                    if($rechargeMoney.val()=="")
                    {
                        $.dialog.close('*');
                        indexHandler.showError('必填项不能为空')
                    }
                    else
                    {
                        var $moneyval = $rechargeMoney.val();
                        var index = layer.load(2, {shade: [0.8, '#393D49'],time:15000});
                        $.post("/QqPayOrder",{"money":$moneyval},function(data){
                            if(data!="")
                            {
                                $.dialog.open({
                                    title: 'QQ充值',
                                    width: 500,
                                    height:280,
                                    type: 'alert'
                                }).ready(function(o){
                                    o.html(tpl('#qq_pay_order',{rows:data}))
                                    layer.close(index)
                                })
                            }
                            else
                            {
                                $.dialog.close('*');
                                alertify.error("<div class='text'><i class='ico-error'></i>充值繁忙<i class='err-close'></i></div>")
                                return false;
                            }
                        },'text')
                    }
                }
            })
        }
        ,CheckWithDrawMoney: function()
        {
            $doc.on('blur', 'input[name=real_money]', function(e) {
                var $realMoney = $('input[name=real_money]');
                var $mix = $("#withdrawmin").text()
                var $max = $("#withdrawmax").text()
                if($realMoney.val()=="") return;
                if(Number($realMoney.val())<Number($mix) || Number($realMoney.val())>Number($max))
                {
                    $.dialog.close('*');
                    indexHandler.showError('提款金额区间'+$mix+'元-'+$max+'元')
                    $('input[name=real_money]').val("")
                    return false
                }
                else
                {
                    if(Number($('input[name=account]').val())<Number($realMoney.val()))
                    {
                        $.dialog.close('*');
                        indexHandler.showError("账户金额不足")
                        $('input[name=real_money]').val("")
                        return;
                    }
                }
            })
        }
        ,WithDrawInit: function()
        {
            $('input[name=real_money]').val("");
            $('input[name=accPwd]').val("");
            $('input[name=answer]').val("");
            $('input[name=withDrawTelphone]').val("")
            $('input[name=withDrawVcode]').val("")
        }
        ,AplyWithDraw: function()
        {
            $doc.on('click', 'button[data-method=sub]', function(e) {
                var $bankVal;
                $(".withdrawBank").each(function(){
                    if($(this).hasClass('selected'))
                        $bankVal = $(this).attr("data-field")
                })
                var $money = $('input[name=real_money]').val();
                var $curpassword = $('input[name=accPwd]').val();
                var $appkeyVal = $('input[name=appkey]').val();
                var $answer = $('input[name=answer]').val();
                //var $withDrawTelphone = $('input[name=withDrawTelphone]').val()
                //var $withDrawVcode = $('input[name=withDrawVcode]').val()
                if($bankVal=="" || $money=="" || $curpassword=="" || $appkeyVal=="" || $answer=="")
                {
                    indexHandler.showError("必填项不能为空")
                    return;
                }
                else
                {
                    var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                    $.post('/deposit',{"bankVal":$bankVal,"money":$money,"curpassword":$curpassword,"appkeyVal":$appkeyVal,"answer":$answer},function(info){
                        $('input[name=appkey]').val(info['appkey']);
                        layer.close(index)
                        if(info['flag'])
                        {
                            $.dialog.close('*');
                            alertify.success("<div class='text'><i class='ico-success'></i>"+info['msg']+"<i class='suc-close'></i></div>")
                            $(".financeMoney").html(parseFloat(Number($(".financeMoney").html())-Number($money)).toFixed(4))
                            $('input[name=financeMoneyName]').val(parseFloat(Number($('input[name=financeMoneyName]').val())-Number($money)).toFixed(4))
                            indexHandler.WithDrawInit();
                            return;
                        }
                        else
                        {
                            $.dialog.close('*');
                            alertify.error("<div class='text'><i class='ico-error'></i>"+info['msg']+"<i class='err-close'></i></div>")
                            return;
                        }
                    },'json')
                }
            })
        }
        ,getMoney: function()
        {
            $.post("/getMoney",function(money){
                var arr = money.split('-')
                $(".usermoney").html("可用余额 ￥"+arr[0]);
                $(".wallet_account").html("分红钱包 ￥"+arr[1]);
            },"text")
        }
        ,Transfer: function()
        {
            $doc.on('click','button[data-method=transferSub]', function(){
                var from = $("select[name=fromAccount]").val()
                var to = $("select[name=toAccount]").val()
                var money = $("input[name=transMoney]").val()
                var fundcode = $('input[name=fundcode]').val()
                if(from==to || (from!=1 && to!=1)) return false;
                if(money=="" || fundcode=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return false;
                }
                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                $.post('/Transfer',{'money':money,'from':from,'to':to,'fundcode':fundcode},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>资金转移成功<i class='suc-close'></i></div>")
                        if(from==1 && to==6)
                        {
                            $(".financeMoney").html(parseFloat(Number($(".financeMoney").html())-Number(money)).toFixed(4))
                            $(".wallet_account").html(parseFloat(Number($(".wallet_account").html())+Number(money)).toFixed(4))
                        }
                        else if(from==6 && to==1)
                        {
                            $(".financeMoney").html(parseFloat(Number($(".financeMoney").html())+Number(money)).toFixed(4))
                            $(".wallet_account").html(parseFloat(Number($(".wallet_account").html())-Number(money)).toFixed(4))
                        }
                        $("input[name=transMoney]").val("")
                        $('input[name=fundcode]').val("")
                        $.post("/SearchUserThriedAccount",function(data){
                            $('.agAccount').html(data.agAccount)
                            $('.ebetAccount').html(data.ebetAccount)
                            $('.mgAccount').html(data.mgAccount)
                            $('.chessAccount').html(data.ptAccount)
                            layer.close(index)
                        },'json')
                        indexHandler.getMoney()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>资金转移失败<i class='err-close'></i></div>")
                        layer.close(index)
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