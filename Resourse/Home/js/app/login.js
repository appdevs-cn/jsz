// 用户登录操作
window.isCheck=""
!(function(win){
    var com = {
        alertifyInif: function()
        {
            alertify.set({
                delay : 3000,
                buttonReverse : false
            });
        }
        ,AESEncrypt: function(string,param)
        {
            key = CryptoJS.enc.Utf8.parse(param);
            iv = CryptoJS.enc.Utf8.parse(param);
            var encrypted = CryptoJS.AES.encrypt(string, key, {
                iv: iv,
                mode:CryptoJS.mode.CBC,
                padding:CryptoJS.pad.ZeroPadding
            }).toString()
            return encrypted
        }
        ,changeLogin: function()
        {
            $('[data-st-panel-cls="J__swtLgReg"]').SimpleSwitchTab("click", function (evt, $tabs, $panel) {
				$tabs.parent().removeClass("on");
				$(this).parent().addClass("on");
			}).eq(0).trigger("click");
        }
        ,showError: function(msg)
        {
            $("#error").html('<i class="icon-exclamation-sign"></i>'+msg)
        }
        ,sliderValidate: function()
        {
            var slider = new SliderUnlock("#slider",{
                successLabelTip : "已获取验证信息"
            },function(){
                if($("input[name='name']").val()=="" || $("input[name='name']").val()==undefined)
                {
                    login.showError("请先输入用户名进行验证")
                    slider.reset()
                    slider.init()
                    $("#labelTip").html("拖动滑块验证")
                    return false;
                }
                else
                {
                    $.post("/checkVerif",{"username":$("input[name='name']").val()},function(info){
                        if(info!=false)
                        {
                            window.isCheck = info;
                            $("a[data-method=login]").trigger('click')
                        }   
                        else
                        {
                            login.showError("获取验证信息失败")
                            slider.reset()
                            slider.init()
                            $("#labelTip").html("拖动滑块验证")
                        }
                    },'text')
                }
            });
            slider.init()
            return slider
        }
        ,loginHandler: function()
        {
            if(window.isCheck=="")
            {
                login.showError("请先通过验证后登录")
            }
            else
            {
                var _name = $("input[name='name']").val();
                var _pwd = $("input[name='pwd']").val();
                var data = _name+"|"+_pwd+"|"+window.isCheck
                var encrypted = login.AESEncrypt(data,window.isCheck)
                var aes = encrypted;
                $('.btn-submit').removeAttr('data-method')
                $('.btn-submit').attr("disable","disable")
                $('.btn-submit').html('<i class="icon-spinner icon-spin icon-1x"></i>登录中..')
                $.post("loginHandler",{"uap":aes,"param":window.isCheck},function(info){
                    $('.btn-submit').removeAttr('disable')
                    $('.btn-submit').attr("data-method","login")
                    $('.btn-submit').html('登录')
                    login.LoginAfter()
                    var _info = info.split("-");
                    if(_info[0]==1)
                    {
                        $.post("/propellingLogin");
                        if(_info[3]==1)
                        {
                            layui.use('layer', function(layer){
                                var index = layer.confirm('账户使用的是初始密码,请前往用户管理修改？', {
                                    btn: ['前往修改密码','关闭'] 
                                    ,anim:3
                                    ,btnAlign: 'c'
                                    ,yes: function()
                                    {
                                        window.location.href = '/User/edit/type/changePwd';
                                        layer.close(index)
                                    }
                                    ,btn2: function()
                                    {
                                        window.location.href = '/index';
                                        layer.close(index)
                                    }
                                    ,cancel: function()
                                    {
                                        return false;
                                    }
                                    });
                            })
                            return
                        }
                        else
                        {
                            layui.use("layer", function(layer){
                                layer.msg('上次登录地址【'+_info[2]+'】', {
                                    offset: 't',
                                    anim: 6,
                                    time:2000,
                                    end: function(){
                                        window.location.href = '/index';
                                    }
                                });
                            })
                            return
                        }
                    }
                    else
                    {
                        login.showError(_info[1])
                        return
                    }
                },'text')
            }
        }
        ,LoginAfter: function()
        {
            var slider = login.sliderValidate()
            slider.reset(),slider.init(),$("#labelTip").html("拖动滑块验证")
            $("input").val("")
        }
        ,CheckGoogle: function()
        {
            $('input[name=name]').on('blur', function(){
                if($(this).val()=="") return false;
                $.post('/checkGoogle',{'username':$(this).val()}, function(isGoogle){
                    if(isGoogle)
                    {
                        $('a[data-method="oath"]').show()
                    }
                },'text')
            })
        }
        ,findUserPassword: function()
        {
            $.dialog.open({
                title: '找回密码',
                width: 500,
                height:360,
                type: 'prompt'
            }).ready(function(o) {
                o.html(tpl('#find_password'));
                initGettelvcode()
                login.GetVcode()
                layui.use('form', function(){
                    var form = layui.form();
                    form.on('submit(forgotPassword)', function(data){
                        var send = data.field
                        $.post("/forgetpassword",{"name":send.username,"tel":send.tel,"newpwd":send.newpwd,"telVcode":send.telVcode},function(msg){
                            $.dialog.close("*")
                            var arr = msg.split("-")
                            if(arr[0]==1)
                                alertify.success("<div class='text'><i class='ico-success'></i>"+arr[1]+"<i class='suc-close'></i></div>")
                            else
                                alertify.error("<div class='text'><i class='ico-error'></i>"+arr[1]+"<i class='err-close'></i></div>")
                        },'text');
                        return false;
                    });
                })
            })
        }
        ,ggoath: function()
        {
            layui.use('layer', function(layer){
                var index = layer.confirm('使用谷歌登录之后，密码登录将失效，是否登录？', {
                    btn: ['谷歌登录','密码登录'] 
                    ,anim:3
                    ,btnAlign: 'c'
                    }, function(){
                        layer.close(index)
                        $.dialog.open({
                            title: '谷歌登录',
                            width: 600,
                            height:380,
                            type: 'prompt'
                        }).ready(function(o) {
                            o.html(tpl('#oath_login'));
                            //生成动态二维码
                            var $name = $('input[name=oathusername]');
                            $name.val($('input[name=name]').val())
                            $('input[name=oathusername]').on('blur', function() {
                                if ($name.val() == "") {
                                    return;
                                } else {
                                    $.post("/createOathImage", { "name": $name.val() }, function(path) {
                                        if (path != null) {
                                            $("#pathimgpathhref").html(path.secret)
                                            $("#pathimgpathhrefdd").show();
                                        } else {
                                            
                                        }
                                    }, 'json')
                                }
                            }).trigger('blur')
                            layui.use('form', function(){
                                var form = layui.form()
                                form.on('submit(oathLogin)', function(data){
                                    var send = data.field
                                    $.post("/oathimg",{"name":send.oathusername,"oathcode":send.oathcode,"pwd":send.pwd},function(info){
                                        var _info = info.split("-");
                                        if (_info[0] == 1)
                                        {
                                            $.dialog.close("*")
                                            $.post("/propellingLogin");
                                            if (_info[2] == 1)
                                            {
                                                layui.use('layer', function(layer){
                                                    var index = layer.confirm('账户使用的是初始密码,请前往用户管理修改？', {
                                                        btn: ['前往修改密码','关闭'] 
                                                        ,anim:3
                                                        ,btnAlign: 'c'
                                                        }, function(){
                                                            window.location.href = '/User/edit';
                                                            layer.close(index)
                                                        }, function(){
                                                            window.location.href = '/index';
                                                        });
                                                })
                                            }
                                            else
                                            {
                                                layui.use("layer", function(layer){
                                                    layer.msg('上次登录地址【'+_info[2]+'】', {
                                                        offset: 't',
                                                        anim: 6,
                                                        time:2000,
                                                        end: function(){
                                                            window.location.href = '/index';
                                                        }
                                                    });
                                                })
                                            }
                                        }
                                        else
                                        {
                                            $.dialog.close("*")
                                            alertify.error("<div class='text'><i class='ico-error'></i>"+_info[1]+"<i class='err-close'></i></div>")
                                        }
                                    },'text');
                                    return false;
                                });
                            })
                        })
                    }, function(){
                        layer.close(index)
                    });
            })
        }
        ,GetVcode: function()
        {
            layui.use("layer", function(layer){
                $('a[data-method="GetForgerPwdMessageCode"]').on("click", function(){
                    var tel = $('input[name=tel]').val()
                    var username = $("input[name=username]").val()
                    if(tel=="" || username=="") return false;
                    var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                    $.post("/GetTelVcode",{"tel":tel,"username":username},function(info){
                        if(info)
                        {
                            alertify.success("<div class='text'><i class='ico-success'></i>短信验证码已发送，注意查收!<i class='suc-close'></i></div>")
                        }
                        else
                        {
                            alertify.error("<div class='text'><i class='ico-error'></i>短信验证码发送失败!<i class='err-close'></i></div>")
                        }
                        layer.close(index)
                    })
                })
            })
        }
    };
    win.login = com;
})(window);

