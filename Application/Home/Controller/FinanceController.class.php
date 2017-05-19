<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/8
 * Time: 下午2:16
 */

namespace Home\Controller;


use Home\Model\UserModel as User;
use Home\Model\UserBankModel as UserBank;
use Home\Model\TikuanConfigModel as TikuanConfig;
use Home\Model\TongHuiModel as TongHui;
use Home\Model\ZhfChartModel as Zhf;
use Home\Model\TongHuiWxModel as TongHuiWx;
use Home\Model\TongHuiAlipyModel as TongHuiAlipy;
use Home\Model\WeChartModel as WeChart;
use Home\Model\QqChartModel as QqChart;
use Home\Model\AlipyChartModel as AlipyChart;
use Home\Model\AccordingTimeModel as AccordingTime;
use Home\Model\UserQuestionModel as UserQuestion;
use Home\Model\MgGameModel as MgGame;
use Home\Model\EbetGameModel as EbetGame;
use Home\Model\PtGameModel as PtGame;
use Home\Model\AgGameModel as AgGame;

class FinanceController extends CommonController
{
    public function index()
    {
        $this->type = I("get.type");
        $this->menu = "finance";
        $this->managermenu = 'finance';
        $User = new User();
        $this->money = $User->getUserAccount();
        
        $AccordingTime = new AccordingTime('','','');
        //所需要的消费量
        $report = $AccordingTime->getTodayReport();
        $this->needXF = ($report['rechargeAmount']=="") ? 0 : $report['rechargeAmount']*0.3;
        //提款初始参数
        $UserBank = new UserBank();
        $this->bankList = $UserBank->getUserMrBank();
        $TikuanConfig = new TikuanConfig('','','');
        $this->isStatus = $TikuanConfig->getStatus();
        $this->starttime = $TikuanConfig->getStarttime();
        $this->endtime = $TikuanConfig->getEndtime();
        $this->WITHDRAWMIN = $TikuanConfig->getMinmoney();
        $this->WITHDRAWMAX = $TikuanConfig->getMaxmoney();
        $this->appKey = md5(session("SESSION_NAME").time());
        session("WITHDRAWAPPKEY",$this->appKey);

        // 获取用户的密保问题
        $UserQuestion = new UserQuestion();
        $UserQuestion->data = array("userid"=>session("SESSION_ID"));
        $this->question = $UserQuestion->getQuestion();

        // 查询该用户总的充值金额
        $rechargeAmount = M("according_time")->where(array("userid"=>session("SESSION_ID")))->sum('rechargeAmount');
        $THIRDMONEY = C("THIRDMONEY");

        // 查询用户的会员等级[当会员等级大于等于1级的时候才显示第三方充值]
        $level = M("user")->where(array("id"=>session("SESSION_ID")))->getField("level");
        if($level>=1 || bccomp($rechargeAmount,$THIRDMONEY*100000,4)!=-1)
        {
            $this->showThirdRecharge = true;
        }
        else
        {
            $this->showThirdRecharge = false;
        }
        if($this->type==3)
        {
            // 获取MG游戏账户余额
            $MgGame = new MgGame();
            $isHave = M("mg_user")->where(array("mg_username"=>"jsz_".session("SESSION_NAME")))->find();
            if(!empty($isHave))
            {
                $mgAccount = $MgGame->searchUserBalance(session('SESSION_NAME'));
                $this->mgAccount = number_format($mgAccount,2,",",".");
            }
            else
            {
                $this->mgAccount = "0.00";
            }

            // 获取Ebet游戏的账户余额
            $EbetGame = new EbetGame();
            $isEbetHave = M("ebet_user")->where(array("ebet_username"=>"ebet_".session("SESSION_NAME")))->find();
            if(!empty($isEbetHave))
            {
                $ebetAccount = $EbetGame->searchUserBalance(session('SESSION_NAME'));
                $this->ebetAccount = number_format($ebetAccount,2,",",".");
            }
            else
            {
                $this->ebetAccount = "0.00";
            }

            // 获取PT游戏账户余额
            $PtGame = new PtGame();
            $isHave = M("pt_user")->where(array("EBJSZ_username"=>"EBJSZ_".session("SESSION_NAME")))->find();
            if(!empty($isHave))
            {
                $ptAccount = $PtGame->SearchUserBlance(session('SESSION_NAME'));
                $this->ptAccount = number_format($ptAccount,2,",",".");
            }
            else
            {
                $this->ptAccount = "0.00";
            }

            // 获取AG游戏账户余额
            $AgGame = new AgGame();
            $isHave = M("ag_user")->where(array("ag_username"=>"jsz".session("SESSION_NAME")))->find();
            if(!empty($isHave))
            {
                $agAccount = $AgGame->SearchUserBlance(session('SESSION_NAME'));
                $this->agAccount = number_format($agAccount,2,",",".");
            }
            else
            {
                $this->agAccount = "0.00";
            }
        }

        $this->display();
    }

