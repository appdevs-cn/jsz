var $doc = $(document)
var $regkeepmax = $('input[name=regkeepmax]');
var $keepmax = $('input[name=keepmax]');
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
        ,J__chkbox:function()
        {
            $(".J__chkbox").on("click", function(){
                $(this).siblings().removeClass('on')
                $(this).toggleClass("on");
            });
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.JszContent',function(){
                indexHandler.LoadingAfter()
            })
        }
        ,SelectMenuList: function()
        {
            $('.menuList').find('a').each(function(){
                $(this).on('click', function(){
                    $('.menuList').find('a').removeClass('selected')
                    $(this).addClass('selected')
                    if($(this).attr("data-field")=="OrdinaryAccount")
                    {
                        $("#OrdinaryAccount").show()
                        $("#ConnectionAccount").hide()
                        $("#ConnectionManagement").hide()
                    }
                    else if($(this).attr("data-field")=="ConnectionAccount")
                    {
                        $("#OrdinaryAccount").hide()
                        $("#ConnectionAccount").show()
                        $("#ConnectionManagement").hide()
                    }
                    else if($(this).attr("data-field")=="ConnectionManagement")
                    {
                        $("#OrdinaryAccount").hide()
                        $("#ConnectionAccount").hide()
                        $("#ConnectionManagement").show()
                    }
                })
            })
            $(".errorMsg").html("").hide()
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.J__chkbox(),indexHandler.SelectMenuList(),
            indexHandler.CheckKeepPoint(),indexHandler.CreateUser(),indexHandler.CreateReg(),indexHandler.Reviewquota(),indexHandler.CheckRegPoint(),
            indexHandler.DeleteReg(),indexHandler.InitCopy()
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
        ,showError: function(msg)
        {
            $(".errorMsg").html('<i class="icon-exclamation-sign"></i>'+msg)
        }
        ,CheckKeepPoint: function()
        {
            $doc.on('blur','input[name=keeppoint]',function(e) {
                var $v = $('input[name=keeppoint]').val();
                if($v!="")
                {
                    $v=$v.replace(/[^\d.]/g,'');
                    $v = (parseFloat($v)<0) ? 0.0 : parseFloat($v);
                    $v = (parseInt($v*10)>parseInt($keepmax.val()*10)) ? parseFloat($keepmax.val()) : parseFloat($v);
                    $v = parseFloat($v).toFixed(1);
                    $('input[name=keeppoint]').val($v);
                }
            })
        }
        ,CreateUser: function()
        {
            layui.use('layer', function(layer){
                $doc.on('click','button[data-method=create]',function(e){
                    var index = layer.load(2, {shade: [0.8, '#393D49'],time:15000});
                    var $type = "";
                    $(".group").each(function(){
                        if($(this).hasClass("on"))
                            $type = $(this).attr("data-field")
                    })
                    var $username = $('input[name=username]').val();
                    var $keeppoint = $('input[name=keeppoint]').val();
                    var $klckeeppoint = $('input[name=klckeeppoint]').val();
                    if(($type!=4 && $type!=5) || $type=="" || $username=="" || $keeppoint=="" || $klckeeppoint=="")
                    {
                        indexHandler.showError("必填项不能为空")
                        return false;
                    }
                    else
                    {
                        $.post('/createUser',{"group":$type,"username":$username,"keeppoint":$keeppoint,"klckeeppoint":$klckeeppoint},function(info){
                            var $a = info.split("-");
                            layer.close(index)
                            alertify.success("<div class='text'><i class='ico-success'></i>"+$a[0]+"<i class='suc-close'></i></div>")
                            $('input[name=username]').val("");
                            $('input[name=keeppoint]').val("");
                            $('input[name=klckeeppoint]').val("");
                        },'text')
                    }
                })
            })
        }
        ,CheckRegPoint: function()
        {
            $doc.on('blur','input[name=regkeeppoint]',function(e) {
                var $v = $('input[name=regkeeppoint]').val();
                if($v!="")
                {
                    $v=$v.replace(/[^\d.]/g,'');
                    $v = (parseFloat($v)<0) ? 0.0 : parseFloat($v);
                    $v = (parseInt($v*10)>parseInt($regkeepmax.val()*10)) ? parseFloat($regkeepmax.val()) : parseFloat($v);
                    $v = parseFloat($v).toFixed(1);
                    $('input[name=regkeeppoint]').val($v);
                }
            })
        }
        ,CreateReg: function()
        {
            $doc.on('click','button[data-method=createReg]',function(e){
                var $type = "";
                $(".reggroup").each(function(){
                    if($(this).hasClass("on"))
                        $type = $(this).attr("data-field")
                })
                var $limitday = $('select[name=limitday]').val();
                var $regkeeppoint = $('input[name=regkeeppoint]').val();
                var $regklckeeppoint = $('input[name=regklckeeppoint]').val();
                var $count = $('input[name=count]').val();
                if(($type!=4 && $type!=5) || $type=="" || $limitday=="" || $regkeeppoint=="" || $count=="" || $regklckeeppoint=="")
                {
                    indexHandler.showError("必填项不能为空")
                    return false;
                }
                else
                {
                    $.post('/createRegist',{"reggroup":$type,"limitday":$limitday,"keeppoint":$regkeeppoint,"count":$count,"regklckeeppoint":$regklckeeppoint},function(info){
                        if(info['flag']==true)
                        {
                            $.dialog.close('*');
                            alertify.success("<div class='text'><i class='ico-success'></i>注册连接生成成功<i class='suc-close'></i></div>")
                        }
                        else
                        {
                            $.dialog.close('*');
                            alertify.error("<div class='text'><i class='ico-error'></i>"+info['msg']+"<i class='err-close'></i></div>")
                        }
                    },'json')
                }
            })
        }
        ,Reviewquota:function()
        {
            $doc.on('click', 'button[data-method="reviewquota"]', function(e) {
                $.post('/getQuate',function(data) {
                    if(data==null) return;
                    $.dialog.open({ 
                        title: '剩余配额',
                        width: 500
                    }).ready(function(o){
                        o.html(tpl('#review_quota',{rows:[data]}))
                    })
                },'json')
            })
        }
        ,DeleteReg: function()
        {
            $doc.on('click', 'a[data-method="delete"]', function(e) {
                var $item = $(e.target).closest("tr")
                var $ser = $(this).attr('data-field');
                $.dialog.close('*');
                layui.use('layer', function(layer){
                var index = layer.confirm('你确定要删除该链接吗?', {
                    btn: ['确定','取消']
                    ,anim:3
                    ,btnAlign: 'c'
                    }, function(){
                        $.post('/delRegist',{'ser':$ser},function(bool){
                        if(bool)
                        {
                            alertify.success("<div class='text'><i class='ico-success'></i>删除成功<i class='suc-close'></i></div>")
                            $item.remove()
                        }
                        },'text')
                    });
                })
            })
        }
        ,InitCopy: function()
        {
            // 复制
            var copyregurl = new Clipboard('.copyregurl');
            copyregurl.on('success',function(){
                alertify.success("<div class='text'><i class='ico-success'></i>注册连接复制成功<i class='suc-close'></i></div>")
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})