var $doc = $(document)
!(function(win){
    var indexHandler = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,showAllBank: function()
        {
            // 展开银行
            $("#J__showAllBank").on("click", function(){
                if($(this).text() == "展开更多↓"){
                    $(this).text("收起更多↑");
                }else{
                    $(this).text("展开更多↓");
                }
                $(".moreBankList").slideToggle();
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
            indexHandler.NoticeSlider(),indexHandler.LoadTpl(),
            indexHandler.Logout(),indexHandler.ChangeUserPassword(),indexHandler.ChangePtPassword(),indexHandler.ChangeEbetPassword(),indexHandler.UpdateNicknameAndLoginask(),
            indexHandler.BindGoogle(),indexHandler.BindBankCard(),indexHandler.Setsecretsecurity(),indexHandler.Setsecuritycode(),
            indexHandler.Changesecuritycode(),indexHandler.BindTrueName(),indexHandler.Dropdown(),indexHandler.NavMenu(),
            indexHandler.GetMessageCode()
        }
        ,LoadBickInfomation: function()
        {
            // 获取用户的基本信息
            $.post("/getBickInfo",function(data){
                $('.panel-form').html(tpl('#basic_information',{rows:[data]}))
                indexHandler.UploadImage()
            },'json')
        }
        ,LoadTel: function()
        {
            // 获取用户电话号码信息
            layui.use("layer", function(layer){
                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                $.post("/isTel",function(data){
                    $('.panel-form').html(tpl('#phone_code',{rows:data}))
                    initGetVcode()
                    indexHandler.IsCheckTel()
                    indexHandler.BindTel()
                    indexHandler.UpdateTel()
                    layer.close(index)
                },'text')
            })
        }
        ,UpdateTel: function()
        {
            $('button[data-method="updateTel"]').on('click', function(){
                $("#telItem").show();
            })
        }
        ,IsCheckTel: function()
        {
            $('input[name=telphone]').on('blur', function(){
                var tel = $(this).val()
                $.post('/IsCheckTel',{'tel':tel},function(info){
                    if(!info)
                    {
                        indexHandler.showError('该号码已被绑定')
                        $('input[name=telphone]').val("")
                        return false;
                    }
                },'text')
            })
        }
        ,LoadEmail: function()
        {
            // 获取用户邮箱地址信息
            layui.use("layer", function(layer){
                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                $.post("/isEmail",function(data){
                    $('.panel-form').html(tpl('#email_addr',{rows:data}))
                    initGetEmailVcode()
                    indexHandler.IsCheckEmail()
                    indexHandler.BindEmail()
                    indexHandler.UpdateEmail()
                    layer.close(index)
                },'text')
            })
        }
        ,UpdateEmail: function()
        {
            $('button[data-method="updateEmail"]').on('click', function(){
                $("#emailItem").show();
            })
        }
        ,IsCheckEmail: function()
        {
            $('input[name=email]').on('blur', function(){
                var email = $(this).val()
                $.post('/IsCheckEmail',{'email':email},function(info){
                    if(!info)
                    {
                        indexHandler.showError('该邮箱已被绑定')
                        $('input[name=email]').val("")
                        return false;
                    }
                },'text')
            })
        }
        ,LoadRealname: function()
        {
            // 获取持卡人信息
            var index = layer.load(2, {shade: [0.8, '#393D49'],time:1000});
            $.post("/isBindRealname",function(data){
                $('.panel-form').html(tpl('#bind_name',{rows:data}))
                layer.close(index)
            },'json')
        }
        ,LoadBankCard: function()
        {
            // 获取银行卡资料
            $.post('/getBankInfo',function(info){
                if(info.isBindRealname=="")
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>请先绑定持卡人<i class='err-close'></i></div>")
                    $('.navList').find("a:eq(2)").trigger("click")
                    return false
                }
                else
                {
                    $.post("/isBindCard",function(data){
                        $('.panel-form').html(tpl('#bind_card',{rows:[data],realname:info.isBindRealname}))
                        indexHandler.showAllBank()
                        $bindprovince = $('select[name=bindprovince]');
                        $bindcity = $('select[name=bindcity]');
                        // 获取省份和城市信息
                        $.post('/getBankInfo',function(info){
                            var _bindprovincehtml = '<option>请选择</option>';
                            var $bindprovinceobj = eval('('+info.bindprovince+')');
                            for(var k in $bindprovinceobj)
                            {
                                _bindprovincehtml += '<option value="'+k+'">'+$bindprovinceobj[k]+'</option>';
                            }
                            $bindprovince.html(_bindprovincehtml);
                            $bindprovince.on("change",function(){
                                $.post('/getCity',{"province":$(this).val()},function(city){
                                    var _cityhtml = '<option>请选择</option>';
                                    var cityobj = eval('('+city.city+')');
                                    for(var k in cityobj)
                                    {
                                        _cityhtml += '<option value="'+k+'">'+cityobj[k]+'</option>';
                                    }
                                    $bindcity.html(_cityhtml);
                                },'json')
                            })
                        },'json')
                        // 选择银行
                        $('a[data-method="bindbank"]').on('click', function(){
                            $('a[data-method="bindbank"]').removeClass('selected')
                            $(this).addClass('selected')
                        })
                        indexHandler.SelectBindCard()
                        indexHandler.AddNewBankCard()
                    },'json')
                }
            },'json')
        }
        ,AddNewBankCard: function()
        {
            $('button[data-method="addNewBankCard"]').on('click',function(){
                $("#bankitem").show();
                $("#bankitemhr").show();
            })
        }
        ,LoadChangeLoginPassword: function()
        {
            // 获取修改登录密码模板
            $('.panel-form').html(tpl('#change_password'))
        }
        ,LoadChangePtPassword: function()
        {
            // 获取修改PT密码模板
            $('.panel-form').html(tpl('#change_pt_password'))
        }
        ,LoadChangeEbetPassword: function()
        {
            // 获取修改EBET密码模板
            $('.panel-form').html(tpl('#change_ebet_password'))
        }
        ,LoadChangeFuncCodePassword: function()
        {
            // 获取修改资金密码模板
            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
            $.post('/isSetfund',function(data){
                $('.panel-form').html(tpl('#set_security_code',{rows:data}))
                layer.close(index)
            },'text')
        }
        ,LoadSecurity: function()
        {
            // 获取修改资金密码模板
            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
            $.post('/isSecurity',function(data){
                $('.panel-form').html(tpl('#secret_security',{rows:data}))
                var $question = $("select[name=question]");
                var _html = '<option value="">请选择密保问题</option>';
                $.post("/getQuestionList",function(obj){
                    for(var i=0; i<obj.length; i++)
                    {
                        _html += '<option value="'+obj[i]["id"]+'">'+obj[i]["name"]+'</option>';
                    }
                    $question.html(_html);
                    layer.close(index)
                },'json')
            },'text')
        }
        ,LoadGoogle: function()
        {
            // 获取谷歌绑定模板
            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
            $.post('/isGoogle',function(data){
                $('.panel-form').html(tpl('#bind_google_verification',{rows:data}))
                $googleSecret = $("input[name=googleSecret]");
                $.post("/createGoogleSecret",function(info){
                    $googleSecret.val(info['secret']);
                    layer.close(index)
                },'json');
            },'text')
        }
        ,LoadInformation: function()
        {
            layui.use('layer', function(layer){
                var index = layer.load(2, {shade: [0.8, '#393D49'],time:15000});
                $.post('/getUserInfomation',function(data){
                    $('.panel-form').html(tpl('#Information_tpl',{rows:data}))
                    layer.close(index)
                },'json')
            })
        }
        ,LoadTpl: function()
        {
            $('a[data-method="loadTpl"]').on('click', function(e){
                $target = $(e.target)
                tplmode = $(this).attr('data-field')
                switch(tplmode)
                {
                    case 'basic_information':indexHandler.LoadBickInfomation();break;
                    case 'phone_code':indexHandler.LoadTel();break;
                    case 'email_addr':indexHandler.LoadEmail();break;
                    case 'bind_name':indexHandler.LoadRealname();break;
                    case 'bind_card':indexHandler.LoadBankCard();break;
                    case 'change_password':indexHandler.LoadChangeLoginPassword();break;
                    case 'set_security_code':indexHandler.LoadChangeFuncCodePassword();break;
                    case 'secret_security':indexHandler.LoadSecurity();break;
                    case 'bind_google_verification':indexHandler.LoadGoogle();break;
                    case 'information': indexHandler.LoadInformation();break;
                    case 'change_pt_password': indexHandler.LoadChangePtPassword();break;
                    case 'change_ebet_password': indexHandler.LoadChangeEbetPassword();break;
                }
                $('a[data-method="loadTpl"]').removeClass("on")
                $(this).addClass("on")
            })
        }
        ,showError: function(msg)
        {
            $(".errorMsg").html('<i class="icon-exclamation-sign"></i>'+msg)
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
        ,SelectBindCard: function()
        {
            $("#J__bindBankCard .bind__card").on("click", function(){
                $(this).addClass("on").siblings(".bind__card").removeClass("on");
                $.post("/ChangeBankCardMoren",{"id":$(this).attr('data-field')})
            });
        }
        ,UploadImage: function()
        {
            $('#fileupload').fileupload({
                dataType: 'json',
                done: function (e, data) {
                    if(data.result.status==false)
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>"+data.result.result+"<i class='err-close'></i></div>")
                        return;
                    }
                    else
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>头像上传成功<i class='suc-close'></i></div>")
                        $(".head").attr("src",data.result.result)
                    }
                }
            });
        }
        ,BindTel: function()
        {
            // 绑定电话号码
            $('button[data-method="bindTel"]').on('click', function(){
                var telphone = $('input[name=telphone]').val()
                var vcode = $('input[name=vcode]').val()
                if(telphone=="" || vcode=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return false;
                }
                $.post('/BindTel',{'tel':telphone,'vcode':vcode},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>电话号码绑定成功<i class='suc-close'></i></div>")
                        indexHandler.LoadTel()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>电话号码绑定失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,BindEmail: function()
        {
            $('button[data-method="BindEmail"]').on('click', function(){
                var email = $('input[name=email]').val()
                var emailVcode = $('input[name=emailVcode]').val()
                if(email=="" || emailVcode=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return false;
                }
                $.post('/BindEmail',{'email':email,'emailVcode':emailVcode},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>邮箱绑定成功<i class='suc-close'></i></div>")
                        indexHandler.LoadEmail()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>邮箱绑定失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,ChangeUserPassword: function()
        {
            $doc.on('click', 'button[data-method="changepassword"]', function(e) {
                var $oldpassword = $("input[name=oldpassword]").val();
                var $newpassword = $("input[name=newpassword]").val();
                var $comfirmpassword = $('input[name=comfirmpassword]').val();
                if($oldpassword=="" || $newpassword=="" || $comfirmpassword=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return;
                }
                else
                {
                    if($newpassword!=$comfirmpassword)
                    {
                        indexHandler.showError('两次输入的密码不一致')
                        return;
                    }
                    else
                    {
                        $.post("/changePassword",{"oldpassword":$oldpassword,"newpassword":$newpassword,"comfirmpassword":$comfirmpassword},function(msg){
                            if(msg==true)
                            {
                                alertify.success("<div class='text'><i class='ico-success'></i>登录密码修改成功!<i class='suc-close'></i></div>")
                                indexHandler.LoadChangeLoginPassword()
                                return false
                            }
                            else
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>登录密码修改失败!<i class='err-close'></i></div>")
                                return false;
                            }
                        },'text')
                    }
                }
            })
        }
        ,ChangePtPassword: function()
        {
            $doc.on('click', 'button[data-method="changeptpassword"]', function(e) {
                var $oldpassword = $("input[name=ptoldpassword]").val();
                var $newpassword = $("input[name=ptnewpassword]").val();
                var $comfirmpassword = $('input[name=ptcomfirmpassword]').val();
                if($oldpassword=="" || $newpassword=="" || $comfirmpassword=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return;
                }
                else
                {
                    if($newpassword!=$comfirmpassword)
                    {
                        indexHandler.showError('两次输入的密码不一致')
                        return;
                    }
                    else
                    {
                        layui.use('layer',function(layer){
                            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                            $.post("/changePtPassword",{"oldpassword":$oldpassword,"newpassword":$newpassword,"comfirmpassword":$comfirmpassword},function(msg){
                                layer.close(index)
                                if(msg==true)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>PT密码修改成功!<i class='suc-close'></i></div>")
                                    indexHandler.LoadChangePtPassword()
                                    return false
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>PT密码修改失败!<i class='err-close'></i></div>")
                                    return false;
                                }
                            },'text')
                        })
                    }
                }
            })
        }
        ,ChangeEbetPassword: function()
        {
            $doc.on('click', 'button[data-method="changeebetpassword"]', function(e) {
                var $oldpassword = $("input[name=ebetoldpassword]").val();
                var $newpassword = $("input[name=ebetnewpassword]").val();
                var $comfirmpassword = $('input[name=ebetcomfirmpassword]').val();
                if($oldpassword=="" || $newpassword=="" || $comfirmpassword=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return;
                }
                else
                {
                    if($newpassword!=$comfirmpassword)
                    {
                        indexHandler.showError('两次输入的密码不一致')
                        return;
                    }
                    else
                    {
                        layui.use('layer',function(layer){
                            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                            $.post("/changeEbetPassword",{"oldpassword":$oldpassword,"newpassword":$newpassword,"comfirmpassword":$comfirmpassword},function(msg){
                                layer.close(index)
                                if(msg==true)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>Ebet密码修改成功!<i class='suc-close'></i></div>")
                                    indexHandler.LoadChangeEbetPassword()
                                    return false
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>Ebet密码修改失败!<i class='err-close'></i></div>")
                                    return false;
                                }
                            },'text')
                        })
                    }
                }
            })
        }
        ,BindTrueName: function()
        {
            $doc.on('click', 'button[data-method="bindname"]', function(e) {
                $realname = $("input[name=realname]").val();
                $fundcode = $('input[name="fundcode"]').val()
                if($realname=="" || $fundcode=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return false;
                }
                else
                {
                    $.post("/bindRealname",{"name":$realname,"fundcode":$fundcode},function(msg){
                        if(msg==true)
                        {
                            alertify.success("<div class='text'><i class='ico-success'></i>持卡人绑定成功!<i class='suc-close'></i></div>")
                            indexHandler.LoadRealname()
                            return false
                        }
                        else
                        {
                            alertify.error("<div class='text'><i class='ico-error'></i>持卡人绑定失败!<i class='err-close'></i></div>")
                        }
                    },'text')
                }
            })
        }
        ,UpdateNicknameAndLoginask: function()
        {
            $doc.on('click', 'button[data-method="updateNickname"]', function(e) {
                $newNickname = $('input[name=newNickname]').val();
                $loginask = $('input[name=loginask]').val();
                if($newNickname.length>5)
                {
                    indexHandler.showError('昵称最长5个字符')
                    return false;
                }
                if($newNickname=="" || $loginask=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return false;
                }
                else
                {
                    $.post("/updateNickname",{'nickname':$newNickname,'loginask':$loginask},function(msg){
                        if(msg)
                        {
                            indexHandler.LoadBickInfomation()
                            $(".shownickname").html($newNickname)
                            alertify.success("<div class='text'><i class='ico-success'></i>基本资料修改成功!<i class='suc-close'></i></div>")
                        }
                        else
                        {
                            alertify.error("<div class='text'><i class='ico-error'></i>基本资料修改失败!<i class='err-close'></i></div>")
                        }

                    },'text')
                }
            })
        }
        ,BindGoogle: function()
        {
            $doc.on('click', 'button[data-method="bindgoogleverification"]', function(e) {
                $googleSecret = $("input[name=googleSecret]").val();
                $SecurityPassword = $("input[name=SecurityPassword]").val();
                if($googleSecret=="" || $SecurityPassword=="")
                {
                    indexHandler.showError("必填项不能为空")
                    return;
                }
                else
                {
                    $.post("/bindGoogleSecret",{"googleSecret":$googleSecret,"SecurityPassword":$SecurityPassword},function(msg){
                        if(msg==true)
                        {
                            alertify.success("<div class='text'><i class='ico-success'></i>谷歌验证绑定成功!<i class='suc-close'></i></div>")
                            indexHandler.LoadGoogle()
                            return false
                        }
                        else
                        {
                            alertify.error("<div class='text'><i class='ico-error'></i>谷歌验证绑定失败!<i class='err-close'></i></div>")
                            return false;
                        }
                    },'text')
                }
            })
        }
        ,BindBankCard: function()
        {
            $doc.on('click', 'button[data-method="bindcard"]', function(e) {
                $bindbank = "";
                $('a[data-method="bindbank"]').each(function(){
                    if($(this).hasClass('selected'))
                        $bindbank = $(this).attr('data-field')
                })
                $bindprovince = $('select[name=bindprovince]').val();
                $bindcity = $('select[name=bindcity]').val();
                $accountnum = $('input[name=accountnum]').val();
                $comfirmaccountnum = $('input[name=comfirmaccountnum]').val();
                $bindSecurityPassword = $('input[name=bindSecurityPassword]').val();

                if($bindbank=="" || $bindprovince=="" || $bindcity=="" || $accountnum=="" || $comfirmaccountnum=="" || $bindSecurityPassword=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return;
                }
                else
                {
                    if($accountnum!=$comfirmaccountnum)
                    {
                        indexHandler.showError('两次输入的银行卡号不一致')
                        return;
                    }
                    else
                    {
                        // 验证银行卡卡号
                        var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                        $.post('/checkBankCode',{"account_num":$accountnum},function(code){
                            if(code==1000)
                            {
                                $.post('/bindCard',{"bank_id":$bindbank,"account_num":$accountnum,"comfirm_account_num":$comfirmaccountnum,"bankprov":$bindprovince,"bankcity":$bindcity,"bindSecurityPassword":$bindSecurityPassword},function(msg){
                                    if(msg=="error1")
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>绑定银行卡数量超出3张!<i class='err-close'></i></div>")
                                        return;
                                    }
                                    else if(msg=="error2")
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>资金密码不正确,绑定失败!<i class='err-close'></i></div>")
                                        return;
                                    }
                                    else if(msg=="error3")
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>银行卡绑定失败!<i class='err-close'></i></div>")
                                        return;
                                    }
                                    else if(msg=="error4")
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>必填项不能为空!<i class='err-close'></i></div>")
                                        return;
                                    }
                                    else if(msg=="error5")
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>两次输入的银行卡号不一致!<i class='err-close'></i></div>")
                                        return;
                                    }
                                    else if(msg=="error6")
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>该银行卡卡号已被绑定过!<i class='err-close'></i></div>")
                                        return;
                                    }
                                    else if(msg=="success")
                                    {
                                        alertify.success("<div class='text'><i class='ico-success'></i>银行卡绑定成功!<i class='suc-close'></i></div>")
                                        indexHandler.LoadBankCard()
                                        return;
                                    }
                                    layer.close(index)
                                },'text')
                            }
                            else
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>卡号与持卡用户不匹配!<i class='err-close'></i></div>")
                                return;
                            }
                        },'text')
                        
                    }
                }
            })
        }
        ,Setsecretsecurity: function()
        {
            $doc.on('click', 'button[data-method="setsecretsecurity"]', function(e) {
                var $item = $(e.target).closest('div')
                var $question = $("select[name=question]").val();
                var $answer = $("input[name=answer]").val();
                var $SecurityPassword = $("input[name=SecurityPassword]").val();
                if($question=="" || $answer=="" || $SecurityPassword=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return;
                }
                else
                {
                    $.post("/setQuestion",{"question":$question,"answer":$answer,"SecurityPassword":$SecurityPassword},function(msg){
                        if(msg==true)
                        {
                            alertify.success("<div class='text'><i class='ico-success'></i>密保问题设置成功!<i class='suc-close'></i></div>")
                            indexHandler.LoadSecurity()
                            return false
                        }
                        else
                        {
                            alertify.error("<div class='text'><i class='ico-error'></i>密保问题设置失败!<i class='err-close'></i></div>")
                            return false;
                        }
                    },'text')
                }
                return false
            })
        }
        ,Setsecuritycode: function()
        {
            $doc.on('click', 'button[data-method="setsecuritycode"]', function(e) {
                var $item = $(e.target).closest('div')
                var $SecurityPassword = $("input[name=SecurityPassword]").val();
                var $comfSecurityPassword = $('input[name=comfSecurityPassword]').val();
                if($SecurityPassword=="" || $comfSecurityPassword=="")
                {
                    indexHandler.showError('必填项不能为空')
                    return;
                }
                else
                {
                    if($SecurityPassword!=$comfSecurityPassword)
                    {
                        indexHandler.showError('两次输入的密码不一致')
                        return;
                    }
                    else
                    {
                        $.post("/setSecurityPassword",{"SecurityPassword":$SecurityPassword,"comfSecurityPassword":$comfSecurityPassword},function(msg){
                            if(msg==true)
                            {
                                alertify.success("<div class='text'><i class='ico-success'></i>资金密码设置成功!<i class='suc-close'></i></div>")
                                indexHandler.LoadChangeFuncCodePassword()
                                return false
                            }
                            else
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>资金密码设置失败!<i class='err-close'></i></div>")
                                return false;
                            }
                        },'text')
                    }
                }
            })
        }
        ,Changesecuritycode: function()
        {
            $doc.on('click', 'button[data-method="changesecuritycode"]', function(e) {
                var $item = $(e.target).closest('div')
                var $oldSecurityPassword = $("input[name=oldSecurityPassword]").val();
                var $newSecurityPassword = $("input[name=newSecurityPassword]").val();
                var $comfirmSecurityPassword = $('input[name=comfirmSecurityPassword]').val();
                if($oldSecurityPassword=="" || $newSecurityPassword=="" || $comfirmSecurityPassword=="")
                {
                    indexHandler.showError('密码不能为空')
                    return;
                }
                else
                {
                    if($newSecurityPassword!=$comfirmSecurityPassword)
                    {
                        indexHandler.showError('两次输入的密码不一致')
                        return;
                    }
                    else
                    {
                        $.post("/changeSecurityPassword",{"oldSecurityPassword":$oldSecurityPassword,"newSecurityPassword":$newSecurityPassword,"comfirmSecurityPassword":$comfirmSecurityPassword},function(msg){
                            if(msg==true)
                            {
                                layui.use('layer',function(layer){
                                    layer.msg('<div style="text-align:center;padding-top:5px">资金密码修改成功</div>',{time:2000,end:function(){
                                        $("input[name=oldSecurityPassword]").val("");
                                        $("input[name=newSecurityPassword]").val("");
                                        $('input[name=comfirmSecurityPassword]').val("");
                                    }})
                                })
                                return false
                            }
                            else
                            {
                                layui.use('layer',function(layer){
                                    layer.msg('<div style="text-align:center;padding-top:5px">资金密码修改失败</div>',{time:2000})
                                })
                                return false;
                            }
                        },'text')
                    }
                }
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
        ,GetMessageCode: function()
        {
            $("#GetMessageCode").on('click', function(){
                var telp = $('input[name=telphone]').val()
                var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                $.post('/MessageAuthenticationCode',{'tel':telp},function(info){
                    if(info)
                    {
                        layui.use('layer',function(layer){
                            layer.msg('<div style="text-align:center;padding-top:5px">短信已发送，请注意查看！</div>',{time:2000})
                        })
                    }
                    else
                    {
                        layui.use('layer',function(layer){
                            layer.msg('<div style="text-align:center;padding-top:5px">获取验证码失败！</div>',{time:2000})
                        })
                    }
                    layer.close(index)
                },'text')
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})



function initGetVcode()
{
    if($.cookie("isclicktelvcode")!=1)
    {
        $.cookie("telvcode",null)
    }
    var reg = /^[0-9]*$/;
    var telvcode = $.cookie("telvcode")
    if(reg.test(telvcode))
    {
        var obj = document.getElementById("getvcodeid")
        if(obj!=null)
        {
            if($.cookie("telvcode")==0)
            {
                obj.removeAttribute("disabled");
                obj.value="获取验证码"
                $.cookie("telvcode",null)
                $.cookie("isclicktelvcode",0)
            }
            else
            {
                obj.setAttribute("disabled", true);
                obj.value="重新发送(" + $.cookie("telvcode") + ")秒"
                var countdown = $.cookie("telvcode")
                countdown--;
                $.cookie("telvcode",countdown)
                setTimeout(function() {  
                    initGetVcode()
                },1000)
            }
        }
    }
    else
    {
        $.cookie("telvcode",60)
    }
}

function GetVcode(obj)
{
    var telphone = $('input[name=telphone]').val()
    if(telphone=="") return false;
    //验证电话号码
    var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/;
    if(!myreg.test(telphone))
    {
        alertify.error("<div class='text'><i class='ico-error'></i>请输入有效的电话号码!<i class='err-close'></i></div>")
        return false;
    }
    $.cookie("isclicktelvcode",1)
    var telvcode = $.cookie("telvcode")
    // 获取短信验证码         
   if($.cookie("telvcode")==0)
    {
        obj.removeAttribute("disabled");
        obj.value="获取验证码"
        $.cookie("telvcode",null)
        $.cookie("isclicktelvcode",0)
    }
    else
    {
        if($.cookie("telvcode")==60)
        {
            $.post('/GetVcode',{'tel':telphone},function(info){
                if(info)
                {
                    alertify.success("<div class='text'><i class='ico-success'></i>短信验证码已发送，注意查收!<i class='suc-close'></i></div>")
                }
                else
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>短信验证码发送失败!<i class='err-close'></i></div>")
                }
            },'text')
        }
        var reg = /^[0-9]*$/;
        if(reg.test($.cookie("telvcode")))
        {
            obj.setAttribute("disabled", true);
            obj.value="重新发送(" + $.cookie("telvcode") + ")秒"
            var countdown = $.cookie("telvcode")
            countdown--;
            $.cookie("telvcode",countdown)

            setTimeout(function() {  
                GetVcode(obj)
            },1000)
        }
    }
}




function initGetEmailVcode()
{
    if($.cookie("isclickemailvcode")!=1)
    {
        $.cookie("emailvcode",null)
    }
    var reg = /^[0-9]*$/;
    var emailvcode = $.cookie("emailvcode")
    if(reg.test(emailvcode))
    {
        var obj = document.getElementById("getemailvcodeid")
        if(obj!=null)
        {
            if($.cookie("emailvcode")==0)
            {
                obj.removeAttribute("disabled");
                obj.value="获取验证码"
                $.cookie("emailvcode",null)
                $.cookie("isclickemailvcode",0)
            }
            else
            {
                obj.setAttribute("disabled", true);
                obj.value="重新发送(" + $.cookie("emailvcode") + ")秒"
                var countdown = $.cookie("emailvcode")
                countdown--;
                $.cookie("emailvcode",countdown)
                setTimeout(function() {  
                    initGetEmailVcode()
                },1000)
            }
        }
    }
    else
    {
        $.cookie("emailvcode",60)
    }
}

function SendEmailVcodeFunc(obj)
{
    var email  = $('input[name=email]').val()
    if(email=="") return false;
    var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
    if(!myreg.test(email))
    {
        alertify.error("<div class='text'><i class='ico-error'></i>请输入有效的电子邮件地址!<i class='err-close'></i></div>")
        return false;
    }
    $.cookie("isclickemailvcode",1)
    // 获取短信验证码         
   if($.cookie("emailvcode")==0)
    {
        obj.removeAttribute("disabled");
        obj.value="获取验证码"
        $.cookie("emailvcode",null)
        $.cookie("isclickemailvcode",0)
    }
    else
    {
        if($.cookie("emailvcode")==60)
        {
            $.post('/SendEmailVcode',{"email":email},function(info){
                 if(info)
                {
                     alertify.success("<div class='text'><i class='ico-success'></i>邮件已发出，请注意查收!<i class='suc-close'></i></div>")
                 }
                else
                {
                     alertify.error("<div class='text'><i class='ico-error'></i>邮件发送失败!<i class='err-close'></i></div>")
                 }
             },'text')
        }
        var reg = /^[0-9]*$/;
        if(reg.test($.cookie("emailvcode")))
        {
            obj.setAttribute("disabled", true);
            obj.value="重新发送(" + $.cookie("emailvcode") + ")秒"
            var countdown = $.cookie("emailvcode")
            countdown--;
            $.cookie("emailvcode",countdown)

            setTimeout(function() {  
                SendEmailVcodeFunc(obj)
            },1000)
        }
    }
}