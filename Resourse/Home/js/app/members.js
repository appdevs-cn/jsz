var $doc = $(document)
var $contents = $('div.cont', $doc)
var $uid = $('input[name=uid]');
var $p = $('input[name=p]');
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
            window.socket.on("message", function($data) {
                var $json = $data.split('-')
                if ($json[1] == 5 || $json[1] == 4 || $json[1] == 12 || $json[1] == 13) {
                    indexHandler.GetList()
                }
            })
            indexHandler.NoticeSlider(),
            indexHandler.Logout(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.GetList(),indexHandler.search()
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
        ,GetList: function()
        {
            $.dialog.close("*")
            _page = $p.val();
            _uid = $uid.val();
            $.post('/searchMember',{"p":_page,"uid":_uid},function(data){
                if(data.length==0)
                {
                    $(".panel-lkList").html(tpl('#empty_member_list'))
                    return false;
                }
                $uid.val(data[0]['currentuid']);
                $(".panel-lkList").html(tpl('#member_list',{rows:[data]}))
                indexHandler.TrHover()
                $(".pageCls").on("click",function(){
                    $p.val($(this).attr("p"));
                    indexHandler.GetList()
                })
                $(".showChild").on('click',function(){
                    var uid = $(this).attr("childid");
                    $uid.val(uid);
                    indexHandler.GetList()
                })
                $(".showMenuChild").on('click',function(){
                    var uid = $(this).attr("childid");
                    $uid.val(uid);
                    indexHandler.GetList()
                })
                // 开户额度
                indexHandler.setAccount();
                indexHandler.transfer();
                indexHandler.setQuotaFunc();
                indexHandler.SignContract()
                indexHandler.SignContracting()
                indexHandler.SignContractend()
                indexHandler.DisContracting()

                indexHandler.SignDayrateContract()
                indexHandler.SignDayrateContracting()
                indexHandler.SignDayrateContractend()
                indexHandler.DisDayrateContracting()

                indexHandler.OpenDayrate()
                indexHandler.CloseDayrate()
                indexHandler.OpenShare()
                indexHandler.CloseShare()
                window.nowarr = new Array();
                for(var i=0; i<data.length; i++)
                {
                    window.nowarr.push(data[i]['sec']);
                }
                socket.on("OnlineStatus",function(info){
                    var onlineArray = new Array();
                    for(var o in info)
                    {
                        onlineArray.push(o);
                    }
                    var nowuser = window.nowarr;
                    for(var j=0; j<nowuser.length; j++)
                    {
                        if(jQuery.inArray( nowuser[j], onlineArray )!=-1)
                        {
                            $("#online"+nowuser[j]).html('在线');
                        }
                        else
                        {
                            $("#online"+nowuser[j]).html('离线');
                        }
                    }
                })
            },'json')
        }
        ,searchGetList: function()
        {
            $uid.val("");
            $page = $p.val();
            $username = $('input[name=username]').val();
            $starttime = $('input[name=starttime]').val();
            $endtime = $('input[name=endtime]').val();
            if($starttime=="" && $endtime=="")
            {

            }
            else
            {
                if($starttime=="" || $endtime=="")
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>请输入开始时间和结束时间进行查询<i class='err-close'></i></div>")
                    return;
                }
            }
            $mincapital = $('input[name=mincapital]').val();
            $maxcapital = $('input[name=maxcapital]').val();
            if($mincapital=="" && $maxcapital=="")
            {

            }
            else
            {
                if($mincapital=="" || $maxcapital=="")
                {
                    alertify.error("<div class='text'><i class='ico-error'></i>请输入最低余额和最高余额进行查询<i class='err-close'></i></div>")
                    return;
                }
            }
            $.post('/searchMember',{"p":$page,"uid":"","username":$username,"starttime":$starttime,"endtime":$endtime,"mincapital":$mincapital,"maxcapital":$maxcapital},function(data){
                if(data.length==0)
                {
                    $(".panel-lkList").html(tpl('#empty_member_list'))
                    return false;
                }
                $uid.val(data[0]['currentuid']);
                $(".panel-lkList").html(tpl('#member_list',{rows:[data]}))
                $(".pageCls").on("click",function(){
                    $p.val($(this).attr("p"));
                    indexHandler.searchGetList();
                })
                $(".showChild").on('click',function(){
                    var uid = $(this).attr("childid");
                    $uid.val(uid);
                    indexHandler.GetList();
                })
                $(".showMenuChild").on('click',function(){
                    var uid = $(this).attr("childid");
                    $uid.val(uid);
                    indexHandler.GetList();
                })
                // 开户额度
                indexHandler.setAccount();
                indexHandler.transfer();
                indexHandler.setQuotaFunc();
                indexHandler.SignContract()
                indexHandler.SignContracting()
                indexHandler.SignContractend()
                indexHandler.DisContracting()

                indexHandler.SignDayrateContract()
                indexHandler.SignDayrateContracting()
                indexHandler.SignDayrateContractend()
                indexHandler.DisDayrateContracting()

                indexHandler.OpenDayrate()
                indexHandler.CloseDayrate()
                indexHandler.OpenShare()
                indexHandler.CloseShare()
                
                window.nowarr = new Array();
                for(var i=0; i<data.length; i++)
                {
                    window.nowarr.push(data[i]['sec']);
                }
                socket.on("OnlineStatus",function(info){
                    var onlineArray = new Array();
                    for(var o in info)
                    {
                        onlineArray.push(o);
                    }
                    var nowuser = window.nowarr;
                    for(var j=0; j<nowuser.length; j++)
                    {
                        if(jQuery.inArray( nowuser[j], onlineArray )!=-1)
                        {
                            $("#online"+nowuser[j]).html('<span style="color:green">在线</span>');
                        }
                        else
                        {
                            $("#online"+nowuser[j]).html('<span style="color:gray">离线</span>');
                        }
                    }
                })
            },'json')
        }
        ,search: function()
        {
            $doc.on('click','button[data-method="search"]',function(e) {
                indexHandler.searchGetList()
            })
        }
        ,setAccount: function()
        {
            $('a[data-method="set_account"]').on('click',  function(e) {
                var $pd = $(e.target).attr("pd");
                $.post('/setAccount',{"pd":$pd},function(_html){
                    $.dialog.open({
                        title: '开户配置',
                        btnText:['配置','关闭'],
                        width: 800,
                        type: 'prompt'
                    }).ready(function(o){
                        o.html(tpl('#set_account'))
                        $("#set_account_content").html(_html);
                        $('button[data-method="sendQuota"]').on('click', function(){
                            $isCanable = $('input[name=isCanable]').val();
                            if($isCanable)
                            {
                                var data = $("#paieform").serialize();
                                $.post('/fenpaiHandler',data,function(msg){
                                    $.dialog.close("*");
                                    alertify.success("<div class='text'><i class='ico-success'></i>"+msg+"<i class='suc-close'></i></div>")
                                    return false
                                },'text');
                            }
                            else
                            {
                                $.dialog.close("*")
                                return false
                            }
                        })
                    })
                },'html')
            })
        }
        ,transfer: function()
        {
            $('a[data-method="set_transfer"]').on('click',  function(e) {
                var $money = $(".financeMoney").html();
                var $u = $(this).attr("u");
                var $uname = $(this).attr("uname");
                $.dialog.open({
                    title: '转账',
                    btnText:['转账','关闭'],
                    width: 600,
                    type: 'prompt'
                }).ready(function(o) {
                    o.html(tpl('#set_transfer',{rows:[{"uid":$u,"uname":$uname,"money":$money}]}))
                    layui.use('layer', function(layer){
                        $('button[data-method=transferMoney]').on('click',function(){
                            var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                            var _u = $("input[name='u']").val();
                            var _m = $("input[name='amount']").val();
                            var _p = $("input[name=currentPwd]").val();
                            var _t = $("select[name=type]").val();
                            var reg = /^\d+$/gi;
                            if(!reg.test(_m) || _u=="" || _m=="" || _p=="" || _t==""){
                                $.dialog.close("*");
                                return false;
                            }
                            $.post("/transfertouser",{"p":_p,"u":_u,"m":_m,"t":_t},function(res){
                                $.dialog.close("*");
                                layer.close(index)
                                alertify.success("<div class='text'><i class='ico-success'></i>"+res+"<i class='suc-close'></i></div>")
                                indexHandler.GetList()
                                $(".financeMoney").html(parseFloat(Number($(".financeMoney").html())-Number(_m)).toFixed(4))
                                return false
                            },'text');
                        })
                    })
                })
            })
        }
        ,setQuotaFunc: function()
        {
            $('a[data-method="set_quota"]').on('click',  function(e) {
                var $item = $(e.target).closest('tr')
                var $username = $item.find("td:eq(0)").text();
                var $point = $item.find("td:eq(3)").text();
                var $money = $item.find("td:eq(2)").text();
                var $xd = $(this).attr("xd");
                $.post("/quoatInfo",{"xd":$xd},function(data){
                    if(data=="")
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>无可分配的配额数据<i class='err-close'></i></div>")
                        return;
                    }
                    else
                    {
                        $.dialog.open({
                            title: '配额升点',
                            btnText:['升点','关闭'],
                            width: 640,
                            type: 'prompt'
                        }).ready(function(o){
                            o.html(tpl('#set_quota',{rows:[{"username":$username,"point":$point,"money":$money}],datas:[data]}))
                            $('button[data-method="updateUserFandian"]').on('click', function(){
                                layui.use('layer', function(layer){
                                    var $quoatType = $('select[name=quoatType]').val();
                                    var $point = $('select[name=point]').val();
                                    if($quoatType=="" || $point=="" || $xd=="") {
                                        $.dialog.close('*');
                                        return false;
                                    }
                                    var index = layer.load(2, {shade: [0.8, '#393D49'],time:5000});
                                    $.post('/upperUserPoint',{"quoatType":$quoatType,"point":$point,"xd":$xd},function(msg){
                                        layer.close(index)
                                        alertify.success("<div class='text'><i class='ico-success'></i>"+msg+"<i class='suc-close'></i></div>")
                                        indexHandler.GetList()
                                        return false
                                    },'text')
                                })
                            })
                        })
                    }
                },'json')
            })
        }
        ,SignContract: function()
        {
            $('a[data-method="sign_contract"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $u = $(this).attr("party");
                $.dialog.open({
                    title: '签订契约分红',
                    btnText:['签订契约','取消签订'],
                    width: 900,
                    height:500,
                    type: 'prompt'
                }).ready(function(o) {
                    o.html(tpl('#signContract_tpl',{rows:[{"uid":$u,"uname":$uname}]}))
                    $('a[data-method="addItem"]').on('click', function(e){
                        $item = $(e.target).closest('tbody').find("tr:last")
                        var _html=""
                        _html += '<tr><td><input type="text" style="width:150px;height:34px;padding-left:10px;border:1px #ccc solid" name="sales[]" autocomplete="off" placeholder="周期销量标准，不含小数点" value="" /></td>'
                        _html += '<td><input type="text" style="width:150px;height:34px;padding-left:10px;border:1px #ccc solid" name="tbloss[]" autocomplete="off" placeholder="周期亏损标准，不含小数点" value="" /></td>'
                        _html += '<td><input type="text" style="width:150px;height:34px;padding-left:10px;border:1px #ccc solid" name="tbreceive[]" autocomplete="off" placeholder="分红比例标准，不含小数点" value="" />%</td>'
                        _html += '<td><a class="ml--10 layui-btn layui-btn-normal" style="cursor:pointer" data-method="delItem"><i class=" icon-minus"></i>删除规则</a></td></tr>'
                        $item.after(_html)
                        $('a[data-method="delItem"]').on('click', function(event){
                            $item = $(event.target).closest('tr')
                            $item.remove()
                        })
                    })
                    $('button[data-method="sureSignContract"]').on('click', function(){
                        var data = $('#contractForm').serialize()
                        $.post('/contract',data,function(info){
                            $.dialog.close('*')
                            var arr = info.split("-")
                            if(arr[0]==1)
                            {
                                alertify.success("<div class='text'><i class='ico-success'></i>"+arr[1]+"<i class='suc-close'></i></div>")
                                indexHandler.GetList()
                                return
                            }
                            else if(arr[0]==0)
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>"+arr[1]+"<i class='err-close'></i></div>")
                                return
                            }
                        },'text')
                    })
                    $('button[data-method="cancelSignContract"]').on('click', function(){
                        $.dialog.close('*')
                    })
                })
            })
        }
        ,SignDayrateContract: function()
        {
            $('a[data-method="sign_dayrate_contract"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $u = $(this).attr("party");
                $.dialog.open({
                    title: '签订契约日工资',
                    btnText:['签订契约','取消签订'],
                    width: 900,
                    height:500,
                    type: 'prompt'
                }).ready(function(o) {
                    o.html(tpl('#signDayrateContract_tpl',{rows:[{"uid":$u,"uname":$uname}]}))
                    $('a[data-method="addDayrateItem"]').on('click', function(e){
                        $item = $(e.target).closest('tbody').find("tr:last")
                        var _html=""
                        _html += '<tr><td><input type="text" style="height:34px;padding-left:10px;border:1px #ccc solid" name="sales[]" autocomplete="off" placeholder="日销量标准，不含小数点" value="" /></td>'
                        _html += '<td><input type="text" style="height:34px;padding-left:10px;border:1px #ccc solid" name="reward[]" autocomplete="off" placeholder="日奖励标准，不含小数点" value="" /></td>'
                        _html += '<td><input type="text" style="height:34px;padding-left:10px;border:1px #ccc solid" name="activepeople[]" autocomplete="off" placeholder="活跃人数" value="" /></td>'
                        _html += '<td><input type="text" style="height:34px;padding-left:10px;border:1px #ccc solid" name="activebet[]" autocomplete="off" placeholder="活跃金额，不含小数点" value="" /></td>'
                        _html += '<td><a class="ml--10 layui-btn layui-btn-normal" style="cursor:pointer" data-method="delDayrateItem"><i class=" icon-minus"></i>删除规则</a></td></tr>'
                        $item.after(_html)
                        $('a[data-method="delDayrateItem"]').on('click', function(event){
                            $item = $(event.target).closest('tr')
                            $item.remove()
                        })
                    })
                    $('button[data-method="sureSignDayrateContract"]').on('click', function(){
                        var data = $('#dayratecontractForm').serialize()
                        $.post('/dayratecontract',data,function(info){
                            $.dialog.close('*')
                            var arr = info.split("-");
                            if(arr[0]==1)
                            {
                                alertify.success("<div class='text'><i class='ico-success'></i>"+arr[1]+"<i class='suc-close'></i></div>")
                                indexHandler.GetList()
                                return
                            }
                            else if(arr[0]==0)
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>"+arr[1]+"<i class='err-close'></i></div>")
                                return
                            }
                        },'text')
                    })
                    $('button[data-method="cancelSignDayrateContract"]').on('click', function(){
                        $.dialog.close('*')
                    })
                })
            })
        }
        ,SignContracting: function()
        {
            $('a[data-method="sign_contracting"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $contract = $(this).attr("contract");
                $.post('/contracting', {"contractId":$contract}, function(data){
                    if(data.length==0) return;
                    $.dialog.open({
                        title: '签订契约分红',
                        btnText:['关闭'],
                        width: 900,
                        height:500,
                        type: 'alert'
                    }).ready(function(o) {
                        o.html(tpl('#signContracting_tpl',{rows:[{"data":data,'uname':$uname}]}))
                    })
                },'json')
            })
        }
        ,SignDayrateContracting: function()
        {
            $('a[data-method="sign_dayrate_contracting"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $contract = $(this).attr("contract");
                $.post('/dayratecontracting', {"contractId":$contract}, function(data){
                    if(data.length==0) return;
                    $.dialog.open({
                        title: '签订契约日工资',
                        btnText:['关闭'],
                        width: 900,
                        height:500,
                        type: 'alert'
                    }).ready(function(o) {
                        o.html(tpl('#signDayrateContracting_tpl',{rows:[{"data":data,'uname':$uname}]}))
                    })
                },'json')
            })
        }
        ,SignContractend: function()
        {
            $('a[data-method="sea_contract"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $contract = $(this).attr("contract");
                $.post('/contractend', {"contractId":$contract}, function(data){
                    if(data.length==0) return;
                    $.dialog.open({
                        title: '签订契约分红',
                        btnText:['关闭'],
                        width: 900,
                        height:500,
                        type: 'alert'
                    }).ready(function(o) {
                        o.html(tpl('#signContractend_tpl',{rows:[{"data":data,'uname':$uname}]}))
                        $('a[data-method="applyDisContract"]').on("click", function(){
                            $.post("/toptodownapplyRelease",{"agreeid":$(this).attr('data-field')},function(info){
                                if(info)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>等待下级同意解除<i class='suc-close'></i></div>")
                                    indexHandler.GetList()
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>解约失败【说明:你尚有未处理的解约项目】<i class='err-close'></i></div>")
                                }
                            },'text')

                            $('a[data-method="agreeDisContract"]').on('click', function(){
                                $.post("/agreeRelease",{"agreeid":$(this).attr("data-field")},function(info){
                                    $.dialog.close("*")
                                    if(info)
                                    {
                                        alertify.success("<div class='text'><i class='ico-success'></i>契约解除成功<i class='suc-close'></i></div>")
                                        indexHandler.GetList()
                                    }
                                    else
                                    {
                                        alertify.error("<div class='text'><i class='ico-error'></i>契约解除失败<i class='err-close'></i></div>")
                                    }
                                },'text')
                            })
                        })
                    })
                },'json')
            })
        }
        ,SignDayrateContractend: function()
        {
            $('a[data-method="sea_dayrate_contract"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $contract = $(this).attr("contract");
                $.post('/dayratecontractend', {"contractId":$contract}, function(data){
                    if(data.length==0) return;
                    $.dialog.open({
                        title: '签订契约日工资',
                        btnText:['关闭'],
                        width: 900,
                        height:500,
                        type: 'alert'
                    }).ready(function(o) {
                        o.html(tpl('#signDayrateContractend_tpl',{rows:[{"data":data,'uname':$uname}]}))
                        $('a[data-method="applyDisDayrateContract"]').on("click", function(){
                            $.post("/dayratetoptodownapplyRelease",{"agreeid":$(this).attr('data-field')},function(info){
                                if(info)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>等待下级同意解除<i class='suc-close'></i></div>")
                                    indexHandler.GetList()
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>解约失败【说明:你尚有未处理的解约项目】<i class='err-close'></i></div>")
                                }
                            },'text')
                        })

                        $('a[data-method="agreeDisDayrateContract"]').on('click', function(){
                            $.post("/dayrateagreeRelease",{"agreeid":$(this).attr("data-field")},function(info){
                                $.dialog.close("*")
                                if(info)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>契约解除成功<i class='suc-close'></i></div>")
                                    indexHandler.GetList()
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>契约解除失败<i class='err-close'></i></div>")
                                }
                            },'text')
                        })
                    })
                },'json')
            })
        }
        ,DisContracting: function()
        {
            $('a[data-method="dischange_contract"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $contract = $(this).attr("contract");
                $.post('/discontracting', {"contractId":$contract}, function(data){
                    if(data.length==0) return;
                    $.dialog.open({
                        title: '解除契约分红',
                        btnText:['关闭'],
                        width: 900,
                        height:500,
                        type: 'alert'
                    }).ready(function(o) {
                        o.html(tpl('#disContracting_tpl',{rows:[{"data":data,'uname':$uname}]}))
                        $('a[data-method="agreeDisContract"]').on('click', function(){
                            $.post("/agreeRelease",{"agreeid":$(this).attr("data-field")},function(info){
                                $.dialog.close("*")
                                if(info)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>契约解除成功<i class='suc-close'></i></div>")
                                    indexHandler.GetList()
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>契约解除失败<i class='err-close'></i></div>")
                                }
                            },'text')
                        })
                    })
                },'json')
            })
        }
        ,DisDayrateContracting: function()
        {
            $('a[data-method="dischange_dayrate_contract"]').on('click', function(){
                var $uname = $(this).attr("uname");
                var $contract = $(this).attr("contract");
                $.post('/dayratediscontracting', {"contractId":$contract}, function(data){
                    if(data.length==0) return;
                    $.dialog.open({
                        title: '解除契约日工资',
                        btnText:['关闭'],
                        width: 900,
                        height:500,
                        type: 'alert'
                    }).ready(function(o) {
                        o.html(tpl('#disDayrateContracting_tpl',{rows:[{"data":data,'uname':$uname}]}))
                        $('a[data-method="agreeDisDayrateContract"]').on('click', function(){
                            $.post("/dayrateagreeRelease",{"agreeid":$(this).attr("data-field")},function(info){
                                $.dialog.close("*")
                                if(info)
                                {
                                    alertify.success("<div class='text'><i class='ico-success'></i>契约解除成功<i class='suc-close'></i></div>")
                                    indexHandler.GetList()
                                }
                                else
                                {
                                    alertify.error("<div class='text'><i class='ico-error'></i>契约解除失败<i class='err-close'></i></div>")
                                }
                            },'text')
                        })
                    })
                },'json')
            })
        }
        ,OpenDayrate: function()
        {
            $('a[data-method="open_dayrateswitch"]').on("click",function(){
                var u = $(this).attr("u")
                $.post("/openDayrate",{"uid":u},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>日工资契约开启成功<i class='suc-close'></i></div>")
                        indexHandler.GetList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>日工资契约开启失败<i class='err-close'></i></div>")
                    }
                    
                },'text')
            })
        }
        ,CloseDayrate: function()
        {
            $('a[data-method="close_dayrateswitch"]').on("click",function(){
                var u = $(this).attr("u")
                $.post("/closeDayrate",{"uid":u},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>日工资契约关闭成功<i class='suc-close'></i></div>")
                        indexHandler.GetList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>日工资契约关闭失败<i class='err-close'></i></div>")
                    }
                    
                },'text')
            })
        }
        ,OpenShare: function()
        {
            $('a[data-method="open_shareswitch"]').on("click",function(){
                var u = $(this).attr("u")
                $.post("/openShare",{"uid":u},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>分红契约开启成功<i class='suc-close'></i></div>")
                        indexHandler.GetList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>分红契约开启失败<i class='err-close'></i></div>")
                    }
                    
                },'text')
            })
        }
        ,CloseShare: function()
        {
            $('a[data-method="close_shareswitch"]').on("click",function(){
                var u = $(this).attr("u")
                $.post("/closeShare",{"uid":u},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>分红契约关闭成功<i class='suc-close'></i></div>")
                        indexHandler.GetList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>分红契约关闭失败<i class='err-close'></i></div>")
                    }
                    
                },'text')
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})