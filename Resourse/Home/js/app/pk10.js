window.LOTTERYCONF = {
    "pk10": {
        selectItem:[{}],
        orderItem:[],
        addItem:[],
        tabs: [{
            name: "猜冠军",
            id: "cgj"
        }, {
            name: "前二名",
            id: "q2m"
        }, {
            name: "前三名",
            id: "q3m"
        }, {
            name: "前五定位",
            id: "q5dw"
        }, {
            name: "后五定位",
            id: "h5dw"
        }, {
            name: "猜单双",
            id: "cds"
        }, {
            name: "猜大小",
            id: "cdx"
        }],
        search: {
            "cgj": [{
                name: "猜冠军",
                games: [{
                    id: "cgj",
                    alias:"猜冠军",
                    name: "猜冠军"
                }]
            }],
            "q2m": [{
                name: "直选",
                games: [{
                    id: "q2mfs",
                    alias:"前二名直选复式",
                    name: "冠亚军"
                },{
                    id: "q2mds",
                    alias:"前二名直选单式",
                    name: "冠亚军单式"
                }]
            }],
            q3m: [{
                name: "直选",
                games: [{
                    id: "q3mfs",
                    alias:"前三名直选复式",
                    name: "冠亚季军"
                },{
                    id: "q3mds",
                    alias:"前三名直选单式",
                    name: "冠亚季军单式"
                }]
            }],
            "q5dw": [{
                name: "定位胆",
                games: [{
                    id: "q5dwd",
                    alias:"前五定位胆",
                    name: "前五名定位胆"
                }]
            }],
            "h5dw": [{
                name: "定位胆",
                games: [{
                    id: "h5dwd",
                    alias:"后五定位胆",
                    name: "后五名定位胆"
                }]
            }],
            "cds": [{
                name: "冠军单双",
                games: [{
                    id: "gjds",
                    alias:"第一名单双",
                    name: "冠军单双"
                }]
            },{
                name: "亚军单双",
                games: [{
                    id: "yjds",
                    alias:"第二名单双",
                    name: "亚军单双"
                }]
            },{
                name: "季军单双",
                games: [{
                    id: "jjds",
                    alias:"第三名单双",
                    name: "季军单双"
                }]
            }],
            "cdx": [{
                name: "冠军大小",
                games: [{
                    id: "gjdx",
                    alias:"第一名大小",
                    name: "冠军大小"
                }]
            },{
                name: "亚军大小",
                games: [{
                    id: "yjdx",
                    alias:"第二名大小",
                    name: "亚军大小"
                }]
            },{
                name: "季军大小",
                games: [{
                    id: "jjdx",
                    alias:"第三名大小",
                    name: "季军大小"
                }]
            }]
        },
        games: {
            "cgj": {
                describe: {
                    title: "游戏玩法：从01-10中任意选择1个以上号码。",
                    content: "投注方案：01<br />从01-10中选择一个号码，只要开奖的冠军车号与所选号码一致即中奖。如：选择05，开奖冠军车号为05，即为中奖"
                },
                items: [{
                    title: "第一名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                }]
            },
            "q2mfs": {
                describe: {
                    title: "游戏玩法：从01-10选择2个号码组成一注。",
                    content: "投注方案：5 8<br />从01-10选择2个号码组成一注，只要开奖的冠军车号、亚军车号与所选号码相同且顺序一致，即为中奖"
                },
                items: [{
                    title: "第一名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第二名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                }]
            },
            q2mds: {
                describe: {
                    title: "游戏玩法：手动输入一个2位数号码组成一注,号码之间用空格分开,每注用逗号分开。",
                    content: "投注方案：1 2,3 4<br /> 手动输入2个两位数号码组成一注，所选号码与开奖冠军、亚军相同，且顺序一致，即为中奖"
                },
                textarea: !0,
                fn: "XwDirectFilter",
                limit: 2,
                filter:"common"
            },
            "q3mfs": {
                describe: {
                    title: "游戏玩法：从01-10选择3个号码组成一注。",
                    content: "投注方案：3 4 5<br />从01-10中选择三个号码，只要开奖冠军、亚军、季军的车号与所选号码相同且顺序一致即中奖。如：冠军选择01，亚军选择02，季军选择03，开奖的冠军车号01、亚军02、季军03，即为中奖"
                },
                items: [{
                    title: "第一名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第二名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第三名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                }]
            },
            q3mds: {
                describe: {
                    title: "游戏玩法：手动输入一个3位数号码组成一注,号码之间用空格分开,每注用逗号分开。",
                    content: "投注方案：1 2 3,4 5 6<br /> 手动输入3个两位数号码组成一注，所选号码与开奖冠亚季军的车号相同，且顺序一致，即为中奖。"
                },
                textarea: !0,
                fn: "XwDirectFilter",
                limit: 3,
                filter:"common"
            },
            "q5dwd": {
                describe: {
                    title: "游戏玩法：在第一、二、三、四、五名任意位置上任意选择1个或1个以上号码。",
                    content: "投注方案：第一名：01；开奖第一名车号：01，即为中奖<br />从第一、二、三、四、五名任意位置上至少选择1个以上号码，所选号码与相同位置上的开奖车号一致，即为中奖"
                },
                items: [{
                    title: "第一名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第二名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第三名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第四名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第五名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                }]
            },
            "h5dwd": {
                describe: {
                    title: "游戏玩法：在第六、七、八、九、十名任意位置上任意选择1个或1个以上号码。",
                    content: "投注方案：第六名：01；开奖第六名车号：01，即为中奖<br />从第六、七、八、九、十名任意位置上至少选择1个以上号码，所选号码与相同位置上的开奖号码一致，即为中奖"
                },
                items: [{
                    title: "第六名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第七名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第八名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第九名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                },{
                    title: "第十名",
                    tools: [{
                        name: "全",
                        fn: '1'
                    }, {
                        name: "大",
                        fn: '2'
                    }, {
                        name: "小",
                        fn: '3'
                    }, {
                        name: "奇",
                        fn: '4'
                    }, {
                        name: "偶",
                        fn: '5'
                    }, {
                        name: "清",
                        fn: '6'
                    }],
                    nums: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10"]
                }]
            },
            "gjds": {
                describe: {
                    title: "游戏玩法：选择单或双为一注。",
                    content: "投注方案：第一名选择 双，开奖号码为08，即为中奖<br />选择单或双进行投注，只要开奖对应车号的单双(注：01,03,05,07,09为单；02,04,06,08,10为双)与所选项一致即中奖"
                },
                items: [{
                    title: "第一名",
                    nums: ["单", "双"]
                }]
            },
            "yjds": {
                describe: {
                    title: "游戏玩法：选择单或双为一注。",
                    content: "投注方案：第二名选择 双，开奖号码为08，即为中奖<br />选择单或双进行投注，只要开奖对应车号的单双(注：01,03,05,07,09为单；02,04,06,08,10为双)与所选项一致即中奖"
                },
                items: [{
                    title: "第二名",
                    nums: ["单", "双"]
                }]
            },
            "jjds": {
                describe: {
                    title: "游戏玩法：选择单或双为一注。",
                    content: "投注方案：第三名选择 双，开奖号码为08，即为中奖<br />选择单或双进行投注，只要开奖对应车号的单双(注：01,03,05,07,09为单；02,04,06,08,10为双)与所选项一致即中奖"
                },
                items: [{
                    title: "第三名",
                    nums: ["单", "双"]
                }]
            },
            "gjdx": {
                describe: {
                    title: "游戏玩法：选择大或小为一注。",
                    content: "投注方案：第一名选择 大，开奖号码为07，即为中奖<br />选择大或小进行投注，只要开奖的名次对应车号的大小(注：01,02,03,04,05为小；06,07,08,09,10为大)与所选项一致即中奖"
                },
                items: [{
                    title: "第一名",
                    nums: ["大", "小"]
                }]
            },
            "yjdx": {
                describe: {
                    title: "游戏玩法：选择大或小为一注。",
                    content: "投注方案：第二名选择 大，开奖号码为07，即为中奖<br />选择大或小进行投注，只要开奖的名次对应车号的大小(注：01,02,03,04,05为小；06,07,08,09,10为大)与所选项一致即中奖"
                },
                items: [{
                    title: "第二名",
                    nums: ["大", "小"]
                }]
            },
            "jjdx": {
                describe: {
                    title: "游戏玩法：选择大或小为一注。",
                    content: "投注方案：第三名选择 大，开奖号码为07，即为中奖<br />选择大或小进行投注，只要开奖的名次对应车号的大小(注：01,02,03,04,05为小；06,07,08,09,10为大)与所选项一致即中奖"
                },
                items: [{
                    title: "第三名",
                    nums: ["大", "小"]
                }]
            }
        }
    }
}
window.sortIndex = 1;
window.divtop = 35;
window.left = 100;
window.flag = true;
window.up_series_number="";
window.series_number="";
var isOpen = false;
var audio;
audio =  new Audio("/Resourse/Home/audio/raceing.mp3");
audio.autoplay = false;
audio.loop = true;
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
                    },$(s.target).attr('data-field')), ssc.t_oninput($(s.target).attr('data-field')),ssc.emptyText(),ssc.filterData(),ssc.importFile(),ssc.init({
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

                /*todo 猜冠军*/
                window.caiguanjun = function()
                {
                    $select = window.select
                    $count = $select[0]
                    return $count
                }

                var uniquelize = function(a){
                    var ra = new Array();
                    for(var i = 0; i < a.length; i ++){
                        if(jQuery.inArray(a[i],ra)==-1){
                            ra.push(a[i]);
                        }
                    }
                    return ra;
                }

                var intersect = function(a,b){
                    var temp = uniquelize(a);
                    var raa = new Array();
                    for(var i = 0; i < temp.length; i ++){
                        if(jQuery.inArray(temp[i],b)!=-1){
                            raa.push(temp[i]);
                        }
                    }
                    return raa;
                }
                /*todo 前二直选复式*/
                window.qianerzhixuanfushi = function()
                {
                    $select = window.select
                    $count1 = $select[0]
                    $count2 = $select[1]
                    $selectRowsNumber = window.selectRowsNumber
                    $rowsNumber = $selectRowsNumber.split(",")
                    if($rowsNumber[0]==null || $rowsNumber[1]==null) return 0
                    $arr = $rowsNumber[0].split(" ")
                    $brr = $rowsNumber[1].split(" ")
                    var lr = intersect($arr,$brr)
                    zhushu = $count1*$count2-lr.length
                    return zhushu
                }

                /*todo 前三直选复式*/
                window.qiansanzhixuanfushi = function()
                {
                    $select = window.select
                    $count1 = $select[0]
                    $count2 = $select[1]
                    $count3 = $select[2]
                    $selectRowsNumber = window.selectRowsNumber
                    $rowsNumber = $selectRowsNumber.split(",")
                    if($rowsNumber[0]==null || $rowsNumber[1]==null || $rowsNumber[2]==null) return 0

                    $arr = $rowsNumber[0].split(" ")
                    $brr = $rowsNumber[1].split(" ")
                    $crr = $rowsNumber[2].split(" ")


                    var lr11 = intersect($arr,$brr);
                    var lr12 = intersect(lr11,$crr);
                    var len = lr12.length;

                    var lr1 = intersect($arr,$brr);
                    var len1 = lr1.length;

                    var lr2 = intersect($arr,$crr);
                    var len2 = lr2.length;

                    var lr3 = intersect($crr,$brr);
                    var len3 = lr3.length;
                    zhushu = $count1*$count2*$count3-$count3*len1-$count2*len2-$count1*len3+len*2;

                    return zhushu
                }

                /*todo 定位胆*/
                window.dingweidan = function()
                {
                    $select = window.select
                    $count1 = $select[0]
                    $count2 = $select[1]
                    $count3 = $select[2]
                    $count4 = $select[3]
                    $count5 = $select[4]
                    return $count1+$count2+$count3+$count4+$count5
                }

                /*todo 大小单双*/
                window.daxiaodanshuang = function()
                {
                    $select = window.select
                    $count = $select[0]
                    return $count
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
        ,SoundSwitch: function()
        {
            // 声音开关
            $("#soundSwitch").on('click',function(){
                if(audio.paused){
                    audio.play();
                    $("#soundSwitch").removeClass("sound_off").addClass("sound_on");
                    $("#soundSwitch").attr("data-field",1)
                }else{
                    audio.pause();
                    $("#soundSwitch").removeClass("sound_on").addClass("sound_off");
                    $("#soundSwitch").attr("data-field",0)
                }
            })
        }
        ,PlaySoundHandler: function()
        {
            ssc.PlaySound('/Resourse/Home/audio/call.wav')
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
        ,displayPanl: function()
        {
            $.post("/DisplayBuy/timeDown",{"lid":$lid},function(data){
                display.html(tpl("#display_tpl",{rows:[data]})),ssc.runIt(data['last_number'])
                ssc.SoundSwitch()
                r.bonus = [data['bonus']]
                r.currentid = data['id']
                r.selectItem[0].lid = data['lid']
                r.selectItem[0].currentid = data['id']
                r.selectItem[0].bonusLevel = data['bonuslevel']
                var endtime = data['endtime'];
                $("#current_endtime").html(endtime);
                $("#bottomSearis").html(data.series_number)

                window.series_number = data['series_number'];
                window.up_series_number = data['last_series_number'];

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

                if(parseInt(window.up_series_number)==(parseInt(window.series_number)-1)){
                    $(".pkkaijiang_dh").hide();
                    $(".pk_mingci").show();
                }
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
                    if(parseInt(window.up_series_number)!=(parseInt(window.series_number)-1)){
                        //如果进入的时间在300－250秒之间的话。显示倒计时
                        if(leave<=300*1000 && leave>=220*1000){
                            $(".car-num-content01").show();
                            $(".pkkaijiang_dh").hide();
                            $(".pk_mingci").hide();
                            var leave2 = 80*1000-(300*1000-leave);
                            var timestamp2 = leave2/1000;
                            var second2 = Math.floor( timestamp2 % 60);
                            var minute2 = Math.floor((timestamp2 / 60)	% 60);
                            var lastLottery = $("#current_issue").text();
                            $(".kj_span1").text(parseInt(lastLottery)-1);
                            $(".kj_span2").html('<i>00</i><i>'+("0"+minute2).slice(-2)+"</i><i>"+("0"+second2).slice(-2)+'</i>');
                            if(leave2<1000)
                            {
                                ssc.carrun()
                                ssc.trackloop();
                            }
                        } else {
                            if(window.flag==true){
                                if($("#soundSwitch").attr("data-field")==1)
                                {
                                    audio.play();
                                }
                                $(".car-num-content01").hide();//隐藏第二个倒计时
                                $(".pk_mingci").hide();
                                $(".pkkaijiang_dh").show();//显示汽车图层
                                ssc.trackloop();
                                ssc.carrun();
                                window.flag=false;
                            }
                        }
                    }
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
            ssc.loadLastData(num) //显示排名动画
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
                 case 21:
                    $event = "pk10OpenCode";break;
            }
            window.socket.on($event,function(data)
            {
                $json = JSON.parse(data);
                $("#lastSeries").text($json.series_number);

                var _recentHtml = '<li><div class="col1">'+$json.series_number+'</div><div class="col2">'
                var $numarray = $json.number.split(",")
                for(var j=0; j<$numarray.length; j++)
                {
                    $numarray[j] = ($numarray[j].length==1) ? "0"+$numarray[j] : $numarray[j]
                    _recentHtml += '<i>'+$numarray[j]+'</i>';
                }
                _recentHtml += '</div></li>';
                $("#game_jwuqi").find("li:last").remove()
                $("#game_jwuqi").find("li:first").before(_recentHtml);

                $("#divTrack1").stop();	 	//停止背景移动
                $(".pk_mingci").show(); 	//显示排名
                $(".pkkaijiang_dh").hide();	//隐藏开奖动画
                $('#newMessageDIV').empty();

                audio.pause();
                
                ssc.runIt($json.number)
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
        ,loadLastData: function(code)
        {
            var carArr = code.split(",");
            ssc.showSorts(carArr)
        }
        ,showSorts: function(carArr)
        {
            for(var i=0; i<carArr.length; i++)
            {
                $("#top"+i).removeClass().addClass("car divcar"+carArr[i])
            }
        }
        ,carrun: function()
        {
            if(!$("#divTrack1").is(":animated")){
                ssc.trackloop()				
            }
            for(var i=1;i<=10;i++){
                $("#divGameCar"+i).find("div").css("right","0px");
                var timer = ssc.getrandom() * 1000;
			    ssc.setCarSpeed(i,timer);
            }
        }
        ,trackloop: function()
        {
            $("#divTrack1").css("right","0");
            $("#divTrack2").css("right","0");
            $("#divTrack1").animate({
            right:"-10000px",
            },16000,'linear',function(){
                ssc.trackloop();
            });
        }
        ,setCarSpeed: function(carNum,timer)
        {
            var divGameCar = $("#divGameCar"+carNum);
            divGameCar.find("div").animate({right:"350",},timer,'linear',function(){
                var div = divGameCar.find("div");
                if(!isOpen){
                    div.css("right","0px");
                    var randomTimer = ssc.getrandom() * 1000;
                    ssc.setCarSpeed(carNum,randomTimer);
                }
            });
        }
        ,getrandom: function()
        {
            return	Math.floor(Math.random() *25 + 1)
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
            var _html = '<option value="1">奖金'+ a['bonus'][0]['common'][$n]+'-'+a['bonuslevel']+'%</option>';
            prize.html(_html);
        }
        ,t_oninput: function(p)
        {
            if(String(p).indexOf("单式")>0 || String(p).indexOf("混合组选")>0)
            {
                var element = document.getElementById("lt_write_box");
                if("\v"=="v")
                {
                    element.onpropertychange = function()
                    {
                        var s = element.value;
                        s = s.replace(/[^\d\s,]/g,",");
                        element.value = s;
                        s = s.replace(/(^\s*)|(\s*$)/g,"")
                        var a = (s!="") ? s.split(",") : "";

                        $result = ssc.checkUnitaryMate(a);
                        if($result['result']!="")
                            a = $result['result'].split(",");
                        else
                            a = []
                        ssc.calculateCommonUnitary(a.length)
                        ssc.setSingerSelectItemNumber($result['result'])
                    }
                }
                else
                {
                    element.addEventListener("input",function(){
                        var s = element.value;
                        s = s.replace(/[^\d\s,]/g,",");
                        element.value = s;
                        s = s.replace(/(^\s*)|(\s*$)/g,"")
                        var a = (s!="") ? s.split(",") : "";

                        $result = ssc.checkUnitaryMate(a);
                        if($result['result']!="")
                            a = $result['result'].split(",");
                        else
                            a = []
                        ssc.calculateCommonUnitary(a.length)
                        ssc.setSingerSelectItemNumber($result['result'])
                    },false);
                }
            }
        }
        ,checkUnitaryMate: function(a)
        {
            $fn = $("#lt_write_box").attr("data-method")
            $filter = $("#lt_write_box").attr("data-fn")
            $limit = $("#lt_write_box").attr('data-field')
            $result = SetFilterFunc($fn,a,$limit,$filter);
            return $result;
        }
        ,emptyText: function()
        {
            $("a[data-method=empty-text]").on("click",function(){
                var element = document.getElementById("lt_write_box");
                element.value = "";
                $n = r.selectItem[0]['playname']
                a = ""
                ssc.calculateCommonUnitary(a.length)
                ssc.setSingerSelectItemNumber("")
            })
        }
        ,filterData: function()
        {
            $("a[data-method=filter-data]").on("click",function(){
                var element = document.getElementById("lt_write_box");
                var s = element.value;
                s = s.replace(/[;]/g,",")
                s = s.replace(/(^\s*)|(\s*$)/g,"")
                var a = (s!="") ? s.split(",") : "";
                if(a!="")
                {
                    res = window.filterArray(a);
                    result = res['result'];
                    filter = res['filter'];

                    if(result=="") return false;
                    element.value = result.join(" ")
                    s = element.value;
                    var a = (s!="") ? s.split(" ") : "";
                    p = $('.selected',"#game_search").attr("data-field");
                    ssc.calculateCommonUnitary(a.length)
                    ssc.setSingerSelectItemNumber(result.join(" "))
                    if(filter.length>0)
                    {
                        $data = filter.join(" ")
                        $.dialog.open({
                            title: '过滤的数据',
                            width: 600,
                            btnText:['关闭'],
                            type: 'alert'
                        }).ready(function(o) {
                            o.html(tpl('#filternumber_tpl',{rows:$data}))
                        }).confirm(function() {
                            $.dialog.close("*")
                            return false
                        })
                    }
                }
                else
                {
                    return false;
                }
            })
        }
        ,importFile: function()
        {
            $('a[data-method=import-file]').on('click',function(){
                layui.use('layer', function(layer){
                    var index = layer.confirm('<div style="text-align:center"><a href="javascript:;" class="a-upload"><input type="file" name="" id="file"><s class="fa fa-file"></s>上传文件txt文件</a></div>', {
                        title:'导入号码'
                        ,btn: ['导入号码','关闭'] 
                        ,anim:3
                        ,btnAlign: 'c'
                        }, function(){
                            if(typeof FileReader == 'undefined') {
                                return false;
                            }
                            var simpleFile = document.getElementById("file").files[0];
                            var reader = new FileReader();
                            // 将文件以文本形式读入页面中
                            reader.readAsText(simpleFile);
                            reader.onload = function(e){
                                var element = document.getElementById("lt_write_box");
                                result = this.result.replace(/[^\d\s]/g,",").replace(/(^\s*)|(\s*$)/g,"")
                                element.value = result
                                s = result;
                                var a = (s!="") ? s.split(" ") : "";
                                $result = ssc.checkUnitaryMate(a);
                                if($result['result']!="")
                                    a = $result['result'].split(" ");
                                else
                                    a = []
                                p = $('.selected',"#game_search").attr("data-field");
                                ssc.calculateCommonUnitary(a.length)
                                ssc.setSingerSelectItemNumber($result['result'])
                            }
                            layer.close(index)
                        });
                })
            })
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
            if(String($n).indexOf("单式")>0  || String($n).indexOf("混合组选")>0)
            {
                var element = document.getElementById("lt_write_box");
                element.value = "";
            }
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
        ,setSingerSelectItemNumber: function(number)
        {
            r.selectItem[0].selectNumber = number
        }
        ,setSelectItemLowPrize: function()
        {
            playname = r.selectItem[0].playname
            r.selectItem[0].prize = r.bonus[0]['common'][playname]+"|"+ r.selectItem[0].bonusLevel + "|1"
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
                if($playname.indexOf("定位")==-1)
                {
                    if($num!="")
                    {
                        $num = $num.substring(0,$num.length-1)
                        $selectNumber += $num + ","
                    }
                }
                else
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
                '"猜冠军":"caiguanjun",' +
                '"前二名直选复式":"qianerzhixuanfushi",' +
                '"前三名直选复式":"qiansanzhixuanfushi",' +
                '"前五定位胆":"dingweidan",' +
                '"后五定位胆":"dingweidan",' +
                '"第一名单双":"daxiaodanshuang",' +
                '"第二名单双":"daxiaodanshuang",' +
                '"第三名单双":"daxiaodanshuang",' +
                '"第一名大小":"daxiaodanshuang",' +
                '"第二名大小":"daxiaodanshuang",' +
                '"第三名大小":"daxiaodanshuang"}'
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
                || r.selectItem[0].singermoney==0 || r.selectItem[0].model=="" || r.selectItem[0].prize=="" || parseInt(r.selectItem[0].multiple)<1
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
                    || $orderItem[o].singermoney==0 || $orderItem[o].model=="" || $orderItem[o].prize=="" || parseInt($orderItem[o].multiple)<1
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
            $.post("/Big",$data,function(info){
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
            $.post("/Big",$data,function(info){
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
                || $order[o].singermoney==0 || $order[o].model=="" || $order[o].prize=="" || parseInt($order[o].multiple)<1
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
        $.post("/Big",$data,function(info){
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