var $doc = $(document)
var $p = $('input[name=p]')
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
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),
            indexHandler.ChartLine(),indexHandler.ReportRadio(),indexHandler.Search(),indexHandler.OnlineUser(),indexHandler.SelChart(),
            indexHandler.ReportXy28Radio(),indexHandler.SearchXy28(),indexHandler.ReportAgRadio(),indexHandler.SearchAg(),
            indexHandler.ReportMgRadio(),indexHandler.SearchMg(),indexHandler.ReportPtRadio(),indexHandler.SearchPt(),
            indexHandler.ReportEbetRadio(),indexHandler.SearchEbet()
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
        ,SelChart: function()
        {
            $('a[data-method="selchart"]').on('click', function(){
                $('a[data-method="selchart"]').removeClass('on')
                $(this).addClass("on")
                target = $(this).attr("data-field")
                if(target=='cp')
                {
                    $('#cp').show()
                    $("#xy28").hide()
                    $("#ag").hide()
                    $("#mg").hide()
                    $("#pt").hide()
                    $("#ebet").hide()
                    indexHandler.ChartLine()
                    return false
                }
                if(target=='xy28')
                {
                    $('#cp').hide()
                    $("#xy28").show()
                    $("#ag").hide()
                    $("#mg").hide()
                    $("#pt").hide()
                    $("#ebet").hide()
                    indexHandler.Xy28ChartLine()
                    return false
                }
                if(target=='ag')
                {
                    $('#cp').hide()
                    $("#xy28").hide()
                    $("#ag").show()
                    $("#mg").hide()
                    $("#pt").hide()
                    $("#ebet").hide()
                    indexHandler.AgChartLine()
                    return false
                }
                if(target=='mg')
                {
                    $('#cp').hide()
                    $("#xy28").hide()
                    $("#ag").hide()
                    $("#mg").show()
                    $("#pt").hide()
                    $("#ebet").hide()
                    indexHandler.MgChartLine()
                    return false
                }
                if(target=='pt')
                {
                    $('#cp').hide()
                    $("#xy28").hide()
                    $("#ag").hide()
                    $("#mg").hide()
                    $("#pt").show()
                    $("#ebet").hide()
                    indexHandler.PtChartLine()
                    return false
                }
                if(target=='ebet')
                {
                    $('#cp').hide()
                    $("#xy28").hide()
                    $("#ag").hide()
                    $("#mg").hide()
                    $("#pt").hide()
                    $("#ebet").show()
                    indexHandler.EbetChartLine()
                    return false
                }
            })
        }
        ,ChartLine: function()
        {
            var dataArray=[];
            var czArray = [];
            var txArray = [];
            var xlArray = [];
            var fdArray = [];
            var pjArray = [];
            $.post('/getReportChart',function(info){
                info.forEach(function(data){
                    var str = data.split("&");
                    dataArray.push(str[0]);
                    czArray.push(parseInt(str[1]));
                    txArray.push(parseInt(str[2]));
                    xlArray.push(parseInt(str[3]));
                    fdArray.push(parseInt(str[4]));
                    pjArray.push(parseInt(str[5]));
                });
                var report_title = '最近七天走势图';
                var date = dataArray;
                var series = [{
                    name: "充值",
                    data: czArray
                }, {
                    name: "提现",
                    data: txArray
                }, {
                    name: "销量",
                    data: xlArray
                }, {
                    name: "反点",
                    data: fdArray
                }, {
                    name: "派奖",
                    data: pjArray
                }
                ]
                $('div.chart').highcharts({
                    title: {
                        text: report_title + '走势图',
                        align: 'left',
                    },
                    xAxis: {
                        categories: date
                    },
                    yAxis: {
                        title: {
                            text: report_title
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: "horizontal",
                        align: 'right',
                        verticalAlign: 'top',
                        floating: true,
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: series
                })
            },'json')
        }
        ,Xy28ChartLine: function()
        {
            var dataArray=[];
            var czArray = [];
            var txArray = [];
            var xlArray = [];
            var fdArray = [];
            var pjArray = [];
            $.post('/getReportXy28Chart',function(info){
                info.forEach(function(data){
                    var str = data.split("&");
                    dataArray.push(str[0]);
                    xlArray.push(parseInt(str[1]));
                    fdArray.push(parseInt(str[2]));
                    pjArray.push(parseInt(str[3]));
                });
                var report_title = '最近七天幸运28走势图';
                var date = dataArray;
                var series = [{
                    name: "销量",
                    data: xlArray
                }, {
                    name: "反点",
                    data: fdArray
                }, {
                    name: "派奖",
                    data: pjArray
                }
                ]
                $('div.xy28chart').highcharts({
                    title: {
                        text: report_title + '走势图',
                        align: 'left',
                    },
                    xAxis: {
                        categories: date
                    },
                    yAxis: {
                        title: {
                            text: report_title
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: "horizontal",
                        align: 'right',
                        verticalAlign: 'top',
                        floating: true,
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: series
                })
            },'json')
        }
        // ++++++++++++++++++++++++++++++++++AG
        ,AgChartLine: function()
        {
            var dataArray=[];
            var czArray = [];
            var txArray = [];
            var xlArray = [];
            var fdArray = [];
            var pjArray = [];
            var index = layer.load(2, {shade: [0.8, '#393D49']});
            $.post('/getReportAgChart',function(info){
                info.forEach(function(data){
                    var str = data.split("&");
                    dataArray.push(str[0]);
                    xlArray.push(parseFloat(str[1]));
                    fdArray.push(parseFloat(str[2]));
                });
                var report_title = '最近七天AG走势图';
                var date = dataArray;
                var series = [{
                    name: "销量",
                    data: xlArray
                }, {
                    name: "盈利",
                    data: fdArray
                }
                ]
                $('div.agchart').highcharts({
                    title: {
                        text: report_title + '走势图',
                        align: 'left',
                    },
                    xAxis: {
                        categories: date
                    },
                    yAxis: {
                        title: {
                            text: report_title
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: "horizontal",
                        align: 'right',
                        verticalAlign: 'top',
                        floating: true,
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: series
                })
                layer.close(index)
            },'json')
        }
        ,ReportAgRadio: function()
        {
            $doc.on('click','a[date-method=reportAgRadio]',function(e) {
                $('a[date-method=reportAgRadio]').removeClass('on')
                $(this).addClass('on')
                var $day = $(this).attr("data-field");
                if($day=="") return;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportAgByDay',{"day":$day},function(info){
                    layer.close(index)
                    $("#agsales").text(info['report']['totalBet'].toFixed(4))
                    $("#aggain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }
        ,SearchAg: function()
        {
            $doc.on('click','button[data-method="searchAg"]', function(e) {
                var $stime = $("input[name='agstarttime']").val();
                var $etime = $("input[name='agendtime']").val();
                if($stime=="" || $etime=="") return ;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportAgByTime',{"stime":$stime,"etime":$etime},function(info){
                    layer.close(index)
                    $("#agsales").text(info['report']['totalBet'].toFixed(4))
                    $("#aggain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }

        // ++++++++++++++++++++++++++++++++++MG
        ,MgChartLine: function()
        {
            var dataArray=[];
            var czArray = [];
            var txArray = [];
            var xlArray = [];
            var fdArray = [];
            var pjArray = [];
            var index = layer.load(2, {shade: [0.8, '#393D49']});
            $.post('/getReportMgChart',function(info){
                info.forEach(function(data){
                    var str = data.split("&");
                    dataArray.push(str[0]);
                    xlArray.push(parseFloat(str[1]));
                    pjArray.push(parseFloat(str[2]));
                    fdArray.push(parseFloat(str[3]));
                });
                var report_title = '最近七天MG走势图';
                var date = dataArray;
                var series = [{
                    name: "销量",
                    data: xlArray
                }, {
                    name: "中奖",
                    data: pjArray
                }, {
                    name: "盈利",
                    data: fdArray
                }
                ]
                $('div.mgchart').highcharts({
                    title: {
                        text: report_title + '走势图',
                        align: 'left',
                    },
                    xAxis: {
                        categories: date
                    },
                    yAxis: {
                        title: {
                            text: report_title
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: "horizontal",
                        align: 'right',
                        verticalAlign: 'top',
                        floating: true,
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: series
                })
                layer.close(index)
            },'json')
        }
        ,ReportMgRadio: function()
        {
            $doc.on('click','a[date-method=reportMgRadio]',function(e) {
                $('a[date-method=reportMgRadio]').removeClass('on')
                $(this).addClass('on')
                var $day = $(this).attr("data-field");
                if($day=="") return;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportMgByDay',{"day":$day},function(info){
                    layer.close(index)
                    $("#mgsales").text(info['report']['totalBet'].toFixed(4))
                    $("#mgbonus").text(info['report']['totalBonus'].toFixed(4))
                    $("#mggain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }
        ,SearchMg: function()
        {
            $doc.on('click','button[data-method="searchMg"]', function(e) {
                var $stime = $("input[name='mgstarttime']").val();
                var $etime = $("input[name='mgendtime']").val();
                if($stime=="" || $etime=="") return ;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportMgByTime',{"stime":$stime,"etime":$etime},function(info){
                    layer.close(index)
                   $("#mgsales").text(info['report']['totalBet'].toFixed(4))
                    $("#mgbonus").text(info['report']['totalBonus'].toFixed(4))
                    $("#mggain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }

        // ++++++++++++++++++++++++++++++++++PT
        ,PtChartLine: function()
        {
            var dataArray=[];
            var czArray = [];
            var txArray = [];
            var xlArray = [];
            var fdArray = [];
            var pjArray = [];
            var index = layer.load(2, {shade: [0.8, '#393D49']});
            $.post('/getReportPtChart',function(info){
                info.forEach(function(data){
                    var str = data.split("&");
                    dataArray.push(str[0]);
                    xlArray.push(parseFloat(str[1]));
                    pjArray.push(parseFloat(str[2]));
                    fdArray.push(parseFloat(str[3]));
                });
                var report_title = '最近七天PT走势图';
                var date = dataArray;
                var series = [{
                    name: "销量",
                    data: xlArray
                }, {
                    name: "中奖",
                    data: pjArray
                }, {
                    name: "盈利",
                    data: fdArray
                }
                ]
                $('div.ptchart').highcharts({
                    title: {
                        text: report_title + '走势图',
                        align: 'left',
                    },
                    xAxis: {
                        categories: date
                    },
                    yAxis: {
                        title: {
                            text: report_title
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: "horizontal",
                        align: 'right',
                        verticalAlign: 'top',
                        floating: true,
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: series
                })
                layer.close(index)
            },'json')
        }
        ,ReportPtRadio: function()
        {
            $doc.on('click','a[date-method=reportPtRadio]',function(e) {
                $('a[date-method=reportPtRadio]').removeClass('on')
                $(this).addClass('on')
                var $day = $(this).attr("data-field");
                if($day=="") return;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportPtByDay',{"day":$day},function(info){
                    layer.close(index)
                    $("#ptsales").text(info['report']['totalBet'].toFixed(4))
                    $("#ptbonus").text(info['report']['totalBonus'].toFixed(4))
                    $("#ptgain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }
        ,SearchPt: function()
        {
            $doc.on('click','button[data-method="searchPt"]', function(e) {
                var $stime = $("input[name='ptstarttime']").val();
                var $etime = $("input[name='ptendtime']").val();
                if($stime=="" || $etime=="") return ;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportPtByTime',{"stime":$stime,"etime":$etime},function(info){
                    layer.close(index)
                   $("#ptsales").text(info['report']['totalBet'].toFixed(4))
                    $("#ptbonus").text(info['report']['totalBonus'].toFixed(4))
                    $("#ptgain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }

        // ++++++++++++++++++++++++++++++++++Ebet
        ,EbetChartLine: function()
        {
            var dataArray=[];
            var czArray = [];
            var txArray = [];
            var xlArray = [];
            var fdArray = [];
            var pjArray = [];
            var index = layer.load(2, {shade: [0.8, '#393D49']});
            $.post('/getReportEbetChart',function(info){
                info.forEach(function(data){
                    var str = data.split("&");
                    dataArray.push(str[0]);
                    xlArray.push(parseFloat(str[1]));
                    pjArray.push(parseFloat(str[2]));
                    fdArray.push(parseFloat(str[3]));
                });
                var report_title = '最近七天EBET走势图';
                var date = dataArray;
                var series = [{
                    name: "销量",
                    data: xlArray
                }, {
                    name: "中奖",
                    data: pjArray
                }, {
                    name: "盈利",
                    data: fdArray
                }
                ]
                $('div.ebetchart').highcharts({
                    title: {
                        text: report_title + '走势图',
                        align: 'left',
                    },
                    xAxis: {
                        categories: date
                    },
                    yAxis: {
                        title: {
                            text: report_title
                        },
                        plotLines: [{
                            value: 0,
                            width: 1,
                            color: '#808080'
                        }]
                    },
                    legend: {
                        layout: "horizontal",
                        align: 'right',
                        verticalAlign: 'top',
                        floating: true,
                        borderWidth: 0
                    },
                    credits: {
                        enabled: false
                    },
                    exporting: {
                        enabled: false
                    },
                    series: series
                })
                layer.close(index)
            },'json')
        }
        ,ReportEbetRadio: function()
        {
            $doc.on('click','a[date-method=reportEbetRadio]',function(e) {
                $('a[date-method=reportEbetRadio]').removeClass('on')
                $(this).addClass('on')
                var $day = $(this).attr("data-field");
                if($day=="") return;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportEbetByDay',{"day":$day},function(info){
                    layer.close(index)
                    $("#ebetsales").text(info['report']['totalBet'].toFixed(4))
                    $("#ebetbonus").text(info['report']['totalBonus'].toFixed(4))
                    $("#ebetgain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }
        ,SearchEbet: function()
        {
            $doc.on('click','button[data-method="searchEbet"]', function(e) {
                var $stime = $("input[name='ebetstarttime']").val();
                var $etime = $("input[name='ebetendtime']").val();
                if($stime=="" || $etime=="") return ;
                var index = layer.load(2, {shade: [0.8, '#393D49']});
                $.post('/reportEbetByTime',{"stime":$stime,"etime":$etime},function(info){
                    layer.close(index)
                   $("#ebetsales").text(info['report']['totalBet'].toFixed(4))
                    $("#ebetbonus").text(info['report']['totalBonus'].toFixed(4))
                    $("#ebetgain").text(info['report']['totalGain'].toFixed(4))
                },'json')
            })
        }

        ,ReportRadio: function()
        {
            $doc.on('click','a[date-method=reportRadio]',function(e) {
                $('a[date-method=reportRadio]').removeClass('on')
                $(this).addClass('on')
                var $day = $(this).attr("data-field");
                if($day=="") return;
                $.post('/reportByDay',{"day":$day},function(info){

                    $rechargeAmount = (info['report']['rechargeAmount']!=null) ? parseInt(info['report']['rechargeAmount']) : 0
                    $tixianAmount = (info['report']['tixianAmount']!=null) ? parseInt(info['report']['tixianAmount']) : 0
                    $touzhuAmount = (info['report']['touzhuAmount']!=null) ? parseInt(info['report']['touzhuAmount']) : 0
                    $fandianAmount = (info['report']['fandianAmount']!=null) ? parseInt(info['report']['fandianAmount']) : 0
                    $bonusAmount = (info['report']['bonusAmount']!=null) ? parseInt(info['report']['bonusAmount']) : 0

                    $("#recharge").text($rechargeAmount.toFixed(4))
                    $("#withdraw").text($tixianAmount.toFixed(4))
                    $("#sales").text($touzhuAmount.toFixed(4))
                    $("#fd").text($fandianAmount.toFixed(4))
                    $("#bonus").text($bonusAmount.toFixed(4))
                },'json')
            })
        }
        ,Search: function()
        {
            $doc.on('click','button[data-method="search"]', function(e) {
                var $stime = $("input[name='starttime']").val();
                var $etime = $("input[name='endtime']").val();
                if($stime=="" || $etime=="") return ;
                $.post('/reportByTime',{"stime":$stime,"etime":$etime},function(info){
                    $rechargeAmount = (info['report']['rechargeAmount']!=null) ? parseInt(info['report']['rechargeAmount']) : 0
                    $tixianAmount = (info['report']['tixianAmount']!=null) ? parseInt(info['report']['tixianAmount']) : 0
                    $touzhuAmount = (info['report']['touzhuAmount']!=null) ? parseInt(info['report']['touzhuAmount']) : 0
                    $fandianAmount = (info['report']['fandianAmount']!=null) ? parseInt(info['report']['fandianAmount']) : 0
                    $bonusAmount = (info['report']['bonusAmount']!=null) ? parseInt(info['report']['bonusAmount']) : 0

                    $("#recharge").text($rechargeAmount)
                    $("#withdraw").text($tixianAmount)
                    $("#sales").text($touzhuAmount)
                    $("#fd").text($fandianAmount)
                    $("#bonus").text($bonusAmount)
                },'json')
            })
        }
        ,ReportXy28Radio: function()
        {
            $doc.on('click','a[date-method=reportXy28Radio]',function(e) {
                $('a[date-method=reportXy28Radio]').removeClass('on')
                $(this).addClass('on')
                var $day = $(this).attr("data-field");
                if($day=="") return;
                $.post('/reportXy28ByDay',{"day":$day},function(info){

                    $touzhuAmount = (info['report']['touzhuAmount']!=null) ? parseInt(info['report']['touzhuAmount']) : 0
                    $fandianAmount = (info['report']['fandianAmount']!=null) ? parseInt(info['report']['fandianAmount']) : 0
                    $bonusAmount = (info['report']['bonusAmount']!=null) ? parseInt(info['report']['bonusAmount']) : 0
                    $gainAmount = (info['report']['gain']!=null) ? parseInt(info['report']['gain']) : 0

                    $("#xy28sales").text($touzhuAmount.toFixed(4))
                    $("#xy28fd").text($fandianAmount.toFixed(4))
                    $("#xy28bonus").text($bonusAmount.toFixed(4))
                    $("#xy28gain").text($gainAmount.toFixed(4))
                },'json')
            })
        }
        ,SearchXy28: function()
        {
            $doc.on('click','button[data-method="searchXy28"]', function(e) {
                var $stime = $("input[name='xy28starttime']").val();
                var $etime = $("input[name='xy28endtime']").val();
                if($stime=="" || $etime=="") return ;
                $.post('/reportXy28ByTime',{"stime":$stime,"etime":$etime},function(info){
                    $touzhuAmount = (info['report']['touzhuAmount']!=null) ? parseInt(info['report']['touzhuAmount']) : 0
                    $fandianAmount = (info['report']['fandianAmount']!=null) ? parseInt(info['report']['fandianAmount']) : 0
                    $bonusAmount = (info['report']['bonusAmount']!=null) ? parseInt(info['report']['bonusAmount']) : 0
                    $gainAmount = (info['report']['gain']!=null) ? parseInt(info['report']['gain']) : 0

                    $("#xy28sales").text($touzhuAmount.toFixed(4))
                    $("#xy28fd").text($fandianAmount.toFixed(4))
                    $("#xy28bonus").text($bonusAmount.toFixed(4))
                    $("#xy28gain").text($gainAmount.toFixed(4))
                },'json')
            })
        }
        ,OnlineUser: function()
        {
            window.socket.on('ChildMemberCount',function(count){
                $("#online").text(count);
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})