    // 检查用户是否已经绑定过银行卡
    public function CheckUserIsBindBank()
    {
        $UserBank = new UserBank();
        echo (empty($UserBank->checkUserBank())) ? false : true;
    }

    /**
     * 显示订单详情[通汇支付]
     */
    public function thPayOrder()
    {
        $bank = I("post.bank");
        $money = I("post.money");
        if(empty($money)) exit();
        $ThOrder = TongHui::getInstance($money,$bank);
        echo json_encode($ThOrder->showOrder());
    }

    /**
     * 通汇支付
     */
    public function thPay()
    {
        $ThOrder = TongHui::getInstance();
        $this->data = $ThOrder->payOrder($_POST);
        $this->display("thPayOrderHandler");
    }

    /**
     * 显示订单详情[智汇付支付]
     */
    public function zhfpayOrder()
    {
        $bank = I("post.bank");
        $money = I("post.money");
        if(empty($money)) exit();
        $ZhfOrder = Zhf::getInstance($money,$bank);
        echo json_encode($ZhfOrder->showOrder());
    }

    /**
     * 通汇支付
     */
    public function zhfpay()
    {
        $ZhfOrder = Zhf::getInstance();
        $this->data = $ZhfOrder->payOrder($_POST);
        $this->display("zhfPayOrderHandler");
    }

    /**
     * 显示订单详情[通汇微信支付]
     */
    public function thwxPayOrder()
    {
        $bank = I("post.bank");
        $money = I("post.money");
        if(empty($money)) exit();
        $ThOrder = TongHuiWx::getInstance($money,$bank);
        echo json_encode($ThOrder->showOrder());
    }

    /**
     * 通汇微信支付
     */
    public function thwxPay()
    {
        $ThOrder = TongHuiWx::getInstance();
        $this->data = $ThOrder->payOrder($_POST);
        $this->display("thwxPayOrderHandler");
    }


    /**
     * 显示订单详情[通汇支付宝支付]
     */
    public function thalipyPayOrder()
    {
        $bank = I("post.bank");
        $money = I("post.money");
        if(empty($money)) exit();
        $ThOrder = TongHuiAlipy::getInstance($money,$bank);
        echo json_encode($ThOrder->showOrder());
    }

    /**
     * 通汇支付宝支付
     */
    public function thalipyPay()
    {
        $ThOrder = TongHuiAlipy::getInstance();
        $this->data = $ThOrder->payOrder($_POST);
        $this->display("thalipyPayOrderHandler");
    }

    /**
     * 显示订单详情[微信支付]
     */
    public function WxPayOrder()
    {
        $money = I("post.money");
        if(empty($money)) exit();
        $WeChart = WeChart::getInstance($money);
        echo $WeChart->showOrder();
    }

