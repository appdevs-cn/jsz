window.LOTTERYCONF = {
    "k3": {
        selectItem:[{}],
        orderItem:[],
        addItem:[],
        tabs: [{
            name: "和值",
            id: "hz"
        }, {
            name: "三同号",
            id: "3th"
        }, {
            name: "二同号",
            id: "2th"
        }, {
            name: "不同号",
            id: "bth"
        }, {
            name: "三连号",
            id: "3lh"
        }],
        search: {
            "hz": [{
                name: "和值",
                games: [{
                    id: "he",
                    alias:"和值",
                    name: "和(3号码相加)"
                }, {
                    id: "da",
                    alias:"和值大",
                    name: "大(11-18)"
                }, {
                    id: "xiao",
                    alias:"和值小",
                    name: "小(3-10)"
                }, {
                    id: "dan",
                    alias:"和值单",
                    name: "单(和为单数)"
                }, {
                    id: "shuang",
                    alias:"和值双",
                    name: "双(和为双数)"
                }]
            }],
            "3th": [{
                name: "三同号",
                games: [{
                    id: "3thtx",
                    alias:"三同号通选",
                    name: "通选"
                }, {
                    id: "3thdx",
                    alias:"三同号单选",
                    name: "单选"
                }]
            }],
            "2th": [{
                name: "二同号",
                games: [{
                    id: "2thfx",
                    alias:"二同号复选",
                    name: "复选"
                },{
                    id: "2thdx",
                    alias:"二同号单选",
                    name: "单选"
                }]
            }],
            bth: [{
                name: "不同号",
                games: [{
                    id: "1bth",
                    alias:"一不同号",
                    name: "一不同号"
                },{
                    id: "2bth",
                    alias:"二不同号",
                    name: "二不同号"
                },{
                    id: "3bth",
                    alias:"三不同号",
                    name: "三不同号"
                }]
            }],
            "3lh": [{
                name: "三连号",
                games: [{
                    id: "3lhtx",
                    alias:"三连号通选",
                    name: "通选"
                },{
                    id: "3lhdx",
                    alias:"三连号单选",
                    name: "单选"
                }]
            }]
        },
        games: {
            "he": {
                describe: {
                    title: "游戏玩法：3-18号码中任选一个或者多个。",
                    content: "投注方案：和值6<br />是指对三个号码的和值进行投注，包括“和值3”至“和值18”投注"
                },
                items: [{
                    title: "三数之和",
                    nums: ["3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13", "14", "15", "16", "17", "18"]
                }]
            },
            "da": {
                describe: {
                    title: "游戏玩法：是指对三个号码的和值进行投注，和值为11-18即中3.86元。",
                    content: "投注方案：和值19<br />是指对三个号码的和值进行投注，和值为11-18即中3.86元。"
                },
                items: [{
                    title: "和值大",
                    nums: ["和值大"]
                }]
            },
            "xiao": {
                describe: {
                    title: "游戏玩法：是指对三个号码的和值进行投注，和值为3-10即中3.86元。",
                    content: "投注方案：和值10<br />是指对三个号码的和值进行投注，和值为3-10即中3.86元。"
                },
                items: [{
                    title: "和值小",
                    nums: ["和值小"]
                }]
            },
            "dan": {
                describe: {
                    title: "游戏玩法：是指对三个号码的和值进行投注，和值为单数即中3.86元。",
                    content: "投注方案：和值9<br />是指对三个号码的和值进行投注，和值为单数即中3.86元。"
                },
                items: [{
                    title: "和值单",
                    nums: ["和值单"]
                }]
            },
            "shuang": {
                describe: {
                    title: "游戏玩法：是指对三个号码的和值进行投注，和值为双数即中3.86元。",
                    content: "投注方案：和值10<br />是指对三个号码的和值进行投注，和值为双数即中3.86元。"
                },
                items: [{
                    title: "和值双",
                    nums: ["和值双"]
                }]
            },
            "3thtx": {
                describe: {
                    title: "游戏玩法：是指对所有相同的三个号码（即全包豹子）进行投注。",
                    content: "是指对所有相同的三个号码（即全包豹子）进行投注。"
                },
                items: [{
                    title: "通选",
                    nums: ["三同号通选"]
                }]
            },
            "3thdx": {
                describe: {
                    title: "是指从所有相同的三个号码（111、222、…、666）中任意选择一组进行投注。",
                    content: "投注方案:111<br/>是指从所有相同的三个号码（111、222、…、666）中任意选择一组进行投注。"
                },
                items: [{
                    title: "单选",
                    nums: ["111","222","333","444","555","666"]
                }]
            },
            "2thfx": {
                describe: {
                    title: " 是指对三个号码中两个指定的相同号码和一个任意号码进行投注。",
                    content: "投注方案:11*<br/> 是指对三个号码中两个指定的相同号码和一个任意号码进行投注。"
                },
                items: [{
                    title: "二同号",
                    nums: ["11-","22-","33-","44-","55-","66-"]
                }]
            },
            "2thdx": {
                describe: {
                    title: "是指对三个号码中两个指定的相同号码和一个指定的不同号码进行投注。",
                    content: "投注方案:11,2<br/>是指对三个号码中两个指定的相同号码和一个指定的不同号码进行投注。"
                },
                items: [{
                    title: "同号",
                    nums: ["11","22","33","44","55","66"]
                },{
                    title: "不同号",
                    nums: ["1","2","3","4","5","6"]
                }]
            },
            "1bth": {
                describe: {
                    title: "至少选择1个号码投注，选号至少一个与开奖号码一致,另外2个号码相同即中奖。",
                    content: "投注方案:1<br/>至少选择1个号码投注，选号至少一个与开奖号码一致,另外2个号码相同即中奖。"
                },
                items: [{
                    title: "不同号",
                    nums: ["1","2","3","4","5","6"]
                }]
            },
            "2bth": {
                describe: {
                    title: "至少选择2个号码投注，选号各有一个与开奖号码一致即中奖。",
                    content: "投注方案:1<br/>至少选择2个号码投注，选号各有一个与开奖号码一致即中奖。"
                },
                items: [{
                    title: "二不同号",
                    nums: ["1","2","3","4","5","6"]
                }]
            },
            "3bth": {
                describe: {
                    title: " 至少选择3个号码投注，选号各有一个与开奖号码一致即中奖。",
                    content: "投注方案:1<br/> 至少选择3个号码投注，选号各有一个与开奖号码一致即中奖。"
                },
                items: [{
                    title: "三不同号",
                    nums: ["1","2","3","4","5","6"]
                }]
            },
            "3lhtx": {
                describe: {
                    title: "游戏玩法：是指对所有三个相连的号码（仅限：123、234、345、456）进行投注。",
                    content: "是指对所有三个相连的号码（仅限：123、234、345、456）进行投注。"
                },
                items: [{
                    title: "三连号",
                    nums: ["三连号通选"]
                }]
            },
            "3lhdx": {
                describe: {
                    title: "游戏玩法：是指对所有三个相连的号码（仅限：123、234、345、456）中任意选择一组进行投注。",
                    content: "投注方案:1<br/>是指对所有三个相连的号码（仅限：123、234、345、456）中任意选择一组进行投注。"
                },
                items: [{
                    title: "三连号",
                    nums: ["123", "234", "345", "456"]
                }]
            }
        }
    }
}

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
        ,LotteryRecord: function()
        {
            $(".J__lotteryRecord").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".lotteryRecord-list").stop(true).slideToggle(300);
            });
        }
        ,PopHelp: function()
        {
            $(".J__popHelp").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".cont").stop(true).slideToggle(300);
            });
        }
        ,SelUnit: function()
        {
            $("#J__selUnit i").on("click", function(){
                $(this).addClass("on").siblings().removeClass("on");
                mode = $(this).attr("data-field");
                $singermoneyval = $("#singermoney")
                $singernoteval = parseInt($("#singernote").text())
                $multipleval = parseInt($('input[name=multiple]').val())
                switch (parseInt(mode))
                {
                    case 0:
                        $modelCurrentMoney = $singernoteval*$multipleval*2
                        break;
                    case 1:
                        $modelCurrentMoney = $singernoteval*$multipleval*2/10
                        break;
                    case 2:
                        $modelCurrentMoney = $singernoteval*$multipleval*2/100
                        break;
                    case 3:
                        $modelCurrentMoney = $singernoteval*$multipleval*2/1000
                        break;
                }
                $singermoneyval.text($modelCurrentMoney)
                r.selectItem[0].model = mode
                r.selectItem[0].singermoney = $modelCurrentMoney
            });
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.JszContent',function(){
                ssc.LoadingAfter()
                /*todo 初始点击事件*/
                games.on("click", "a", function(c) {
                    var n = $(c.target);
                    n.hasClass("on") || (e = n.attr("id"), $(".on", games).removeClass("on"), n.addClass("on"), ssc.gamea({
                        search: r.search[e],
                        on: r.search[e][0].games[0].id
                    }), ssc.gamet({
                        games: r.games[r.search[e][0].games[0].id]
                    }), ssc.gamem({
                        bonus: r.bonus,
                        bonuslevel: r.selectItem[0].bonusLevel
                    },r.search[e][0]['games'][0]['alias']),ssc.initSetDefaultOption(e))
                }), $("#game_search").on("click", "a", function(s) {
                    ssc.gamea({
                        search: r.search[e],
                        on: s.target.id
                    }), ssc.gamet({
                        games: r.games[s.target.id]
                    }), ssc.gamem({
                        bonus: r.bonus,
                        bonuslevel: r.selectItem[0].bonusLevel
                    },$(s.target).attr('data-field')), ssc.init({
                        "playname":$(s.target).attr('data-field')
                        ,"showplayname":$(s.target).text()
                    })
                }),games.html(tpl("#tabs_tpl", r)).find("a:eq(0)").trigger("click")

                $('#game_betting').on('click', '.lottery-ball', function(event) {
                    var $target = $(event.target)
                    $target[$target.hasClass('on') ? 'removeClass' : 'addClass']('on')
                    ssc.calculateMultipleNote()
                    event.stopPropagation()
                }).on('click', '.lottery-ball-other', function(e) {
                    var $target = $(e.target)
                    var $balls = $target.closest('li').find('.lottery-ball')
                    var max = Math.round($balls.length / 2)
                    switch ($target.attr('data-fn')) {
                        case '1':
                            $balls.addClass('on')
                            break
                        case '2':
                            $.each($balls, function(i, d) {
                                $(d)[i > max - 1 ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '3':
                            $.each($balls, function(i, d) {
                                $(d)[i < max ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '4':
                            $.each($balls, function(i, d) {
                                $(d)[parseInt($(d).text()) % 2 > 0 ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '5':
                            $.each($balls, function(i, d) {
                                $(d)[parseInt($(d).text()) % 2 < 1 ? 'addClass' : 'removeClass']('on')
                            })
                            break
                        case '6':
                            $balls.removeClass('on')
                            break
                    }
                    ssc.calculateMultipleNote()
                })

                /*todo 和值 三同号单选 二同号复选 三连号单选*/
                window.hz = function()
                {
                    $select = window.select
                    $count = $select[0]
                    a=b=c=1
                    if($count==0) return 0
                    for(var i=1; i<=$count;i++){
                        a *= i;
                    }
                    for(var j=($count-1);j>=1;j--){
                        b *=j;
                    }
                    return a/(b*1)
                }

                /*todo 和值大 小 单 双 三同号通选 三连号通选*/
                window.al1 = function()
                {
                    $select = window.select
                    $count = $select[0]
                    if($count==0) return 0
                    return $count
                }

                /*todo 一不同号*/
                window.bth1 = function()
                {
                    $select = window.select
                    $count = $select[0]
                    a=b=c=1
                    $num = 1
                    if($count==0 || $count<$num) return 0
                    for(var i=1; i<=$count;i++){
                        a *= i;
                    }
                    for(var j=($count-$num);j>=1;j--){
                        b *=j;
                    }
                    for(var k=$num; k>1; k--){
                        c *=k;
                    }
                    return a/(b*c)
                }

                /*todo 二不同号*/
                window.bth2 = function()
                {
                    $select = window.select
                    $count = $select[0]
                    a=b=c=1
                    $num = 2
                    if($count==0 || $count<$num) return 0
                    for(var i=1; i<=$count;i++){
                        a *= i;
                    }
                    for(var j=($count-$num);j>=1;j--){
                        b *=j;
                    }
                    for(var k=$num; k>1; k--){
                        c *=k;
                    }
                    return a/(b*c)
                }

                /*todo 三不同号*/
                window.bth3 = function()
                {
                    $select = window.select
                    $count = $select[0]
                    a=b=c=1
                    $num = 3
                    if($count==0 || $count<$num) return 0
                    for(var i=1; i<=$count;i++){
                        a *= i;
                    }
                    for(var j=($count-$num);j>=1;j--){
                        b *=j;
                    }
                    for(var k=$num; k>1; k--){
                        c *=k;
                    }
                    return a/(b*c)
                }

                /*todo 二同号单选*/
                window.t2dx = function()
                {
                    $select = window.select;
                    $tonghao = $select[0]
                    $butonghao = $select[1]
                    $selectRowsNumber = window.selectRowsNumber
                    $rowsNumber = $selectRowsNumber.split(",")
                    $c2arr = $rowsNumber[0].split(" ")
                    for(var o in $c2arr)
                    {
                        $c2arrunique = $c2arr[o].charAt(0)
                        if(jQuery.inArray($c2arrunique,$rowsNumber[1])!=-1)
                        {
                            $("#J__selNums li:eq(0)").find(".lottery-ball[data-val="+$c2arr[o]+"]").removeClass('on')
                        }
                    }
                    if($tonghao==0 || $butonghao==0) return 0
                    return $tonghao*$butonghao
                }
                /*todo 追号计划*/
                $('a[data-method="chase"]').on('click',function(e) {
                    r.addItem = []
                    $num = $("li",cartlist).length
                    if(r.orderItem.length==0 || $num==0) return
                    if(r.orderItem.length>1 && $num>1)
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>只能对单一订单追号<i class='err-close'></i></div>")
                    }
                    else
                    {
                        $.post("/futureLottery",{"id":$lid},function(data){
                            if(data.length>0)
                            {
                                $.dialog.util.alert({
                                    title: "追号",
                                    width: 800,
                                    height:500,
                                    btnText:['追号购买'],
                                    height: "auto"
                                }).ready(function(t) {
                                    t.html(tpl("#chase_tpl",{rows:[data]}))
                                    $('.chase_list').perfectScrollbar({
                                        height: 300
                                    })
                                    ssc.addperiodsChange()
                                    $('button[data-method="createAddList"]').bind("click",function(){
                                        type=""
                                        $("#menu0").find("li").each(function(){
                                            if($(this).hasClass("hover"))
                                            {
                                                type = $(this).attr("aria-controls")
                                            }
                                        })
                                        if(type=="t1")
                                            ssc.createWithTimes(type)
                                        else if(type=="t2")
                                            ssc.createDouble(type)
                                        else if(type=="t3")
                                            ssc.createProfit(type)
                                        ssc.calculateAddListItem()
                                    })
                                    $('button[data-method="addBuy"]').on("click", function(){
                                        ssc.addOrderSubmit()
                                        r.addItem=[]
                                        $.dialog.close("*")
                                    })
                                }).close(function(){
                                    r.addItem=[]
                                    $.dialog.close("*")
                                    return;
                                })
                            }
                        },'json')
                    }
                })
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            ssc.RefreshMoney(),ssc.Logout(),ssc.displayPanl(),ssc.socketEvent(),ssc.SelUnit()
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
                },"text")
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
        ,PlaySoundHandler: function()
        {
            ssc.PlaySound('/Resourse/Home/Sound/kai.wav')
            document.getElementById("play1").addEventListener("ended", function(){document.getElementById("play2").play()});
            document.getElementById("play2").addEventListener("ended", function(){document.getElementById("play3").play()}); 
            document.getElementById("play3").addEventListener("ended", function(){document.getElementById("play4").play()});
        }
        ,PlaySound: function(src)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV').html('<embed id="play1" src="'+src+'"/>'); 
            }
            else
            {
                $('#newMessageDIV').html('<audio id="play1" autoplay="autoplay"><source src="'+src+'"'+ 'type="audio/wav"/></audio>');
            }
        }
        ,PlaySound1: function(n)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV0').html('<embed id="play2" src="/Resourse/Home/Sound/0.wav"/>'); 
            }
            else
            {
                $('#newMessageDIV0').html('<audio id="play2"><source src="/Resourse/Home/Sound/'+n+'.wav" type="audio/wav"/></audio>');
            }
        }
        ,PlaySound2: function(n)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV1').html('<embed id="play3" src="/Resourse/Home/Sound/0.wav"/>'); 
            }
            else
            {
                $('#newMessageDIV1').html('<audio id="play3"><source src="/Resourse/Home/Sound/'+n+'.wav" type="audio/wav"/></audio>');
            }
        }
        ,PlaySound3: function(n)
        {
            if("\v"=="v")
            { 
                $('#newMessageDIV2').html('<embed id="play4" src="/Resourse/Home/Sound/0.wav"/>'); 
            }
            else
            {
                $('#newMessageDIV2').html('<audio id="play4"><source src="/Resourse/Home/Sound/'+n+'.wav" type="audio/wav"/></audio>');
            }
        }
        ,displayPanl: function()
        {
            $.post("/DisplayBuy/timeDown",{"lid":$lid},function(data){
                display.html(tpl("#display_tpl",{rows:[data]})),ssc.runIt(data['last_number'])
                /* todo 显示最近5期开奖 */
                ssc.LotteryRecord()
                r.bonus = [data['bonus']]
                r.currentid = data['id']
                r.selectItem[0].lid = data['lid']
                r.selectItem[0].currentid = data['id']
                r.selectItem[0].bonusLevel = data['bonuslevel']
                var endtime = data['endtime'];
                $("#current_endtime").html(endtime);
                $("#bottomSearis").html(data.series_number)
                ssc.setSelectItemLowPrize()
                ssc.getBuyRecordItem()
                ssc.getBuyAddRecordItem()
                ssc.gamem({bonus: r.bonus,bonuslevel: r.selectItem[0].bonusLevel},r.selectItem[0].playname)

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
                    $("#bottomTime").html(h+":"+m+":"+s)
                    setTimeout(clock, 1000);
                } else {
                    if($("li",cartlist).length>1)
                    {
                        $.dialog.close('*')
                        layui.use('layer', function(layer){
                            var index = layer.confirm('购物车还有未提交的数据,是否清除？', {
                                btn: ['确定清除','保留购买'] 
                                ,anim:3
                                ,btnAlign: 'c'
                                }, function(){
                                    ssc.cleanCartlist()
                                    layer.close(index)
                                });
                        })
                    }
                    else
                    {
                        $.dialog.close('*');
                        alertify.success("<div class='text'><i class='ico-success'></i>该期已结束，进入下期购买，请留意期号变化<i class='suc-close'></i></div>")
                    }
                    ssc.delAddperiodsItem()
                    ssc.displayPanl();
                }
            }
        }
        ,runIt: function(num)
        {
            for(var i=0; i<num.length; i++)
            {
                $("#num"+i).text(num[i])
            }
        }
        ,socketEvent: function()
        {
            $event="";
            switch (parseInt($lid))
            {
                case 15:
                    $event = "jsk3OpenCode";break;
                case 16:
                    $event = "jlk3OpenCode";break;
            }
            window.socket.on($event,function(data)
            {
                $json = JSON.parse(data);
                $("#lastSeries").text($json.series_number);
                ssc.runIt($json.number)
                var _recentHtml = '<tr><td>'+$json.series_number+'</td><td><div class="number">'
                for(var j=0; j<$json.number.length; j++)
                {
                    _recentHtml += '<i>'+$json.number[j]+'</i>';
                }
                _recentHtml += '</div></td></tr>';
                $("#game_jwuqi>tbody").find("tr:last").remove()
                $("#game_jwuqi>tbody").find("tr:first").before(_recentHtml);
                ssc.getBuyRecordItem()
                ssc.getBuyAddRecordItem()
            })
            window.socket.on("message", function($data) {
                var $json = $data.split('-')
                if ($json[1] == 1) {
                    ssc.getBuyRecordItem()
                    ssc.getBuyAddRecordItem()
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
        ,getBuyAddRecordItem: function()
        {
            $.post("/buyAddRecordItem",{"lid":$lid},function(data){
                $("#buyrecordadditem").html(tpl("#buyaddrecord_tpl",{rows:[data]}))
                $("a[data-method=adddetail]").on("click",function(){
                    $buyid = $(this).attr("data-field");
                    $.dialog.open({
                        title: '追号订单',
                        width: 900,
                        height:580,
                        btnText:["关 闭"],
                        type: 'alert'
                    }).ready(function(o) {
                        $.post('/selfaddRecordDetail',{"id":$buyid},function(json){
                            if(json.length==0) return false;
                            o.html(tpl('#detail_add_record',{rows:[json]}))
                        },'json')
                    })
                })
                $("a[data-method='list']").on("click",function(){
                    $buyid = $(this).attr("data-field");
                    $.dialog.open({
                        title: '追号列表',
                        width: 1000,
                        height:450,
                        btnText:["全部撤单","关闭"],
                        type: 'confirm'
                    }).ready(function(o) {
                        var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                        $.post('/addRecordList',{"id":$buyid},function(json){
                            if(json.length==0) return false;
                            o.html(tpl('#record_list',{rows:[json]}))
                            ssc.TrHover()
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
                                        setTimeout(ssc.getMoney,2000)
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
                                            alertify.success("<div class='text'><i class='ico-success'></i>追号订单批量撤单处理中<i class='suc-close'></i></div>")
                                            setTimeout(ssc.getMoney,10000)
                                        }
                                        else
                                        {
                                            $.dialog.close("*")
                                            alertify.error("<div class='text'><i class='ico-error'></i>追号订单批量撤单失败<i class='err-close'></i></div>")
                                        }
                                    },'text')
                                    $.dialog.close('*');
                                    return false;
                                }
                            })
                        },'json')
                    })
                })
            },'json')
        }
        ,gamea: function(a)
        {
            gamec.html(tpl("#search_tpl", a))
        }
        ,gamet: function(a)
        {
            gamen.html(tpl("#bet_tpl", a))
            howtoplay.html(tpl("#howtoplay_tpl", a))
            ssc.PopHelp()
        }
        ,gamem: function(a,n)
        {
            $n = String(n);
            if(typeof(a['bonus'])=="undefined") return;
            if($n!="和值")
            {
                var _html = '<option value="1">奖金'+ a['bonus'][0]['common'][$n]+'-'+a['bonuslevel']+'%</option>';
            }
            else
            {
                var _html = '<option value="1">动态奖金'+'-'+a['bonuslevel']+'%</option>';
            }
            prize.html(_html);
        }
        ,cleanCartlist: function()
        {
            $("li",cartlist).remove()
            ssc.calculateCartlist();
        }
        ,addCartlist: function()
        {
            if(ssc.isInsite())
            {
                order = ssc.setOrderTempData()
                id = r.orderItem.length
                selnum = r.selectItem[0].selectNumber
                switch(parseInt(r.selectItem[0].model))
                {
                    case 0:
                        $model = '元模式';break;
                    case 1:
                        $model = '角模式';break;
                    case 2:
                        $model = '分模式';break;
                    case 3:
                        $model = '厘模式';break;
                }

                showselnum = (selnum.length>10) ? (selnum.substr(0,50)+'...') : selnum
                var _html = '<li>'
                _html += '<em>'+r.selectItem[0].play+'_'+r.selectItem[0].showplayname+'</em>'
                _html += '<em>'+r.selectItem[0].multiple+'倍</em>'
                _html += '<em>'+$model+'</em>'
                _html += '<em>'+showselnum+'</em>'
                _html += '<em>'+r.selectItem[0].singermoney+'</em>'
                _html += '<em><a href="javascript:;" data-field="'+id+'" title="删除订单" data-method="delete"><img src="/Resourse/Home/images/cp/icon_del-num.png" /></a></em></li>'
                $("#cartlist").append(_html)
                r.orderItem.push(order)
                ssc.cleanSelectItemData()
                ssc.calculateCartlist()
            }
            else
            {
                return false;
            }
        }
        ,isInsite: function()
        {
            if(r.selectItem[0].selectNumber=="" || r.selectItem[0].singermoney==0 || r.selectItem[0].singernote==0)
            {
                return false
            }
            else
            {
                return true
            }
        }
        ,setOrderTempData: function()
        {
            order = new Object()
            order.bonusLevel = r.selectItem[0].bonusLevel
            order.currentid = r.selectItem[0].currentid
            order.lid = r.selectItem[0].lid
            order.model = r.selectItem[0].model
            order.multiple = r.selectItem[0].multiple
            order.playname = r.selectItem[0].playname
            order.position = r.selectItem[0].position
            order.prize = r.selectItem[0].prize
            order.selectNumber = r.selectItem[0].selectNumber
            order.singermoney = r.selectItem[0].singermoney
            order.singernote = r.selectItem[0].singernote
            return order
        }
        ,cleanSelectItemData: function()
        {
            r.selectItem[0].selectNumber=""
            r.selectItem[0].singermoney=0
            r.selectItem[0].singernote=0
        }
        ,calculateCartlist: function()
        {
            var orderCount = 0;
            var orderMoney = 0;
            $("li",cartlist).each(function(i){
                orderCount = i+1;
                $m = $("em:eq(4)",this).text();
                orderMoney = orderMoney + parseFloat($m);
            })
            $("#orderCount").text(orderCount);
            $("#orderMoney").text(orderMoney);
            ssc.addCartlistAfterHandler()
        }
        ,addCartlistAfterHandler: function()
        {
            $n = r.selectItem[0]['playname']
            $("#J__selNums li").find(".lottery-ball").removeClass("on")
            $("#singernote").text(0)
            $("#singermoney").text(0)
        }
        ,calculateCommonUnitary: function(note)
        {
            var multiple = $("input[name=multiple]").val();
            var m;
            $("#J__selUnit").find('i').each(function(){
                if($(this).hasClass('on'))
                    m = $(this).attr('data-field')
            })
            switch (parseInt(m))
            {
                case 0:
                    $currentmoney = note*multiple*2
                    break
                case 1:
                    $currentmoney = note*multiple*2/10
                    break
                case 2:
                    $currentmoney = note*multiple*2/100
                    break
                case 3:
                    $currentmoney = note*multiple*2/1000
                    break
            }
            $("#singernote").text(note)
            $("#singermoney").text($currentmoney)
            r.selectItem[0].model = m
            r.selectItem[0].multiple = multiple
            r.selectItem[0].singernote = note
            r.selectItem[0].singermoney = $currentmoney
        }
        ,setSelectItemLowPrize: function()
        {
            playname = r.selectItem[0].playname
            r.selectItem[0].prize = ""
        }
        ,mutiplechange: function()
        {
            var $mode;
            $singermoneyval = $("#singermoney")
            $("#J__selUnit").find('i').each(function(){
                if($(this).hasClass('on'))
                    $mode = $(this).attr('data-field')
            })
            $singernoteval = parseInt($("#singernote").text())
            $multipleval = parseInt($("input[name=multiple]").val())
            switch (parseInt($mode))
            {
                case 0:
                    $mulCurrentMoney = $singernoteval*$multipleval*2
                    break;
                case 1:
                    $mulCurrentMoney = $singernoteval*$multipleval*2/10
                    break;
                case 2:
                    $mulCurrentMoney = $singernoteval*$multipleval*2/100
                    break;
                case 3:
                    $mulCurrentMoney = $singernoteval*$multipleval*2/1000
                    break;
            }
            $singermoneyval.text($mulCurrentMoney)
            r.selectItem[0].multiple = $multipleval
            r.selectItem[0].singermoney = $mulCurrentMoney
        }
        ,init: function(data)
        {
            r.selectItem[0].playname = data.playname
            r.selectItem[0].showplayname = data.showplayname.replace(/(^\s*)|(\s*$)/g,"")
            r.selectItem[0].multiple = 1
            r.selectItem[0].model = 0
            r.selectItem[0].singernote = 0
            r.selectItem[0].singermoney = 0
            r.selectItem[0].selectNumber = ""
            if(typeof(r.bonus) != "undefined" )
            {
                ssc.setSelectItemLowPrize()
            }
            $("#singernote").text(0)
            $("#singermoney").text(0)
            r.addItem=[]
        }
        ,initSetDefaultOption: function($id)
        {
            r.selectItem[0].position = ""
            $tabs = r.tabs
            $name = ""
            $tabs.forEach(function($item){
                if($item.id==$id)
                {
                    $name = $item.name
                }
            })
            r.selectItem[0].play = $name
            ssc.init({"playname": r.search[$id][0].games[0].alias,"showplayname":r.search[$id][0].games[0].name})
        }
        ,getSeparator: function()
        {
            $playname = r.selectItem[0].playname
            if($playname!="二同号单选")
                return ","
            else
                return " "
        }
        ,selectNumbe: function()
        {
            n = $('li','#J__selNums').length
            $select = new Array()
            $play = r.selectItem[0].play
            $playname = r.selectItem[0].playname

            // 返回分隔符
            $separator = ssc.getSeparator()
            $selectNumber = ''
            for(i=1; i<=n; i++)
            {
                $num = ""
                $("#J__selNums li:eq("+(i-1)+")").find(".lottery-ball").each(function(){
                    if($(this).hasClass("on"))
                    {
                        $num += $(this).attr("data-val")+$separator
                    }
                })
                if($num!="")
                {
                    $num = $num.substring(0,$num.length-1)
                    $selectNumber += $num + ","
                }
            }
            $selectNumber = $selectNumber.substr(0,$selectNumber.length-1)
            r.selectItem[0].selectNumber = $selectNumber
            for(i=1; i<=n; i++)
            {
                $temp = new Array();
                $count = $("#J__selNums li:eq("+(i-1)+")").find(".on").length
                $select.push($count)
            }
            window.select = $select;
            window.selectRowsNumber = $selectNumber
        }
        ,calculateMultipleNote: function()
        {
            ssc.selectNumbe();
            p = $('.selected',"#game_search").attr("data-field");
            $referenceFunc = new Object()
            $referenceFunc = '{' +
                '"和值":"hz",' +
                '"和值大":"al1",' +
                '"和值小":"al1",' +
                '"和值单":"al1",' +
                '"和值双":"al1",' +
                '"三同号通选":"al1",' +
                '"三同号单选":"hz",' +
                '"二同号复选":"hz",' +
                '"二同号单选":"t2dx",' +
                '"一不同号":"bth1",' +
                '"二不同号":"bth2",' +
                '"三不同号":"bth3",' +
                '"三连号通选":"al1",' +
                '"三连号单选":"hz"}'
            $referenceArrFunc = JSON.parse($referenceFunc)
            $note = ssc.setCalculateFuncHandler($referenceArrFunc[p])
            ssc.calculateCommonUnitary($note)
        }
        ,setCalculateFuncHandler: function(func)
        {
            return window[func]()
        }
        ,RndNum: function(n)
        {
            var rnd="";
            for(var i=0;i<n;i++)
                rnd+=Math.floor(Math.random()*10);
            return rnd;
        }
        ,checkSubmit: function()
        {
            if(r.selectItem[0].playname=="" || r.selectItem[0].selectNumber=="" || r.selectItem[0].singernote==0
                || r.selectItem[0].singermoney==0 || r.selectItem[0].model=="" || parseInt(r.selectItem[0].multiple)<1
                || r.currentid=="" || r.selectItem[0].lid=="" || r.selectItem[0].singermoney==0)
            {
                return false
            }
                else
            {
                return true
            }
        }
        ,delAddperiodsItem: function()
        {
            $(".t1_addItemList").find("tr:gt(0)").each(function(){
                if(parseInt($(this).attr("id")) == r.selectItem[0].currentid)
                {
                    $(this).remove()
                }
            })
            $o = $('select[name=addPeriods]').find("option:eq(5)").val()
            $('select[name=addPeriods]').find("option:eq(5)").val(parseInt($o)-1)
            $(".totalPeriods").text(parseInt($o)-1)
            ssc.calculateAddListItem()
        }
        ,addperiodsChange: function()
        {
            $("select[name='addPeriods']").on("change",function(){
                type=""
                $("#menu0").find("li").each(function(){
                    if($(this).hasClass("hover"))
                    {
                        type = $(this).attr("aria-controls")
                    }
                })
                $i = $(this).val()
                $("input[name="+type+"_addPeriodsInput]").val($i)
            })
        }
        ,createWithTimes: function(type)
        {
            $addPeriodsInput = parseInt($('input[name='+type+'_addPeriodsInput]').val())
            $startBate = parseInt($('input[name='+type+'_startBate]').val())
            $("."+type+"_addItemList").find("tr:gt(0)").each(function(i){
                if(i<$addPeriodsInput)
                {
                    $(this).find("td:eq(0)").find("input").removeAttr("disabled")
                    $(this).find("td:eq(2)").find("input").removeAttr("disabled")
                    $singernoteval = parseInt(r.orderItem[0].singernote)
                    switch (parseInt(r.orderItem[0].model))
                    {
                        case 0:
                            $m = $startBate*$singernoteval*2
                            break;
                        case 1:
                            $m = $startBate*$singernoteval*2/10
                            break;
                        case 2:
                            $m = $startBate*$singernoteval*2/100
                            break;
                        case 3:
                            $m = $startBate*$singernoteval*2/1000
                            break;
                    }
                    $(this).find("td:eq(0)").find("input").prop("checked",true)
                    $(this).find("td:eq(2)").find('input').val($startBate)
                    $(this).find("td:eq(3)").text($m)

                    $(this).find("td:eq(2)").find("input").on("blur",function(e){
                        var $item = $(e.target).closest('tr')
                        $singernoteval = parseInt(r.orderItem[0].singernote)
                        $chidldstartBate = $(this).val()
                        switch (parseInt(r.orderItem[0].model))
                        {
                            case 0:
                                $cm = $chidldstartBate*$singernoteval*2
                                break;
                            case 1:
                                $cm = $chidldstartBate*$singernoteval*2/10
                                break;
                            case 2:
                                $cm = $chidldstartBate*$singernoteval*2/100
                                break;
                            case 3:
                                $cm = $chidldstartBate*$singernoteval*2/1000
                                break;
                        }
                        $item.find("td:eq(3)").text($cm)
                        ssc.calculateAddListItem()
                    })
                    $(this).find("td:eq(0)").find("input").on('click',function(){
                        ssc.calculateAddListItem()
                    })
                }
                else
                {
                    $(this).find("td:eq(0)").find("input").attr("disabled","disabled")
                    $(this).find("td:eq(0)").find("input").prop("checked",false)
                    $(this).find("td:eq(2)").find("input").attr("disabled","disabled")
                    $(this).find("td:eq(2)").find("input").val(1)
                }
            })
        }
        ,createDouble: function(type)
        {
            $addPeriodsInput = parseInt($('input[name='+type+'_addPeriodsInput]').val())
            $startBate = parseInt($('input[name='+type+'_startBate]').val())
            $("."+type+"_addItemList").find("tr:gt(0)").each(function(i){
                if(i<$addPeriodsInput)
                {
                    $(this).find("td:eq(0)").find("input").removeAttr("disabled")
                    $(this).find("td:eq(2)").find("input").removeAttr("disabled")
                    j = i-1;
                    if(j<0)
                    {
                        $doublebatechild = 1;
                        $(this).find("td:eq(2)").find("input").val($doublebatechild);
                    }
                    else
                    {
                        var n = $("."+type+"_addItemList").find("tr:eq("+(j+1)+")").find("td:eq(2)").find("input").val()
                        $doublebatechild = $startBate*n
                        $(this).find("td:eq(2)").find("input").val($doublebatechild);
                    }
                    $doublebate = parseInt($(this).find("td:eq(2)").find("input").val())
                    $singernoteval = parseInt(r.orderItem[0].singernote)
                    switch (parseInt(r.orderItem[0].model))
                    {
                        case 0:
                            $m = $doublebate*$singernoteval*2
                            break;
                        case 1:
                            $m = $doublebate*$singernoteval*2/10
                            break;
                        case 2:
                            $m = $doublebate*$singernoteval*2/100
                            break;
                        case 3:
                            $m = $doublebate*$singernoteval*2/1000
                            break;
                    }
                    $(this).find("td:eq(0)").find("input").prop("checked",true)
                    $(this).find("td:eq(3)").text($m)

                    $(this).find("td:eq(2)").find("input").on("blur",function(e){
                        var $item = $(e.target).closest('tr')
                        $singernoteval = parseInt(r.orderItem[0].singernote)
                        $chidldstartBate = $(this).val()
                        switch (parseInt(r.orderItem[0].model))
                        {
                            case 0:
                                $cm = $chidldstartBate*$singernoteval*2
                                break;
                            case 1:
                                $cm = $chidldstartBate*$singernoteval*2/10
                                break;
                            case 2:
                                $cm = $chidldstartBate*$singernoteval*2/100
                                break;
                            case 3:
                                $cm = $chidldstartBate*$singernoteval*2/1000
                                break;
                        }
                        $item.find("td:eq(3)").text($cm)
                        ssc.calculateAddListItem()
                    })
                    $(this).find("td:eq(0)").find("input").on('click',function(){
                        ssc.calculateAddListItem()
                    })
                }
                else
                {
                    $(this).find("td:eq(0)").find("input").attr("disabled","disabled")
                    $(this).find("td:eq(0)").find("input").prop("checked",false)
                    $(this).find("td:eq(2)").find("input").attr("disabled","disabled")
                    $(this).find("td:eq(2)").find("input").val(1)
                }
            })
        }
        ,mathcount: function(i)
        {
             var count=0;
            $(".t3_addItemList").find("tr:gt(0)").each(function(j){
                if(j<i){
                    var b = $(this).find("td:eq(2)").find("input").val();
                    count += parseInt(b);
                }
            });
            return count;
        }
        ,createProfit: function(type)
        {
            $addPeriodsInput = parseInt($('input[name='+type+'_addPeriodsInput]').val())
            $startBate = parseInt($('input[name='+type+'_startBate]').val())
            $profit = parseInt($('input[name=profit]').val())
            selectModel = r.orderItem[0].model
            MArray = r.orderItem[0].prize.split("|")[0]
            tzmoney = r.orderItem[0].singermoney
            Q =$profit/100
            if(selectModel == 0){
                MArray = MArray;
            } else if(selectModel == 1){
                MArray = MArray/10;
            } else if(selectModel == 2) {
                MArray = MArray/100;
            } else if(selectModel == 3) {
                MArray = MArray/1000;
            }
            $("."+type+"_addItemList").find("tr:gt(0)").each(function(i){
                if(i<$addPeriodsInput)
                {
                    $(this).find("td:eq(0)").find("input").removeAttr("disabled")
                    $(this).find("td:eq(2)").find("input").removeAttr("disabled")
                    $(this).find("td:eq(2)").find("input").val(1)
                    var n = $(this).find("td:eq(2)").find("input").val()
                    if(i==0)
                    {
                        $(this).find("td:eq(2)").find("input").val(n*$startBate);
                    }
                    else
                    {
                        var B = ssc.mathcount(i);
                        var m = B*tzmoney*(1+Q)/(MArray-tzmoney-tzmoney*Q);
                        m = Math.ceil(m);
                        if(m<=0)
                        {
                            alertify.error("<div class='text'><i class='ico-error'></i>达不到设置的利润率收益<i class='err-close'></i></div>")
                            $(this).find("td:eq(0)").find("input").attr("disabled","disabled")
                            $(this).find("td:eq(0)").find("input").prop("checked",false)
                            $(this).find("td:eq(2)").find("input").attr("disabled","disabled")
                            $(this).find("td:eq(2)").find("input").val(1)
                            $("."+type+"_addItemList").find("tr:eq(1)").find("td:eq(0)").find("input").attr("disabled","disabled")
                            $("."+type+"_addItemList").find("tr:eq(1)").find("td:eq(0)").find("input").prop("checked",false)
                            $("."+type+"_addItemList").find("tr:eq(1)").find("td:eq(2)").find("input").attr("disabled","disabled")
                            $("."+type+"_addItemList").find("tr:eq(1)").find("td:eq(2)").find("input").val(1)
                            $("."+type+"_addItemList").find("tr:eq(1)").find("td:eq(3)").html('0元')
                            return false
                        }
                        else
                        {
                            $(this).find("td:eq(2)").find("input").val(m);
                        }
                    }

                    $doublebate = parseInt($(this).find("td:eq(2)").find("input").val())
                    $singernoteval = parseInt(r.orderItem[0].singernote)
                    switch (parseInt(r.orderItem[0].model))
                    {
                        case 0:
                            $m = $doublebate*$singernoteval*2
                            break;
                        case 1:
                            $m = $doublebate*$singernoteval*2/10
                            break;
                        case 2:
                            $m = $doublebate*$singernoteval*2/100
                            break;
                        case 3:
                            $m = $doublebate*$singernoteval*2/1000
                            break;
                    }
                    $(this).find("td:eq(0)").find("input").prop("checked",true)
                    $(this).find("td:eq(3)").text($m)

                    $(this).find("td:eq(2)").find("input").on("blur",function(e){
                        var $item = $(e.target).closest('tr')
                        $singernoteval = parseInt(r.orderItem[0].singernote)
                        $chidldstartBate = $(this).val()
                        switch (parseInt(r.orderItem[0].model))
                        {
                            case 0:
                                $cm = $chidldstartBate*$singernoteval*2
                                break;
                            case 1:
                                $cm = $chidldstartBate*$singernoteval*2/10
                                break;
                            case 2:
                                $cm = $chidldstartBate*$singernoteval*2/100
                                break;
                            case 3:
                                $cm = $chidldstartBate*$singernoteval*2/1000
                                break;
                        }
                        $item.find("td:eq(3)").text($cm)
                        ssc.calculateAddListItem()
                    })
                    $(this).find("td:eq(0)").find("input").on('click',function(){
                        ssc.calculateAddListItem()
                    })
                }
                else
                {
                    $(this).find("td:eq(0)").find("input").attr("disabled","disabled")
                    $(this).find("td:eq(0)").find("input").prop("checked",false)
                    $(this).find("td:eq(2)").find("input").attr("disabled","disabled")
                    $(this).find("td:eq(2)").find("input").val(1)
                }
            })
        }
        ,calculateAddListItem: function()
        {
            type=""
            $("#menu0").find("li").each(function(){
                if($(this).hasClass("hover"))
                {
                    type = $(this).attr("aria-controls")
                }
            })
            var $totalMoney = 0
            r.addItem = []
            $("."+type+"_addItemList").find("tr:gt(0)").each(function(){
                if($(this).find("td:eq(0)").find("input").prop("checked")==true)
                {
                    $id = parseInt($(this).find("td:eq(0)").find("input").val())
                    $bet = parseInt($(this).find("td:eq(2)").find("input").val())
                    $amoney = parseFloat($(this).find("td:eq(3)").text())
                    $order = $id+":"+$bet+":"+parseFloat($amoney)
                    r.addItem.push($order)
                    $totalMoney += parseFloat($amoney*1000)
                }
            })
            $("."+type+"_addTotalMoney").text(parseFloat($totalMoney/1000))
        }
        ,addOrderSubmit: function()
        {
            $addItem = r.addItem
            $orderItem = r.orderItem
            if($addItem.length==0 || $orderItem.length==0) return;
            $totalMoney = 0;
            $data = ""
            $selectData = ""
            var t = new Date().getTime();
            var randkey = t + r.orderItem[0].lid + ssc.RndNum(6);
            randkey = "z*" + randkey + "*" + r.currentid + "*" + $("input[name=uid]").val();
            for(var o in $orderItem)
            {
                if($orderItem[o].playname=="" || $orderItem[o].selectNumber=="" || $orderItem[o].singernote==0
                    || $orderItem[o].singermoney==0 || $orderItem[o].model=="" || parseInt($orderItem[o].multiple)<1
                    || r.currentid=="" || $orderItem[o].lid=="" || $orderItem[o].singermoney==0)
                {

                }
                else
                {
                    $selectData = $orderItem[o].playname+":"+$orderItem[o].selectNumber+":"+$orderItem[o].singernote+
                        ":"+$orderItem[o].multiple+":"+$orderItem[o].singermoney+":"+$orderItem[o].model+":"+$orderItem[o].prize+":"+$orderItem[o].position
                    $lid = $orderItem[o].lid
                }
            }
            if($selectData=="") return
            $selectData = $selectData+"*"
            for(var i in $addItem)
            {
                $sp = $addItem[i].split(":")
                $totalMoney += parseFloat($sp[2]*1000)
                $selectData += $addItem[i] + "||"
            }
            $selectData = $selectData.substring(0, $selectData.length - 2);
            $totalMoney = parseFloat($totalMoney/1000)
            if ($("input[name='stop']").prop("checked") == true)
                var isStop = 1;
            else
                var isStop = 0;
            $data = "data="+$selectData+"&lottery_number_id="+r.currentid+"&is_add=1&lottery_id="+
                $lid+"&amount="+$totalMoney+"&isStop="+isStop+"&com="+randkey
            $.post("/Small",$data,function(info){
                if (info.status == 0 || info.status == "")
                {
                    $.dialog.close('*');
                    alertify.error("<div class='text'><i class='ico-error'></i>"+info.info+"<i class='err-close'></i></div>")
                    $("li",cartlist).remove()
                    r.orderItem = []
                    r.addItem = []
                    ssc.calculateCartlist()
                }
                else
                {
                    $.dialog.close('*');
                    layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{'closeBtn':0,'time':3000,anim:3,btnAlign: 'c'})
                        $("li",cartlist).remove()
                        r.orderItem = []
                        r.addItem = []
                        ssc.calculateCartlist()
                    })
                    setTimeout(ssc.getBuyRecordItem,2000)
                    setTimeout(ssc.getBuyAddRecordItem,2000)
                    setTimeout(ssc.getMoney,2000)
                }
            },'json')
        }
    }
    win.ssc = ssc;
})(window);

