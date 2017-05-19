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
        ,changeLogin: function()
        {
            $('[data-st-panel-cls="J__swtLgReg"]').SimpleSwitchTab("click", function (evt, $tabs, $panel) {
				$tabs.parent().removeClass("on");
				$(this).parent().addClass("on");
			}).eq(1).trigger("click");
        }
        ,showError: function(msg)
        {
            $("#error").html('<i class="icon-exclamation-sign"></i>'+msg)
        }
    };
    win.login = com;
})(window);

$(function(){
    login.alertifyInif()
    //同意协议
    $(".J_chk_agree").on("click", function(){
        if($(this).find("input[name='agree']").is(":checked")){
            $(".regiestbtn").removeAttr("disabled").removeClass("disabled");
        }else{
            $(".regiestbtn").attr("disabled" , true).addClass("disabled");
        }
    });

    $("#J__lgReg-submit").on("click", function(){
        if($(this).attr("disabled")=="disabled") return false;
        if(checkform())
          {
            $(".regiestbtn").attr("disabled" , true).addClass("disabled");
            $.post('/regiestHandler',$('form[name=updateform]').serialize(),function(info){
                if(info=="用户注册成功")
                {
                    layui.use("layer", function(layer){
                        layer.alert('<div style="text-align:center">'+info+'</div>',{closeBtn:0,anim:3,btnAlign:'c',title:'开户提示'},function(){
                            if(info.indexOf('成功')!==false)
                            {
                                window.location.href="/"
                            }
                        })
                    })
                }
                else
                {
                    login.showError(info)
                }
                $(".regiestbtn").removeAttr("disabled").removeClass("disabled");
                return false;
            },'text')
          }
    })

    function checkform()
    {
        if( updateform.username.value.length<6 )
        {
            login.showError("用户名不能低于6位")
            return false;
        }
        if( updateform.password1.value=="" )
        {
            login.showError("密码不能为空")
            return false;
        }
        if( updateform.code.value=="" )
        {
            login.showError("验证码不能为空")
            return false;
        }
        return true
    }
})