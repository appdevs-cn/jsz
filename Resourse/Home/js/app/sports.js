!(function(win){
    var bbinHandler = {
        LayerConfig: function()
        {
            layui.use('layer',function(layer){
                layer.config({
                    skin:'layer-ext-yi',
                    extend:'/default/skin.css'
                });
            })
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.container-fluid',function(){
                bbinHandler.LoadingAfter()
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".load-container").remove();
            $("body").removeClass('c-index-before').addClass('tytz-bg')
            $(".container-fluid").show();
            bbinHandler.LayerConfig(),bbinHandler.ShrinkLeft(),bbinHandler.ShrinkTop(),bbinHandler.RefreshMoney(),bbinHandler.DayToNight(),bbinHandler.SoundSwitch(),
            bbinHandler.Logout(),bbinHandler.Building()
        }
        ,getMoney: function()
        {
            $.post("/getMoney",function(money){
                var arr = money.split('-')
                $(".usermoney").text(arr[0]);
                $(".wallet_account").text(arr[1]);
            },"text")
        }
        ,ShrinkLeft: function()
        {
            // 收缩左边菜单栏
            $('.c-logo-arrow').bind('click', function () {
                if( $('.c-leftsidebar').attr('status')=='0'){
                    $("#zsyContent").removeClass("zsy-content");
                    $("#zsyContent").addClass("zsy-content-s");
                    $('.c-leftsidebar').attr('class','col-sm-1 c-leftsidebar').attr('status','1');
                    $(".c-leftsidebar").addClass("shou");
                    $('.c-header').attr('class','col-sm-11 c-header');
                    $('.c-logo-arrow').removeClass().addClass('c-logo-arrow-right')
                }else{
                    $('.c-leftsidebar').attr('class','col-sm-2 c-leftsidebar').attr('status','0').removeAttr('style');
                    $('.c-header').attr('class','col-sm-10 c-header');
                    $("#zsyContent").addClass("zsy-content");
                    $("#zsyContent").removeClass("zsy-content-s");
                    $('.c-logo-arrow-right').removeClass().addClass('c-logo-arrow')
                }
            });
        }
        ,ShrinkTop: function()
        {
            // 顶部菜单收缩
            $("#menu").find("li").each(function(){
                $(this).click(function(){
                    $(".cls_menu").removeClass("active");
                    $(this).find("a").addClass("active");
                    if($(this).find("a").attr("data-field")=="menu") {
                        if($(".all-menu").is(":hidden")){
                            $(".all-menu").show();
                        } else {
                            $(".all-menu").hide();
                        }
                    }
                });
            });
        }
        ,RefreshMoney: function()
        {
            // 金额刷新
            $(".getnewmoney").bind("click",function(){
                $.post("/getMoney",function(money){
                    var arr = money.split('-')
                    $(".usermoney").text(arr[0]);
                    $(".wallet_account").text(arr[1]);
                },"text")
            })
        }
        ,DayToNight: function()
        {
            //切换白天模式和夜晚模式
            $('#changeState').bootstrapSwitch({'state': false})
            $('#changeState').on('switchChange.bootstrapSwitch', function (e, state) {
                if (state) {
                    //夜晚模式 移除白天模式的样式文件
                    var targetelement = "link"
                    var targetattr = "href"
                    var allsuspects = document.getElementsByTagName(targetelement)
                    for (var i = allsuspects.length; i >= 0; i--) {
                        if (allsuspects[i] && allsuspects[i].getAttribute(targetattr) != null && allsuspects[i].getAttribute(targetattr).indexOf('/Resourse/Home/css/day.css') != -1)
                            allsuspects[i].parentNode.removeChild(allsuspects[i])
                    }
                    //加载夜晚模式的文件
                    var fileref = document.createElement("link")
                    fileref.setAttribute("rel", "stylesheet")
                    fileref.setAttribute("type", "text/css")
                    fileref.setAttribute("href", '/Resourse/Home/css/night.css')
                    document.getElementsByTagName("head")[0].appendChild(fileref)
                }
                else {
                    //白天模式 移除夜晚模式的样式文件
                    var targetelement = "link"
                    var targetattr = "href"
                    var allsuspects = document.getElementsByTagName(targetelement)
                    for (var i = allsuspects.length; i >= 0; i--) {
                        if (allsuspects[i] && allsuspects[i].getAttribute(targetattr) != null && allsuspects[i].getAttribute(targetattr).indexOf('/Resourse/Home/css/night.css') != -1)
                            allsuspects[i].parentNode.removeChild(allsuspects[i])
                    }
                    //加载夜晚模式的文件
                    var fileref = document.createElement("link")
                    fileref.setAttribute("rel", "stylesheet")
                    fileref.setAttribute("type", "text/css")
                    fileref.setAttribute("href", '/Resourse/Home/css/day.css')
                    document.getElementsByTagName("head")[0].appendChild(fileref)
                }
            })
        }
        ,SoundSwitch: function()
        {
            // 声音开关
            $("#soundSwitch").bind('click',function(){
                if($(this).attr('data-field')==1)
                {
                    $.post("/sound",{'type':'bonussound','status':0},function(info){
                        if(info=="ok")
                        {
                            $("#soundSwitch").attr('data-field', 0)
                            $("#soundSwitch").find('img').attr('src','/Resourse/Home/images/day/sound_off.png')
                        }
                    },'text')
                }
                else
                {
                    $.post("/sound",{'type':'bonussound','status':1},function(info){
                        if(info=="ok")
                        {
                            $("#soundSwitch").attr('data-field', 1)
                            $("#soundSwitch").find('img').attr('src','/Resourse/Home/images/day/sound_on.png')
                        }
                    },'text')
                }
            })
        }
        ,Logout: function()
        {
            // 退出系统
            $.dialog.close('*');
            $(document).on('click', 'a[data-method=out]', function(e) {
                layui.use('layer', function(layer){
                    var index = layer.confirm('<div style="text-align:center;padding-top:20px">确定要退出系统吗？</div>', {
                        btn: ['退出','玩一会'] 
                        ,anim:3
                        ,area:['200px','180px']
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
        ,Building: function()
        {
            $(".tytz-btn").bind("click", function(){
                layui.use("layer", function(layer){
                    layer.msg('<div style="text-align:center;">Server is Unreachable!</div>')
                })
            })
        }
    }
    win.bbin = bbinHandler;
})(window);
$(function(){
    bbin.Loading()
})