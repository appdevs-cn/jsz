window.socket;
!(function(win){
    var socketHandler = {
        Init: function()
        {
            window.socket = io('https://chart.jsz000.com/');
            socketHandler.SocketEvent()
        }
        ,getMoney: function()
        {
            $.post("/getMoney",function(money){
                var arr = money.split('-')
                $(".usermoney").html("可用余额 ￥"+arr[0]);
                $(".wallet_account").html("分红钱包 ￥"+arr[1]);
            },"text")
        }
        ,Chat: function()
        {
            if($("input[name=uid]").val()!="") {
                layui.use('layim', function (layim) {
                    layim.config({
                        init: {
                            url: '/onlineList'
                            , data: {}
                        }
                        , title: '上下级聊天'
                        ,initSkin:'4.jpg'
                        , isgroup: false
                        ,right:'80%'
                        ,voice:'default.mp3'
                        , min: true
                        ,notice: true
                        ,isAudio:true
                        , copyright: true
                    });
                    layim.on('sendMessage', function (data) {
                        window.socket.emit("toChart", JSON.stringify(data));
                    });

                    window.socket.on("replay", function (obj) {
                        layim.getMessage(JSON.parse(obj));
                    });
                    window.socket.on("addUser", function (obj) {
                        layim.addList(JSON.parse(obj));
                    })
                    layim.on('online', function (data) {
                        window.socket.emit("changeOnlineStatus",data+'-'+$("input[name=uid]").val());
                    });
                    window.socket.on("replayOnlineStatus",function(data){
                        var statusArray = (window.onlineStatus!=null) ? window.onlineStatus : new Array();
                        _a = data.split('-');
                        statusArray[_a[1]] = _a[0];
                        window.onlineStatus = statusArray;
                    })
                    layim.on('ready', function (res) {
                        var nowuser = new Array();
                        $("li[data-type=friend]").each(function (e) {
                            nowuser.push($(this).attr("id"));
                        })
                        window.socket.on("OnlineStatus", function (info) {
                            var onlineArray = new Array();
                            for (var o in info) {
                                if(window.onlineStatus!=null)
                                {
                                    var onStatus = window.onlineStatus[o];
                                    if(onStatus=="online")
                                    {
                                        onlineArray.push("layim-friend" + o);
                                    }
                                }
                                else
                                {
                                    onlineArray.push("layim-friend" + o);
                                }
                            }
                            for (var j = 0; j < nowuser.length; j++) {
                                if (jQuery.inArray(nowuser[j], onlineArray) != -1) {
                                    $("#" + nowuser[j]).css("filter", "grayscale(0%)");
                                }
                                else {
                                    $("#" + nowuser[j]).css("filter", "grayscale(100%)");
                                }
                            }
                        })
                });
            });
            }
        }
        ,PlayBCTSound: function(src)
        {
            if("\v"=="v")
            { 
                $('#BCTMessageDIV').html('<embed id="play1" src="'+src+'"/>'); 
            }
            else
            {
                $('#BCTMessageDIV').html('<audio id="play1" autoplay="autoplay"><source src="'+src+'"'+ 'type="audio/wav"/></audio>');
            }
        }
        ,SocketEvent: function()
        {
            var $uid = $('input[name=uid]').val();
            var $path = $('input[name=path]').val();
            window.socket.on("connect", function() {
                if ($uid != "" && $path != "")
                    window.socket.emit("login", $uid + '-' + $path);

                //上线通知
                window.socket.on("IsOnlineing", function(uid) {
                    socketHandler.PlayBCTSound('/Resourse/Home/audio/Ring.wav')
                    alertify.error("<div class='text'><i class='ico-error'></i>"+uid+"<i class='err-close'></i></div>")
                })

                //监听是否有新的邮件
                window.socket.on("messagemail", function(content) {
                    socketHandler.PlayBCTSound('/Resourse/Home/audio/Ring.wav')
                    alertify.error("<div class='text'><i class='ico-error'></i>"+content+"<i class='err-close'></i></div>")
                    var msgcount = Number($(".totalMessage").html())+1
                    $(".totalMessage").html(msgcount)
                })
            })

            window.socket.on("message", function($data) {
                var $json = $data.split('-')
                if($json[1]!=7)
                {
                    socketHandler.PlayBCTSound('/Resourse/Home/audio/call.wav')
                }
                if ($json[1] == 1) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'中奖信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:38px;padding-top:8px">'+$json[0]+'</div>'
                            ,btn: '关闭'
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                layer.close(index);
                            }
                        });
                    })
                    socketHandler.getMoney()
                } else if ($json[1] == 2) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'充值信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:38px;padding-top:8px">'+$json[0]+'</div>'
                            ,btn: '关闭'
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                layer.close(index);
                            }
                        });
                    })
                    socketHandler.getMoney()
                } else if ($json[1] == 3) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'提款信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:38px;padding-top:8px">'+$json[0]+'</div>'
                            ,btn: '关闭'
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                layer.close(index);
                            }
                        });
                    })
                    socketHandler.getMoney()
                } else if ($json[1] == 4) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'契约分红信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:38px;padding-top:8px">'+$json[0]+'</div>'
                            ,btn: ['查看签约','关闭全部']
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                window.location.href='/replayContract'
                            }
                            ,no: function(){
                                layer.close(index);
                            }
                        });
                    })
                }  else if ($json[1] == 5) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'契约分红信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:30px;padding-top:53px">'+$json[0]+'</div>'
                            ,btn: ['关闭全部']
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                layer.close(index);
                            }
                        });
                    })
                } else if ($json[1] == 6) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'返点调整信息'
                            ,offset: '200px' 
                            ,anim:3
                            ,time:3000
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:30px;padding-top:53px">'+$json[0]+'</div>'
                            ,shade: [0.8, '#393D49'] //不显示遮罩
                            ,end: function()
                            {
                                $uid = $('input[name=uid]').val()
                                window.location.href = '/logout';
                                $('input[name=uid]').val(" ");
                                $('input[name=path]').val(" ");
                                if ($uid != "")
                                    window.socket.emit("logout", $uid);
                                layer.close(index)
                            }
                        });
                    })
                } else if ($json[1] == 7) {
                    socketHandler.getMoney()
                }  else if ($json[1] == 10) {
                    layui.use('layer', function(){
                        layer.alert($json[0],{'closeBtn':0,'time':5000,anim:3,btnAlign: 'c',end:function(){
                            window.location.href="/logout"
                        }})
                    })
                } else if ($json[1] == 12) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'契约日工资信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:38px;padding-top:8px">'+$json[0]+'</div>'
                            ,btn: ['查看签约','关闭全部']
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                window.location.href='/dayratereplayContract'
                            }
                            ,no: function(){
                                layer.close(index);
                            }
                        });
                    })
                }  else if ($json[1] == 13) {
                    layui.use('layer', function(layer){
                        var index = layer.open({
                            type: 1
                            ,title:'契约日工资信息'
                            ,offset: 'rb' 
                            ,anim:3
                            ,area:['250px','200px']
                            ,content: '<div style="padding-left:30px;padding-top:53px">'+$json[0]+'</div>'
                            ,btn: ['关闭全部']
                            ,btnAlign: 'c' //按钮居中
                            ,shade: 0 //不显示遮罩
                            ,yes: function(){
                                layer.close(index);
                            }
                        });
                    })
                }
            })
        }
    }
    win.socketHandler = socketHandler;
})(window);

$(function(){
    socketHandler.Init()
})