$(function(){
    ssc.Loading()

    /* todo 添加购买 */
    $(document).on("click",'a[data-method=addCartlist]',function(){
        ssc.addCartlist()
    })

    /*todo 删除购物车单条数据*/
    $(document).on('click', 'a[data-method="delete"]', function(e) {
        var $item = $(e.target).closest('li')
        var id = $(this).attr("data-field")
        $.dialog.close('*');
        layui.use('layer', function(layer){
        var index = layer.confirm('你确定要从购彩车中移除该订单吗?', {
            btn: ['确定','取消']
            ,anim:3
            ,btnAlign: 'c'
            }, function(){
                layer.close(index)
                $item.remove()
                r.orderItem.splice(id,1)
                ssc.calculateCartlist()
                $.dialog.close("*")
                return
            });
        })
    })

    /* todo 购物车全部清空 */
    $(document).on('click','a[data-method=cleanOrderItem]', function(){
        $.dialog.close('*');
        if($("li",cartlist).length==0) return false;
		layui.use('layer', function(layer){
        var index = layer.confirm('确定要清空购物车内容吗？', {
            btn: ['确定','取消']
            ,anim:3
            ,btnAlign: 'c'
            }, function(){
                layer.close(index)
                $("li",cartlist).remove()
                r.orderItem = []
                ssc.calculateCartlist()
                $.dialog.close("*")
                return
            });
        })
    })

    /* todo 倍数改变 */
    $('input[name=multiple]').on("blur",function(){
        $multipleval = parseInt($("input[name=multiple]").val())
        $multipleval = (isNaN($multipleval) || $multipleval==0) ? 1 : $multipleval;
        $("input[name=multiple]").val($multipleval)
        ssc.mutiplechange()
    })
    $("#diff").bind("click",function(){
        var multiple = parseInt($("input[name=multiple]").val())
        multiple = multiple + 1;
        $("input[name=multiple]").val(multiple)
        ssc.mutiplechange()
    })
    $("#plus").bind("click",function(){
        var multiple = parseInt($("input[name=multiple]").val())
        multiple = multiple - 1;
        multiple = (multiple<=0)?1:multiple;
        $("input[name=multiple]").val(multiple)
        ssc.mutiplechange()
    })

    /* todo 快速投注 */
    $('a[data-method="quickBet"]').on("click",function(){
        if(ssc.checkSubmit())
        {
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
                $('.quickBet').html('一键投注')
                 if(info==null) return
                if (info.status == 0 || info.status == "")
                {
                    $.dialog.close('*');
					layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        ssc.cleanSelectItemData()
                        ssc.addCartlistAfterHandler()
                        $('#cartlist>li').remove()
                        r.orderItem = []
                        ssc.calculateCartlist()
                    })
                }
                else
                {
                    $.dialog.close('*');
					layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        ssc.cleanSelectItemData()
                        ssc.addCartlistAfterHandler()
                        $('#cartlist>li').remove()
                        r.orderItem = []
                        ssc.calculateCartlist()
                        setTimeout(ssc.getBuyRecordItem,2000)
                        setTimeout(ssc.getBuyAddRecordItem,2000)
                        setTimeout(ssc.getMoney,2000)
                    })
                }
            },'json')
        }
        else
        {
            return false;
        }
    })

    /* todo 订单投注 */
    $('a[data-method=orderSubmit]').on("click",function(){
        $order = r.orderItem;
        var t = new Date().getTime();
        if($order.length==0) return false;
        var randkey = t + r.orderItem[0].lid + ssc.RndNum(6);
        randkey = "n*" + randkey + "*" + r.currentid + "*" + $("input[name=uid]").val();
        $selectData = ""
        $totalAmount = 0;
        for(var o in $order)
        {
            if($order[o].playname=="" || $order[o].selectNumber=="" || $order[o].singernote==0
                || $order[o].singermoney==0 || $order[o].model=="" || parseInt($order[o].multiple)<1
                || r.currentid=="" || $order[o].lid=="" || $order[o].singermoney==0)
            {

            }
            else
            {
                $selectData += $order[o].playname+':'+$order[o].selectNumber+":"+ $order[o].singernote+":1:"+$order[o].singermoney+
                    ":"+ $order[o].model+":"+ $order[o].prize+":"+ $order[o].multiple + ":"+ $order[o].position + "||"
                $lid = $order[o].lid
                $totalAmount += parseFloat($order[o].singermoney*1000)
            }
        }
        if($selectData=="") return
        $selectData = $selectData.substr(0,$selectData.length-2);
        $totalAmount = parseFloat($totalAmount/1000)
        $data = "data="+$selectData+"&lottery_number_id="+r.currentid+"&is_add=0&lottery_id="+
            $lid+"&amount="+parseFloat($totalAmount)+"&com="+randkey
            $('.orderSubmit').removeAttr('data-method')
        $('.orderSubmit').attr("disable","disable")
        $('.orderSubmit').html('<i class="icon-spinner icon-spin icon-1x"></i>提交中..')
        $.post("/Small",$data,function(info){
            $('.orderSubmit').removeAttr('disable')
            $('.orderSubmit').attr("data-method","orderSubmit")
            $('.orderSubmit').html('立即投注')
            if (info.status == 0 || info.status == "")
            {
            	$.dialog.close('*');
				layui.use('layer', function(layer){
                    layui.use('layer', function(layer){
                        layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                        $('#cartlist>li').remove()
	                    r.orderItem = []
	                    ssc.calculateCartlist()
                    })
                })
            }
            else
            {
                $.dialog.close('*');
                layui.use('layer', function(layer){
                    layer.alert('<div style="text-align:center">'+info.info+'</div>',{closeBtn: 0, time: 3000,anim:3,btnAlign: 'c'});
                    $('#cartlist>li').remove()
                    r.orderItem = []
                    ssc.calculateCartlist()
                    setTimeout(ssc.getBuyRecordItem,2000)
                    setTimeout(ssc.getBuyAddRecordItem,2000)
                    setTimeout(ssc.getMoney,2000)
                })
            }
        },'json')
    })
})