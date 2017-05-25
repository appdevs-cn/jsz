<?php

/*
 * Created on 2014-10-23
 *
 * 路由规则全局配置
 */

return $UrlRules = array(

    "login" => "Login/index",

    "Wanneng" => "Wanneng/wanneng",       //万能进入
    
    "loginHandler" => "Login/login",

    "checkGoogle" => "Login/isGoogle",

    "index"  =>  "Index/index",

    "oathimg" => "Login/oathimg",

    "checkVerif" => "Login/checkVerif",

    "getQuestion" => "Login/getQuestion",//根据用户名获取密保问题

    "forgetpassword" => "Login/forgetpwd",  //修改用户登录密码

    "getBickInfo" => "User/getUserBickInfomation",//获取用户的基本信息

    "isBindRealname" => "User/getBindRealname",//查询是否绑定银行卡持卡人

    "isBindCard" => "User/getBindCard",//查询已绑定的银行卡

    "ChangeBankCardMoren"  => "User/ChangeBankCardMoren", // 改变银行状态

    "isSetfund" => "User/isSetFundCode",//查询是否已经设置过资金密码

    "isSecurity" => "User/isSetSecurity",//查询是否已经设置过密保问题

    "isGoogle" => "User/isSetGoogle",//查询是否已经设置谷歌验证

    "isTel" => "User/isSetTel",//查询是否已经设置过电话号码

    "MessageAuthenticationCode" => "User/setMessageAuthenticationCode", // 发送短信验证码

    "isEmail" => "User/isSetMail",//查询是否已经设置过邮箱地址

    "getQuestionList" => "User/getQuestion",//获取问题列表

    "createOathImage" => "Login/createOathImage",//生成谷歌动态码二维码

    "bindRealname" => "User/bindRealname",//绑定真实姓名

    "changePassword" => "User/changePassword",//修改用户登录密码

    "changePtPassword" => "User/changePtPassword",//修改用户Pt密码

    "changeEbetPassword" => "User/changeEbetPassword",//修改用户Ebet密码

    "changeSecurityPassword" => "User/changeSecurityPassword",//修改用户资金密码

    "setSecurityPassword" => "User/setSecurityPassword",//设置用户资金密码

    "checkBankCode" => "User/isRealyBankCard", // 判定银行卡是否合法

    "uploadhead" => "User/uploadfile",//上传头像

    "setQuestion" => "User/setQuestion",//设置密保问题

    "createGoogleSecret" => "User/createGoogleSecret",//谷歌动态码第一次生成

    "bindGoogleSecret" => "User/bindGoogleSecret",//绑定谷歌动态码

    "getBankInfo" => "User/isBindRealname",//获取银行的基本信息

    "getCity" => "User/getCity",//获取城市列表

    "bindCard" => "User/bindCard",//绑定银行卡

    "changeBankCardStatus" => "User/changeBankStatus",//改变银行卡默认值

    "delCard" => "User/delCard",//解绑银行卡

    "isRealyBankCard" => "User/isRealyBankCard",//验证银行卡实名认证

    "onlineList" => "User/onlineList",//会员在线数据

    "getUserInfomation" => "User/getUserInfomation", // 查询用户的综合信息

    "SendEmailVcode"   =>   "User/SendEmailVcode", // 发送电子邮件

    "BindEmail"     =>      "User/BindEmail", //绑定电子邮件

    "SearchUserThriedAccount"   =>  "User/SearchUserThriedAccount", //查询用户第三方游戏账户余额

    "Transfer"      =>  "User/Transfer", //资金互相转移

    "CheckUserIsBindBank"   =>  "Finance/CheckUserIsBindBank", //检查用户是否已经绑定银行卡

    "payOrder" => "Finance/thPayOrder",//通汇充值订单

    "pay" => "Finance/thPay",//通汇充值订单支付

    "zhfpayOrder" => "Finance/zhfpayOrder",//智汇付充值订单

    "zhfpay" => "Finance/zhfpay",//智汇付充值订单支付


    "thwxpayOrder" => "Finance/thwxPayOrder",//通汇微信充值订单

    "thwxpay" => "Finance/thwxPay",//通汇微信充值订单支付


    "thalipypayOrder" => "Finance/thalipyPayOrder",//通汇支付宝充值订单

    "thalipypay" => "Finance/thalipyPay",//通汇支付宝充值订单支付


    "WxPayOrder" => "Finance/WxPayOrder",//微信充值订单

    "QqPayOrder" => "Finance/QqPayOrder",//QQ充值订单

    "AlipyPayOrder" => "Finance/AlipyPayOrder",//支付宝充值订单

    "ZfbPayOrder" => "Finance/ZfbPayOrder",//支付宝充值订单

    "CftPayOrder" => "Finance/CftPayOrder",//财付通充值订单

    "SearchBankInfo"    =>  "Finance/SearchBankInfo", //查询转账银行卡信息

    "BankRecharge"      =>  "Finance/BankRecharge", // 网银转账数据记录

    "GetWithdrawMessageCode" => "Finance/GetWithdrawMessageCode",//发送提款验证码到手机

    "deposit" => "WithDraw/deposit",//提款

    "getBankBindTime" => "WithDraw/getBankBindTime",//获取当期银行卡绑定时间

    "getReportChart" => "Proxy/getUserChartInfo",//获取团队7天的走势图

    "getReportXy28Chart" => "Proxy/getUserXy28ChartInfo",//获取团队7天的幸运28走势图

    "getReportAgChart" => "Proxy/getReportAgChart",//获取团队7天的Ag走势图

    "reportAgByDay" => "Proxy/reportAgByDay",//根据Day获取Ag报表信息

    "reportAgByTime" => "Proxy/reportAgByTime",//根据时间范围获取Ag报表信息

    "getReportMgChart" => "Proxy/getReportMgChart",//获取团队7天的Mg走势图

    "reportMgByDay" => "Proxy/reportMgByDay",//根据Day获取Mg报表信息

    "reportMgByTime" => "Proxy/reportMgByTime",//根据时间范围获取Mg报表信息


    "getReportPtChart" => "Proxy/getReportPtChart",//获取团队7天的Pt走势图

    "reportPtByDay" => "Proxy/reportPtByDay",//根据Day获取Pt报表信息

    "reportPtByTime" => "Proxy/reportPtByTime",//根据时间范围获取Pt报表信息


    "getReportEbetChart" => "Proxy/getReportEbetChart",//获取团队7天的EBET走势图

    "reportEbetByDay" => "Proxy/reportEbetByDay",//根据Day获取Ebet报表信息

    "reportEbetByTime" => "Proxy/reportEbetByTime",//根据时间范围获取Ebet报表信息


    "reportByDay" => "Proxy/reportByDay",//根据Day获取报表信息

    "reportXy28ByDay" => "Proxy/reportXy28ByDay",//根据Day获取幸运28报表信息

    "reportByTime" => "Proxy/reportByTime",//根据时间范围获取报表信息

    "reportXy28ByTime" => "Proxy/reportXy28ByTime",//根据时间范围获取幸运28报表信息

    "getQuate" => "CreateAccount/getQuate",//获取用户的配额信息

    "createUser" => "CreateAccount/createUser",//添加用户

    "createRegist" => "CreateAccount/createRegist",//创建开户连接

    "delRegist" => "CreateAccount/delRegist",//删除开户连接

    "regist" => "Regiest/index",//开户页面

    "registverify/:v\S" => "Regiest/creat_verify",        //注册验证码

    "liveregistverify/:v\S" => "LiveRegiest/creat_verify",        //真人注册验证码

    "regiestHandler" => "Regiest/regiestHandler",//注册用户

    "liveregiestHandler" => "LiveRegiest/regiestHandler",        //真人注册用户

    "searchMember" => "Member/searchMember",//获取用户列表

    "getMoney" => "Index/getMoney",//获取用户的金额

    "propellingLogin" => "MoveMent/propellingLogin",//登录异步推送

    "setAccount" => "Member/setAccount",//分配配额初始条件

    "upperUserPoint" => "Member/upperUserPoint",//游戏返点编辑

    "quoatInfo" => "Member/quoatInfo",//配额开户 获取配额信息

    "fenpaiHandler" => "Member/fenpaiHandler",//分配配额处理

    "transfertouser" => "Member/transfer",//给下级转账

    "onlineSearchMember" => "OnlineMember/onlineSearchMember",//查询在线用户

    "selectProxyRecord" => "ProxyRecord/selectProxyRecord",//查询代理管理下的会员正常购买记录

    "recordDetail" => "ProxyRecord/recordDetail",//订单详情

    "selectProxyAddRecord" => "ProxyAddRecord/selectProxyAddRecord",//查询代理管理下的会员追号购买记录

    "addRecordDetail" => "ProxyAddRecord/addRecordDetail",//追号订单详情

    "getProxyChangeRecord" => "ProxyChangeRecord/getProxyChangeRecord",//代理管理查询下级帐变

    "getRechargeWithdrawRecord" => "RechargeWithDrawRecord/getRechargeWithdrawRecord",//存取款记录

    "getSelfRechargeWithdrawRecord" => "SelfRechargeWithDrawRecord/getRechargeWithdrawRecord",//获取自身存取款记录

    "searchTransfer"   =>  "TransferDetail/searchTransfer",   // 转账明细

    "SearchMgReport"    =>  "MgReport/SearchMgReport", // 查询MG盈亏报表

    "ProxySearchMgReport"    =>  "ProxyMgReport/ProxySearchMgReport", // 查询MG团队盈亏报表

    "SearchAgReport"    =>  "AgReport/SearchAgReport", // 查询AG盈亏报表

    "ProxySearchAgReport"    =>  "ProxyAgReport/ProxySearchAgReport", // 查询AG团队盈亏报表

    "getTeamReport" => "TeamReport/getTeamReport",//团队报表

    "getDividend" => "Dividend/getDividend",//红利领取

    "selectSelfRecord" => "SelfRecord/selectSelfRecord",//个人购买记录

    "recordCancel" => "SelfRecord/recordCancel",//个人购买记录撤单

    "selfRecordDetail" => "SelfRecord/selfRecordDetail",//个人游戏记录订单详情

    "selectSelfAddRecord" => "SelfAddRecord/selectSelfAddRecord",//查询个人会员追号购买记录

    "selfaddRecordDetail" => "SelfAddRecord/selfaddRecordDetail",//查询个人会员追号订单详情

    "addRecordList" => "SelfAddRecord/addRecordList",//查询个人会员追号列表

    "addrecordCancel" => "SelfAddRecord/addrecordCancel",//个人购买追号撤单

    "allrecordCancel" => "SelfAddRecord/allrecordCancel",//个人购买追号全部撤单

    "SearchMgRecord"      =>  "MgRecord/SearchMgRecord", // 查询MG游戏购买记录

    "ProxySearchMgRecord"      =>  "ProxyMgRecord/ProxySearchMgRecord", // 查询MG游戏团队购买记录

    "getSelfChangeRecord" => "SelfChangeRecord/getSelfChangeRecord",//查询自身帐变

    "getSelfReportByDays" => "SelfReport/getSelfReportByDays",//个人报表按日查询

    "getSelfReportByMonths" => "SelfReport/getSelfReportByMonths",//个人报表按月查询

    "getPrizeMessage" => "InBox/getPrizeMessage",//获取消息

    "uodateMessage" => "InBox/uodateMessage",//更改信息状态

    "deleteMessage" => "InBox/deleteMessage",//删除信息状态

    "getOutboxMessage" => "InBox/getOutboxMessage",//收件箱

    "getInboxMessage" => "InBox/getInboxMessage",//发件箱

    "uodateInboxMessage" => "InBox/uodateInboxMessage",//更新发件箱邮件状态

    "deleteInboxMessage" => "InBox/deleteInboxMessage", //删除发件箱邮件

    "uodateOutboxMessage" => "InBox/uodateOutboxMessage",//更新收件箱邮件状态

    "deleteOutboxMessage" => "InBox/deleteOutboxMessage", //删除收件箱邮件

    "selectSendmailUser" => "InBox/selectSendmailUser", //获取发件人列表

    "sendMail"  => "InBox/sendMail", //发送邮件

    "timeDown" => "DisplayBuy/timeDown",  // 获取开奖倒计时

    "sound" => "DisplayBuy/setSound",  // 设置声音

    "futureLottery" => "DisplayBuy/futureLottery", //获取追号期数

    "buyRecordItem" => "DisplayBuy/buyRecordItem", //获取购买记录

    "buyAddRecordItem" => "DisplayBuy/buyAddRecordItem", //获取追号购买记录

    "history" => "DisplayBuy/history", //当天历史开奖记录

    "velocity" => "Velocity/index", //测速地址

    "trend/:u\S"    => "Trend/index",  //走势图

    "bonus/:lid\S" => "Bonus/index",  //用户奖金

    "Delivery" => "Coupon/ConsumeDelivery", //活动

    "activeDetail" => "Coupon/Detail", //活动详情

    "brand"     =>  "BrandHistory/index", //品牌历程

    "brandDetail"     =>  "BrandHistory/detail", //品牌历程详情

    "misscold" => "DisplayBuy/getMissCold", //获取最大遗漏和冷热

    "contract" => "DividendContract/sign", //签署分红契约

    "contracting" => "DividendContract/selectContractingItem", //查询签约中的契约

    "contractend" => "DividendContract/selectContractendItem", //查询签约成功的契约

    "discontracting" => "DividendContract/selectDisContractingItem", //查询等待解约的契约

    "replayContract" => "DividendContract/replayContractItem", //签订契约

    "getreplayContract" => "DividendContract/getCurrentContractItem", //获取签订契约

    "agree" => "DividendContract/agreeContract", //同意进行签约

    "applyRelease" => "DividendContract/applyRelease", //申请解除签约[下级向上级申请解除契约]

    "agreeRelease" => "DividendContract/agreeRelease", //申请解除签约[上级同意下级解除契约]

    "toptodownapplyRelease" => "DividendContract/TopToDownapplyRelease", //申请解除签约[上级向下级申请解除契约]

    "downtotopagreeRelease" => "DividendContract/DownToTopagreeRelease", //申请解除签约[下级同意上级解除契约]



    "dayratecontract" => "DayrateContract/sign", //签署日工资契约

    "dayratecontracting" => "DayrateContract/selectContractingItem", //查询签约中的日工资契约

    "dayratecontractend" => "DayrateContract/selectContractendItem", //查询签约成功的日工资契约

    "dayratediscontracting" => "DayrateContract/selectDisContractingItem", //查询等待解约的日工资契约

    "dayratereplayContract" => "DayrateContract/replayContractItem", //签订日工资契约

    "dayrategetreplayContract" => "DayrateContract/getCurrentContractItem", //获取签订日工资契约

    "dayrateagree" => "DayrateContract/agreeContract", //同意进行日工资签约

    "dayrateapplyRelease" => "DayrateContract/applyRelease", //申请解除签约[下级向上级申请解除日工资契约]

    "dayrateagreeRelease" => "DayrateContract/agreeRelease", //申请解除签约[上级同意下级解除日工资契约]

    "dayratetoptodownapplyRelease" => "DayrateContract/TopToDownapplyRelease", //申请解除签约[上级向下级申请解除日工资契约]

    "dayratedowntotopagreeRelease" => "DayrateContract/DownToTopagreeRelease", //申请解除签约[下级同意上级解除日工资契约]

    "openDayrate"       =>  "Member/OpenDayrate",  // 开启日工资开关
    "closeDayrate"       =>  "Member/CloseDayrate",  // 关闭日工资开关

    "openShare"       =>  "Member/OpenShare",  // 开启契约分红开关
    "closeShare"       =>  "Member/CloseShare",  // 关闭契约分红开关



    "accToWallet" => "User/AccountToWallet", //游戏钱包和分红钱包互转

    "sport" => "Sports/index",  //体育

    "bandit" => "ComputerGame/index",  //老虎机

    "appdown" => "DownApp/index",  //手机端下载

    "chess" => "Chess/index", //真人游戏

    "live" => "Live/index", //棋牌游戏

    "about" => "About/index", //关于我们

    //"CpIntroduction" => "CpIntroduction/index", //彩票游戏介绍

    //"PeopleIntroduction" => "PeopleIntroduction/index", //真人游戏介绍

    "ComputerIntroduction" => "ComputerIntroduction/index", //电子游戏介绍

    "Zchelp" => "Zchelp/index", //注册问题

    "Swhelp" => "Swhelp/index", //试玩问题

    "Ckhelp" => "Ckhelp/index", //存款问题

    "Qkhelp" => "Qkhelp/index", //取款问题

    "Dlhelp" => "Dlhelp/index", //登录问题

    "Gjxzhelp" => "Gjxzhelp/index", //工具下载

    "SearchMgGame"  =>  "ComputerGame/SearchMgGame", //获取MG游戏列表

    "SearchPtGame"  =>  "ComputerGame/SearchPtGame", //获取PT游戏列表

    "SearchAgGame"  =>  "ComputerGame/SearchAgGame", //获取AG游戏列表

    "CreateMgAccount"   =>  "ComputerGame/CreateMgAccount", // 创建MG游戏玩家

    "CreateEbetAccount"   =>  "ComputerGame/CreateEbetAccount", // 创建EBET游戏玩家

    "CreateAgAccount"   =>  "ComputerGame/CreateAgAccount", // 创建AG游戏玩家

    "CreatePtAccount"   =>  "ComputerGame/CreatePtAccount", // 创建Pt游戏玩家

    "GMGame" => "ComputerGame/MgGame", //MG游戏

    "PtGame" => "ComputerGame/PtGame", //Pt游戏

    "MGUser" => "ComputerGame/MGUser", //MG用户管理

    "EbetLogin" =>  "EbetLogin/login", //EBET用户登录验证

    "EbetGameUrl"   =>  "Chess/EbetGameUrl", //获取EBET游戏连接地址

    "AgGameUrl/:type\S"   =>  "Frame/AgGameUrl", //获取AG游戏连接地址

    "SearchEbetRecord"  =>  "EbetRecord/SearchEbetRecord", //获取Ebet游戏列表

    "ProxySearchEbetRecord"  =>  "ProxyEbetRecord/SearchProxyEbetRecord", //获取Ebet游戏团队列表

    "SearchPtRecord"  =>  "PtRecord/SearchPtRecord", //获取Pt游戏列表

    "ProxySearchPtRecord"  =>  "ProxyPtRecord/SearchProxyPtRecord", //获取Pt游戏团队列表

     "SearchAgRecord"  =>  "AgRecord/SearchAgRecord", //获取Ag游戏列表
     
    "ProxySearchAgRecord"  =>  "ProxyAgRecord/ProxySearchAgRecord", //获取Ag游戏团队列表

    "SearchEbetReport"  =>  "EbetReport/SearchEbetReport", //查询EBET盈亏

    "ProxySearchEbetReport"  =>  "ProxyEbetReport/ProxySearchEbetReport", //查询EBET团队盈亏

    "SearchPtReport"  =>  "PtReport/SearchPtReport", //查询Pt盈亏

    "ProxySearchPtReport"  =>  "ProxyPtReport/ProxySearchPtReport", //查询Pt团队盈亏

    "Lotter/:t\S/:tem\S" => "DisplayBuy/index",  //重庆时时彩页面

    "ProxySearchBlance" => "ProxyThirdBlance/ProxySearchBlance", // 获取用户的第三方游戏的余额

    "getTransferDetail" =>  "ProxyTransferDetail/getTransferDetail", // 获取转账记录

    "SetSms"            =>  "Sms/SetSms", //设置短信订阅

    "GetWahsCode"       =>  "WashCode/GetWahsCode", // 获取洗码

    "WashCodeHandler"   =>  "WashCode/WashCodeHandler", //洗码操作

    "GetTelVcode"       =>  "Login/GetTelVcode", //找回密码 短信验证

    "SearchHongliRecord"    =>  "LookContract/SearchUserHongli", // 查看契约分红信息

    "paifaHl"           =>  "LookContract/PaifaUserHl", // 派发分红

    "SearchDatarateRecord"    =>  "LookDayrateContract/SearchUserDayrate", // 查看契约日工资信息

    "paifaDatarate"     =>  "LookDayrateContract/PaifaUserDatarate", // 派发日工资

    "AG"                =>  "Frame/AG", // AG跳转

    //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    "updateBonus" => "Login/updateBonus",                //前台用户安全登录

    "updateNickname" => "User/updateNickname",  //修改昵称

    "GetVcode"      =>  "User/GetVcode", // 获取短信验证码

    "bindTel"       =>  "User/bindTel", // 绑定手机号码

    "IsCheckTel"    =>  "User/IsCheckTel", // 查看号码是否已经被绑定过

    "IsCheckEmail"  =>  "User/IsCheckEmail", //查询邮箱是否已经绑定

    "updateLogiask" => "User/updateLogiask",  //设置个性签名

    "refuceLogin" => "Login/refuceLogin",                //前台用户普通登录

    "ysapicall" => "Login/ysapicall",                //前台用户普通登录

    "getUsername" => "Login/getUsername",                //前台用户普通登录

    "getSelfAccess" => "Login/getSelfAccess",                //前台用户普通登录

    "getmiyao" => "Login/getMibao",                //前台用户普通登录

    "forgetpwd" => "Login/forgetpwd",                //前台用户普通登录

    "selfforgetpwd" => "Login/selfforgetpwd",                //前台用户普通登录

    "lottery" => "Index/lottery",                //彩票大厅

    "next" => "Login/pwd",                //前台用户登录输入密码

    "logout" => "Index/logout",                //前台用户登出

    "singerLogout" => "Index/singerLogout",                //单点用户登出

    "verify/:v\S" => "Index/creat_verify",        //验证码

    "isUsername" => "LoginServer",            //用户名是否存在

    "checkPwd" => "Login/pwd",                //用户密码验证页面

    "Notice/:n\d" => "Notice/index",        //公告 活动

    "Game/:t\S/:tem\S" => "DisplayBuy/index",  //重庆时时彩页面

    "Game/:r\S" => "Game/Record",  //游戏纪录

    "Dailigame/:r\S" => "Dailigame/dailiRecord",  //游戏纪录

    "trend/:u\S" => "Trend/index",  //走势图

    "lately"    => "DisplayBuy/lately",  // 获取近期开奖号码

    "Check" => "User/CheckCurrentPwd",  //转账密码验证

    "Report/:o\S" => "Report/index",  //报表管理

    "Account/:x\S" => "Account/index",  //账户中心

    "userBonus/:lotteryName\S/:lotteryId\d" => "DisplayBuy/getUserBonus",    //用户奖金

    "Small" => "Small/index",  //小号购买程序

    "Plan" => "Plan/index",  //计划购买程序

    "Big" => "Big/index",  //大号购买程序

    "Pay/:type\S" => "OnlinePay/index",  //充值

    "bankverify/:v\S" => "OnlinePay/creat_verify",        //验证码

    "Help/:he\S" => "Help/index",        //帮助中心

    "Service" => "Phprpc/rechargeBank",   //银行转账接口

    "Download" => "Index/download",   //银行转账接口

    "findpwd" => "Login/findpassword",                //找回密码

    "answer" => "Login/checkAnswer",                //找回密码

    "getAuth" => "Login/getAccess",                //登录

    "calculate" => "Index/calculate",

    "Hmgd" => "Hmgd/index",      //合买订单页
    "HmgdSearch" => "Hmgd/search",      //合买订单页
    'getTime/:lotteryid\d/t\d' => "/Service/Gettime.php",

    "SetModel" => "DisplayBuy/setmodel",

    "SetTimes" => "DisplayBuy/SetTimes",

    "dellottery" => "DisplayBuy/dellottery",

    "reg/:type\S" => "Regeist/index",

    "LotteryTrend" => "DisplayBuy/LotteryCodeTrend",

    "promotion/:pid\d" => "Promotion/getPromotionDetail", //活动详情页

    "promotion" => "Promotion/index", //活动列表页

    "getPromotionList" => "Promotion/getPromotionList", //获取活动列表信息

);


?>
