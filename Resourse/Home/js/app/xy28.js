window.LOTTERYCONF = {
    "xy28": {
        selectItem:[{}],
        orderItem:[],
        addItem:[]
    }
}
var cart = {};
cart.total = 0;
cart.ActiveBetId = 0;
var e = null,
    display = $('div.lm0'),  // 显示面板容器
    games = $("#barea_tab"), // 玩法统称
    gamec = $("#game_search"),// 玩法统称下的具体玩法
    gamen = $("#game_betting"),// 号码选择区
    howtoplay = $("#howtoplay"),
    $lid = $('input[name=lid]').val(),
    prize = $("select[name=prize]"),
    cartlist = $('#cartlist'),
    r = LOTTERYCONF[$('#lottery_id').val()];// 获取玩法的配置参数
!(function(win){
    var ssc = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,lossLine: function()
        {
            $(".J__filter-lossLine").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".selectLists").stop(true).slideToggle(300);
            });
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
                ssc.LoadingAfter()
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            ssc.RefreshMoney(),ssc.getMoney(),ssc.Logout(),ssc.displayPanl(),ssc.socketEvent(),
            ssc.xySlider(),ssc.Dropdown(),ssc.NavMenu(),ssc.NoticeSlider(),ssc.lossLine(),ssc.FloatToolbar(),ssc.FloatAddToolbar()
        }
        ,FloatToolbar: function()
        {
            $(".J__floatToolbar").hover(function(){
                $(this).find(".popup__floatBox").stop(true).fadeIn(200);
            }, function(){
                $(this).find(".popup__floatBox").stop(true).hide();
            });
        }
        ,FloatAddToolbar: function()
        {
            $(".J__floatAddToolbar").hover(function(){
                $(this).find(".popup__floatBox_add").stop(true).fadeIn(200);
            }, function(){
                $(this).find(".popup__floatBox_add").stop(true).hide();
            });
        }
        ,RefreshMoney: function()
        {
            // 金额刷新
            $(".getnewmoney").bind("click",function(){
                $.post("/getMoney",function(money){
                    var arr = money.split('-')
                    $(".usermoney").html("可用余额 ￥"+arr[0]);
                    $(".wallet_account").html("分红钱包 ￥"+arr[1]);
                    $("#playaccount").text(arr[0])
                },"text")
            })
        }
        ,getMoney: function()
        {
            $.post("/getMoney",function(money){
                var arr = money.split('-')
                $(".usermoney").html("可用余额 ￥"+arr[0]);
                $(".wallet_account").html("分红钱包 ￥"+arr[1]);
                $("#playaccount").text(arr[0])
            },"text")
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
        ,xySlider: function()
        {
            // 大图轮播
            var $slider = $(".js_idxSlider"), $nav = $(".slider-nav i");
            $slider.jq_xySlider({
                effect: "fade",
                autoplay:true,
                delay: 5000,
                onEnd: function(idx){
                    $nav.removeClass("on").eq(idx).addClass("on");
                },
                navigation: $nav
            });
        }
        ,Dropdown: function()
        {
            $(".J__dropdown").hover(function(){
				$(this).addClass("active").siblings().removeClass("on");
				$(this).find(".dropdownMenu2").stop(true).slideDown(200);
			}, function(){
				$(this).removeClass("active");
				$(".J__navMenu .current").addClass("on");
				$(this).find(".dropdownMenu2").stop(true).hide();
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
        ,LotteryRecord: function()
        {
            $(".J__lotteryRecord").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".lotteryRecord-list").stop(true).slideToggle(300);
            });
        }
        ,displayPanl: function()
        {
            $.post("/DisplayBuy/timeDown",{"lid":$lid},function(data){
                display.html(tpl("#display_tpl",{rows:[data]})),ssc.runIt(data['last_number'])
                ssc.LotteryRecord()
                r.bonus = [data['bonus']]
                r.currentid = data['id']
                r.selectItem[0].lid = data['lid']
                r.selectItem[0].currentid = data['id']
                r.selectItem[0].bonusLevel = data['bonuslevel']
                var endtime = data['endtime'];
                $("#current_endtime").html(endtime);
                ssc.getBuyRecordItem()

                var stoptimeArr = data['endtime'].split(" ")
                var stopTime1 = stoptimeArr[0].split("-")
                var stopTime2 = stoptimeArr[1].split(":")
                var stop = new Date(stopTime1[0],stopTime1[1],stopTime1[2],stopTime2[0],stopTime2[1],stopTime2[2])

                var serverTimeArr = data['servertime'].split(" ")
                var serverTime1 = serverTimeArr[0].split("-")
                var serverTime2 = serverTimeArr[1].split(":")
                var ServerDate = new Date(serverTime1[0],serverTime1[1],serverTime1[2],serverTime2[0],serverTime2[1],serverTime2[2])
                var now = new Date()
                var ClientDate= new Date(now.getFullYear(),now.getMonth()+1,now.getDate(),now.getHours(),now.getMinutes(),now.getSeconds())
                // 本地时间与服务器的时间差
                var d = ClientDate.getTime()-ServerDate.getTime()
                $("input[name='d']").val(d);
                clock();
            },'json')
            
            function clock(){
                var today = new Date();
                var d = $("input[name='d']").val();
                today.setTime(today.getTime()-d);
                var nowtoday = new Date(today.getFullYear(),today.getMonth()+1,today.getDate(),today.getHours(),today.getMinutes(),today.getSeconds());
                var stoptime = $("#current_endtime").html();
                var stoptimeArr = stoptime.split(" ");
                var stopTime1 = stoptimeArr[0].split("-");
                var stopTime2 = stoptimeArr[1].split(":");
                var stop = new Date(stopTime1[0],stopTime1[1],stopTime1[2],stopTime2[0],stopTime2[1],stopTime2[2]);
                var leave=stop-nowtoday;
                if(leave>=1000){
                    var timestamp = leave/1000;
                    var second = Math.floor( timestamp % 60);
                    var minute = Math.floor((timestamp / 60)	% 60);
                    var hour   = Math.floor((timestamp / 3600)	% 24);
                    var day    = Math.floor((timestamp / 3600)	/ 24);
                    var h = ('0'+hour).slice(-2);
                    var m = ("0"+minute).slice(-2);
                    var s = ("0"+second).slice(-2);
                    $("#count_down").html('<i>'+ h+'</i><i>'+ m+'</i><i>'+ s+'</i>');
                    setTimeout(clock, 1000);
                } else {
                    $.dialog.close('*');
                    alertify.success("<div class='text'><i class='ico-success'></i>该期已结束，进入下期购买，请留意期号变化<i class='suc-close'></i></div>")
                    ssc.displayPanl();
                }
            }
        }
        ,runIt: function(num)
        {
            var numarr = num.split(',')
            for(var i=0; i<numarr.length; i++)
            {
                $("#num"+i).text(numarr[i])
            }
        }
        ,socketEvent: function()
        {
            $event="";
            switch (parseInt($lid))
            {
                 case 23:
	                $event = "bjxy28OpenCode";break;
	            case 29:
	                $event = "jndxy28OpenCode";break;
            }
            window.socket.on($event,function(data)
            {
                $json = JSON.parse(data);
                $("#lastSeries").text($json.series_number);
                ssc.runIt($json.number)

                var _recentHtml = '<li><div class="col1">'+$json.series_number+'</div><div class="col2">'
                var $numarray = $json.number.split(",")
                _recentHtml += '<i>'+$numarray[0]+'</i>';
                _recentHtml += '<i style="background:none;color:#737373">+</i>'
                _recentHtml += '<i>'+$numarray[1]+'</i>';
                _recentHtml += '<i style="background:none;color:#737373">+</i>'
                _recentHtml += '<i>'+$numarray[2]+'</i>';
                _recentHtml += '<i style="background:none;color:#737373">=</i>'
                _recentHtml += '<i>'+$numarray[3]+'</i>';
                _recentHtml += '</div></li>';
                $("#game_jwuqi").find("li:last").remove()
                $("#game_jwuqi").find("li:first").before(_recentHtml);
                ssc.getBuyRecordItem()
            })
            window.socket.on("message", function($data) {
                var $json = $data.split('-')
                if ($json[1] == 1) {
                    ssc.getBuyRecordItem()
                }
            })
        }
        ,getBuyRecordItem: function()
        {
            $.post("/buyRecordItem",{"lid":$lid},function(data){
                $("#buyrecorditem").html(tpl("#buyrecord_tpl",{rows:[data]}))
                $("a[data-method=detail]").on("click",function(){
                    $buyid = $(this).attr("data-field");
                    $.dialog.open({
                        title: '订单详情',
                        width: 800,
                        height:590,
                        btnText:["关 闭"],
                        type: 'alert'
                    }).ready(function(o) {
                        $.post('/selfRecordDetail',{"id":$buyid},function(json){
                            if(json.length==0) return false;
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
                            $item.find("td:eq(4)").html('<span style="color:red">已撤单</span>');
                            $(e.target).remove();
                            alertify.success("<div class='text'><i class='ico-success'></i>订单取消成功<i class='suc-close'></i></div>")
                            ssc.getMoney()
                        }
                        else
                        {
                            $.dialog.close("*")
                            alertify.error("<div class='text'><i class='ico-error'></i>订单取消失败<i class='err-close'></i></div>")
                        }
                        return;
                    },'text')
                })
            },'json')
        }
        ,checkSubmit: function()
        {
            if(r.selectItem[0].selectNumber=="" || r.selectItem[0].singermoney==0 || r.currentid=="" || r.selectItem[0].lid=="")
            {
                return false
            }
            else
            {
                return true
            }
        }
        ,RndNum: function(n)
        {
            var rnd = "";
            for (var i = 0; i < n; i++)
                rnd += Math.floor(Math.random() * 10);
            return rnd;
        }
        ,cleanSelectItemData: function()
        {
            r.selectItem[0].selectNumber = ""
            r.selectItem[0].singermoney = 0
            $("#playnum").find("td").removeClass('cur')
            $("#playnum").find("li").removeClass('cur')
            $("#tmoney").val(0)
        }
    }
    win.ssc = ssc;
})(window);