    /**
     * 显示订单详情[QQ支付]
     */
    public function QqPayOrder()
    {
        $money = I("post.money");
        if(empty($money)) exit();
        $QqChart = QqChart::getInstance($money);
        echo $QqChart->showOrder();
    }

    /**
     * 显示订单详情[支付宝]
     */
    public function AlipyPayOrder()
    {
        $money = I("post.money");
        if(empty($money)) exit();
        $AlipyChart = AlipyChart::getInstance($money);
        echo $AlipyChart->showOrder();
    }

    // 查询转账银行卡信息
    public function SearchBankInfo()
    {
        $bankNameArray = C("BANKNAME");
        $BankImg = C("bankimg");
        $result['banklist'] = $bankNameArray;
        $result['bankimg'] = $BankImg;
        echo json_encode($result);
    }

    public static function sendWebSocketMsg($content)
    {
        $push_api_url = "http://".C("TUISONG_HOST").":2121/";
        $post_data = $content;
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    //网银转账 记录
    public function BankRecharge()
    {
        $money = I("post.money");
        $depositor = I("post.depositor");
        $bankid = I("post.bankid");
        if(empty($money) || empty($depositor) || empty($bankid))
        {
            echo false;
            exit();
        }
        $BankInfo = M("bank_info");
        $res = $BankInfo->where(array("status"=>1))->find();

        if(bccomp($money*100000,$res['money'],4)==1)
        {
            // 发送消息到后台
            // 查询后台管理用户
            $adminUser = M("user")->where("group_id=1 or group_id=6 or group_id=7 or group_id=8 or group_id=9")->field("id")->select();
            $msg = "收款卡限额已到，请更换收款卡";
            $send = $msg."-1-".time();
            foreach($adminUser as $admin)
            {
                $content = array('type'=>"inbox","to"=>$admin['id'],'content'=>$send);
                $result = self::sendWebSocketMsg($content);
            }
            echo false;
            exit();
        }
        $RechargeBank = M("recharge_bank");
        $bankNameArray = C("BANKNAME");
        $time = time();
        $fuyan = rand(1234,9999).session("SESSION_ID").substr($time,-4).rand(100,999);
        $insertId = $RechargeBank->add(array(
            "userid" => session("SESSION_ID"),
            "parent_path" => session("SESSION_PATH"),
            "username" => session("SESSION_NAME"),
            "money" => $money*100000,
            "submit_time" => time(),
            "bank_name" => $bankNameArray[$bankid],
            "ip" => ip2long(get_client_ip()),
            "dateline" => 0,
            "receive" => 2,
            "fukuanname" => rtrim($depositor),
            "fuyan" => $fuyan
        ));
        if($insertId)
        {
            $bankNameArray = C("BANKNAME");
            $bankurl = C("bankurl");
            $res['bankname'] = $bankNameArray[$res['bankid']];
            $BankImg = C("bankimg");
            $res['bankimage'] = $BankImg[$res['bankid']];
            $res['fuyan'] = $fuyan;
            $res['bankurl'] = $bankurl[$bankid];
            echo json_encode($res);
        }
        else
        {
            echo json_encode($res);
        }
    }

    // 发送提款验证码到手机
    public function GetWithdrawMessageCode()
    {
        $tel = I("post.tel","rtrim");
        if($tel=="")
        {
            echo false;
            exit();
        }

        // 查询电话号码是否存在
        $result = M("user_extend")->where(array("userid"=>session("SESSION_ID"),"tel"=>$tel))->find();
        if(empty($result))
        {
            echo false;
            exit();
        }

        $Appkey = C("MessageCodeKey");
        $TemplateId = C("TemplateId");
        $param = substr(time(),-6);
        $key = md5("WithDrawVcode".session("SESSION_NAME"));
        session($key,$param);
        $url = "http://v1.avatardata.cn/Sms/Send?key={$Appkey}&mobile={$tel}&templateId={$TemplateId}&param={$param}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result,true);
        echo $res['success'];
    }
}