$(function(){
    login.sliderValidate(),login.CheckGoogle()
    $("a[data-method=login]").click(function(){
        login.loginHandler()
    })
    $("a[data-method=find_password]").click(function(){
        login.findUserPassword()
    })
    $("a[data-method=oath]").click(function(){
        login.ggoath()
    })
    login.alertifyInif()
})

function initGettelvcode()
{
    if($.cookie("isclicketelvcode")!=1)
    {
        $.cookie("telvcode",null)
    }
    var reg = /^[0-9]*$/;
    var telvcode = $.cookie("telvcode")
    if(reg.test(telvcode))
    {
        var obj = document.getElementById("getelvcodeid")
        if(obj!=null)
        {
            if($.cookie("telvcode")==0)
            {
                obj.removeAttribute("disabled");
                obj.value="获取验证码"
                $.cookie("telvcode",null)
                $.cookie("isclicketelvcode",0)
            }
            else
            {
                obj.setAttribute("disabled", true);
                obj.value="重新发送(" + $.cookie("telvcode") + ")秒"
                var countdown = $.cookie("telvcode")
                countdown--;
                $.cookie("telvcode",countdown)
                setTimeout(function() {  
                    initGettelvcode()
                },1000)
            }
        }
    }
    else
    {
        $.cookie("telvcode",60)
    }
}

function SendTelVcodeFunc(obj)
{
    var tel = $('input[name=tel]').val()
    var username = $("input[name=username]").val()
    if(tel=="" || username=="") return false;
    $.cookie("isclicketelvcode",1)
    // 获取短信验证码         
   if($.cookie("telvcode")==0)
    {
        obj.removeAttribute("disabled");
        obj.value="获取验证码"
        $.cookie("telvcode",null)
        $.cookie("isclicketelvcode",0)
    }
    else
    {
        if($.cookie("telvcode")==60)
        {
            $.post("/GetTelVcode",{"tel":tel,"username":username},function(info){
                if(info)
                {
                    alertify.success("<div class='text'><i class='ico-success'></i>短信验证码已发送，注意查收!<i class='suc-close'></i></div>")
                }
                else
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>短信验证码发送失败!<i class='err-close'></i></div>")
                }
            })
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
                SendTelVcodeFunc(obj)
            },1000)
        }
    }
}