$(function(){
    ssc.Loading()

    // 选择号码
    $("#playnum").find("li").each(function(){
        $(this).click(function(){
            $("#playnum").find("td").removeClass('cur')
            $("#playnum").find("li").removeClass('cur')
            $(this).addClass('cur')
            r.selectItem[0].selectNumber = $(this).attr('code')
        })
    })
    $("#playnum").find("td").each(function(){
        $(this).click(function(){
            $("#playnum").find("td").removeClass('cur')
            $("#playnum").find("li").removeClass('cur')
            $(this).addClass('cur')
            r.selectItem[0].selectNumber = $(this).attr('code')
        })
    })

    // 自定义砝码
    $('button[data-method="dy"]').click(function(){
        $("#savezdy").show()
        $(".inp-sty-2").removeAttr("hidden")
        $(".inp-sty-2").removeAttr("disabled")
    })

    // 保存自定义
    $('a[data-method="savezdy"]').click(function(){
        $("#savezdy").hide()
        $(".inp-sty-2").attr("hidden","hidden")
        $(".inp-sty-2").attr("disabled","disabled")
    })

    // 输入框事件
    $(".inp-sty-2").blur(function(){
        $(this).parents("li").find("span").html($(this).val())
    })

    $(".sleitem").find("li").each(function(){
        $(this).click(function(){
            if($(".inp-sty-2").attr("hidden")==undefined) return 
            var m = Number($(this).find("span").html())
            $("#tmoney").val(Number($("#tmoney").val())+m)
            r.selectItem[0].singermoney = m
        })
    })

    // 投注金额
    $("#tmoney").blur(function(){
        if($(this).val()=="") $(this).val(0)
        $(this).val(parseInt($(this).val()))
        r.selectItem[0].singermoney = $(this).val()
    })

    // 余额显示
    $("#playaccount").html(Number($("#account").html()))

    // 刷新余额
    $(".refresh").click(function(){
        ssc.getMoney()
    })

    /* todo 验证投注条件 */
    function checkSubmit()
    {
        if(r.selectItem[0].selectNumber=="" || r.selectItem[0].singermoney==0 || r.currentid=="" || r.selectItem[0].lid=="")
        {
            return false
        }
        else
        {
            return true
        }
    }

    /* todo 快速投注 */
    $('a[data-method="quickBet"]').on("click",function(){
        if(ssc.checkSubmit())
        {
            r.selectItem[0].playname = $("input[name='lotteryName']").val()
            r.selectItem[0].singernote = 1
            r.selectItem[0].model = 0
            r.selectItem[0].multiple = 1
            r.selectItem[0].prize=""
            r.selectItem[0].position=""
            // 数据组合
            var t = new Date().getTime();
            var randkey = t + r.selectItem[0].lid + ssc.RndNum(6);
            randkey = "n*" + randkey + "*" + r.currentid + "*" + $("input[name=uid]").val();
            $selectData = r.selectItem[0].playname+':'+r.selectItem[0].selectNumber+":"+ r.selectItem[0].singernote+":1:"+r.selectItem[0].singermoney+
                ":"+ r.selectItem[0].model+":"+ r.selectItem[0].prize+":"+ r.selectItem[0].multiple+":"+ r.selectItem[0].position
            $data = "data="+$selectData+"&lottery_number_id="+r.currentid+"&is_add=0&lottery_id="+
                r.selectItem[0].lid+"&amount="+r.selectItem[0].singermoney+"&com="+randkey
                $('.quickBet').removeAttr('data-method')
            $('.quickBet').attr("disable","disable")
            $('.quickBet').html('<i class="icon-spinner icon-spin icon-1x"></i>提交中..')
            $.post("/Small",$data,function(info){
                $('.quickBet').removeAttr('disable')
                $('.quickBet').attr("data-method","quickBet")
                $('.quickBet').html('确认投注')
                if (info.status == 0 || info.status == "")
                {
                    $.dialog.close('*');
					layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        ssc.cleanSelectItemData()
                    })
                }
                else
                {
                    $.dialog.close('*');
					layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        $("#account").html(parseFloat(Number($("#account").html())-Number(r.selectItem[0].singermoney)).toFixed(4))
                        $("#playaccount").html(parseFloat(Number($("#playaccount").html())-Number(r.selectItem[0].singermoney)).toFixed(4))
                        ssc.cleanSelectItemData()
                        setTimeout(ssc.getBuyRecordItem,2000)
                    })
                }
            },'json')
        }
        else
        {
            return false;
        }

    })
})