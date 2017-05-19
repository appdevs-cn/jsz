<?php
namespace Home\Controller;

use Home\Controller\CommonController;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class WashCodeController extends CommonController
{
    public function index()
    {
        $this->display();
    }

    // 获取用户在每个平台的投注总和
    public function GetWahsCode()
    {
        $washcode = M("washcode");
        $uid = session("SESSION_ID");
        // 查询AG一共洗码的金额
        $washAgBet = $washcode->where(array("userid"=>$uid,"type"=>1))->sum('washbet');
        // 查询MG一共洗码的金额
        $washMgBet = $washcode->where(array("userid"=>$uid,"type"=>2))->sum('washbet');
        // 查询PT一共洗码的金额
        $washPtBet = $washcode->where(array("userid"=>$uid,"type"=>3))->sum('washbet');
        // 查询EBET一共洗码的金额
        $washEbetBet = $washcode->where(array("userid"=>$uid,"type"=>4))->sum('washbet');

        // 获取AG总的投注量
        $totalAgBet = $this->SearchAgRecord();
        // 获取MG总的投注量
        $totalMgBet = $this->SearchMgRecord();
        // 获取PT总的投注量
        $totalPtBet = $this->SearchPtRecord();
        // 获取EBET总的投注量
        $totalEbetBet = $this->SearchEbetRecord();

        // 可洗码的投注量
        $validAgBet = $totalAgBet-$washAgBet;
        $validMgBet = $totalMgBet-$washMgBet;
        $validPtBet = $totalPtBet-$washPtBet;
        $validEbetBet = $totalEbetBet-$washEbetBet;

        // 洗码金额计算
        $validAgBonus = $validAgBet*0.008;
        $validMgBonus = $validMgBet*0.008;
        $validPtBonus = $validPtBet*0.008;
        $validEbetBonus = $validEbetBet*0.008;

        // 总的洗码奖励
        $totalBonus = $validAgBonus+$validMgBonus+$validPtBonus+$validEbetBonus;

        $xReturn = array(
            1 => array(
                "third"=>"AG",
                "totalBet" => $totalAgBet,
                "validBet" => $validAgBet,
                "validBonus" => $validAgBonus,
                "rate"  => "0.8%"
            ),
            2 => array(
                "third"=>"MG",
                "totalBet" => $totalMgBet,
                "validBet" => $validMgBet,
                "validBonus" => $validMgBonus,
                "rate"  => "0.8%"
            ),
            3 => array(
                "third"=>"PT",
                "totalBet" => $totalPtBet,
                "validBet" => $validPtBet,
                "validBonus" => $validPtBonus,
                "rate"  => "0.8%"
            ),
            4 => array(
                "third"=>"EBET",
                "totalBet" => $totalEbetBet,
                "validBet" => $validEbetBet,
                "validBonus" => $validEbetBonus,
                "rate"  => "0.8%"
            ),
            5 => array(
                "totalBonus" => $totalBonus
            )
        );
        echo json_encode($xReturn);
    }

    // 洗码兑换
    public function WashCodeHandler()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $userModel = M("user");
        $accountChange = M("accounts_change");
        $userModel->startTrans();
        try
        {   
            $washcode = M("washcode");
            $uid = session("SESSION_ID");
            // 查询AG一共洗码的金额
            $washAgBet = $washcode->where(array("userid"=>$uid,"type"=>1))->sum('washbet');
            // 查询MG一共洗码的金额
            $washMgBet = $washcode->where(array("userid"=>$uid,"type"=>2))->sum('washbet');
            // 查询PT一共洗码的金额
            $washPtBet = $washcode->where(array("userid"=>$uid,"type"=>3))->sum('washbet');
            // 查询EBET一共洗码的金额
            $washEbetBet = $washcode->where(array("userid"=>$uid,"type"=>4))->sum('washbet');

            // 获取AG总的投注量
            $totalAgBet = $this->SearchAgRecord();
            // 获取MG总的投注量
            $totalMgBet = $this->SearchMgRecord();
            // 获取PT总的投注量
            $totalPtBet = $this->SearchPtRecord();
            // 获取EBET总的投注量
            $totalEbetBet = $this->SearchEbetRecord();

            // 可洗码的投注量
            $validAgBet = $totalAgBet-$washAgBet;
            $validMgBet = $totalMgBet-$washMgBet;
            $validPtBet = $totalPtBet-$washPtBet;
            $validEbetBet = $totalEbetBet-$washEbetBet;

            // 洗码金额计算
            $validAgBonus = $validAgBet*0.008;
            $validMgBonus = $validMgBet*0.008;
            $validPtBonus = $validPtBet*0.008;
            $validEbetBonus = $validEbetBet*0.008;

            // 总的洗码奖励
            $totalBonus = $validAgBonus+$validMgBonus+$validPtBonus+$validEbetBonus;

            if(bccomp($totalBonus,100,4)==-1)
            {
                echo "洗码金额100以上才能进行";
                exit();
            }

            $userInfo = $userModel->db(0)->lock(true)->where(array("id"=>$uid))->find();

            $currentMoney = $userInfo['cur_account'];
            $changeMoney = $totalBonus*100000;
            $afterMoney =  $currentMoney+$changeMoney;

            // 更新用户金额
            $userModel->db(0)->where(array("id"=>$uid))->save(array("cur_account"=>$afterMoney));

            //账变
            $_change_data = array(
                "accounts_type" => 46,  
                "buy_record_id" => 0,
                "change_amount" => $changeMoney,
                "userid" => $uid,
                "username" => session("SESSION_NAME"),
                "parent_id" => session("SESSION_PARENTID"),
                "parent_path" => session("SESSION_PATH"),
                "cur_account" => $afterMoney,
                "wallet_account" => $userInfo['wallet_account'],
                "serial_number" => 0,
                "runner_id" => $_uid,
                "runner_name" => session("SESSION_NAME"),
                "change_time" => time(),
                "is_addto" => 0,
                "remark"	=> "获取洗码奖励".$totalBonus
            );
            $_account_change_id = $accountChange->db(0)->add($_change_data);
            //更新该条账变的账变编号
            $achange_num = strtoupper($_xDe->encode($_account_change_id));
            $accountChange->db(0)->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));

            // 增加资金变化
            $FoundChange = new FoundChange();
            $FoundChangeData = array();
            $FoundChangeData['userid'] = $uid;
            $FoundChangeData['username'] = session("SESSION_NAME");
            $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
            $FoundChangeData['parent_path'] = session("SESSION_PATH");
            $FoundChangeData['beforeMoney'] = intval($currentMoney);
            $FoundChangeData['money'] = intval($changeMoney);
            $FoundChangeData['afterMoney'] = intval($afterMoney);
            $FoundChangeData['time'] = time();
            $FoundChangeData['remark'] = "获取洗码奖励".$totalBonus;
            $FoundChange->add($FoundChangeData);

            $userModel->commit();

            // 进入报表
            _team_report(array($uid,0,0,0,0,0,$changeMoney,0));

            // 更新洗码信息表
            $nowtime = time();
            if(bccomp($validAgBonus,0,4)==1)
            {
                $washcode->add(array(
                    "userid"=>$uid,
                    "parent_id" => session("SESSION_PARENTID"),
                    "parent_path" => session("SESSION_PATH"),
                    "type" => 1,
                    "washbet" => $validAgBet,
                    "washbonus" => $validAgBonus,
                    "washtime" => $nowtime
                ));
            }

            if(bccomp($validMgBonus,0,4)==1)
            {
                $washcode->add(array(
                    "userid"=>$uid,
                    "parent_id" => session("SESSION_PARENTID"),
                    "parent_path" => session("SESSION_PATH"),
                    "type" => 2,
                    "washbet" => $validMgBet,
                    "washbonus" => $validMgBonus,
                    "washtime" => $nowtime
                ));
            }

            if(bccomp($validPtBonus,0,4)==1)
            {
                $washcode->add(array(
                    "userid"=>$uid,
                    "parent_id" => session("SESSION_PARENTID"),
                    "parent_path" => session("SESSION_PATH"),
                    "type" => 3,
                    "washbet" => $validPtBet,
                    "washbonus" => $validPtBonus,
                    "washtime" => $nowtime
                ));
            }

            if(bccomp($validEbetBonus,0,4)==1)
            {
                $washcode->add(array(
                    "userid"=>$uid,
                    "parent_id" => session("SESSION_PARENTID"),
                    "parent_path" => session("SESSION_PATH"),
                    "type" => 4,
                    "washbet" => $validEbetBet,
                    "washbonus" => $validEbetBonus,
                    "washtime" => $nowtime
                ));
            }

            echo "洗码领取成功";
        }catch(Exception $ex)
        {
            $userModel->rollback();
            echo "洗码领取失败";
        }
    }

    /////////////////////////////////////////AG投注信息/////////////////////////////////////

    public function SearchAgRecord()
    {
        $username = "jsz".session("SESSION_NAME");
        $username = strtolower($username);
        $startTimeStr = "2017-03-01 00:00:00";
        $endTimeStr = date("Y-m-d H:i:s", mktime(59, 59, 59, date("m") , date("d"), date("Y")));

        $timestamp = time();

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::signAg($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => array(
                    "startDate" => $startTimeStr
                    ,"endDate" => $endTimeStr
                    ,"playerName" => $username
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        $result = array();
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'], true);
            $list = $result['betHistories'];
            $validBetAmount = 0;
            foreach($list as $itemorder)
            {
                $order = json_decode($itemorder,true);
                $validBetAmount = $validBetAmount + $order['betAmount'];
            }
            return $validBetAmount;
        }
        else
        {
            return "0.00";
        }
    }

    // 生成签名函数
    public static function signAg($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('AG_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToAg($SentData)
    {
        $push_api_url = "http://mainapi.ebet2017.com:8070/GlobalSystemAPI/api/request";
        $post_data = json_encode($SentData);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    /////////////////////////////////////////MG投注信息/////////////////////////////////////
    public function SearchMgRecord()
    {
        $parameters['username'] = strtolower("jsz_".session("SESSION_NAME"));
        $stime = "2017-03-01 00:00:00";
        $etime = date("Y-m-d H:i:s", mktime(59, 59, 59, date("m") , date("d"), date("Y")));

        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;

        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::signMg($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => $parameters
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $templist = json_decode($mReturn, true);
        $tlist = json_decode($templist['result'],true);
        $list = $tlist['betHistories'];
        $totalBet = 0;
        foreach($list as $item)
        {
            if($item['transType']=="bet")
                $totalBet = $totalBet + $item['amnt'];
        }
        return $totalBet;
    }

    // 生成签名函数
    public static function signMg($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('MG_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToMg($SentData)
    {
        $push_api_url = "http://mainapi.ebet2017.com:8070/GlobalSystemAPI/api/request";
        $post_data = json_encode($SentData);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    /////////////////////////////////////////PT投注信息/////////////////////////////////////
    public function SearchPtRecord()
    {
        $stime = "2017-03-01 00:00:00";
        $etime = date("Y-m-d H:i:s", mktime(59, 59, 59, date("m") , date("d"), date("Y")));
        
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::signPt($plaintext);

        $ip = get_client_ip();
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => array(
                    "membercode" => "EBJSZ_".strtoupper(session("SESSION_NAME"))
                    ,"startDate" => $stime
                    ,"endDate" => $etime
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        $result = array();
        if($xReturn['status']!=200)
        {
            return "0.00";
            exit();
        }
        $list = json_decode($xReturn['result'],true);
        $list = $list['betHistories'];
        $totalBet = 0;
        foreach($list as $item)
        {
            $totalBet = $totalBet + $item['bet'];
        }

        return $totalBet;
    }

    // 生成签名函数
    public static function signPt($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('PT_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToPt($SentData)
    {
        $push_api_url = "http://mainapi.ebet2017.com:8070/GlobalSystemAPI/api/request";
        $post_data = json_encode($SentData);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Accept: application/json';
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    /////////////////////////////////////////EBET投注信息/////////////////////////////////////

    public function SearchEbetRecord()
    {
        $username = "ebet_".session("SESSION_NAME");
        $username = strtolower($username);
        $stime = "2017-03-01 00:00:00";
        $etime = date("Y-m-d H:i:s", mktime(59, 59, 59, date("m") , date("d"), date("Y")));

        $timestamp = time();
        // 生成签名
        $channelId = 273;
        $plaintext = $username.$timestamp;
        $sign = self::signEbet($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => $username
            ,"timestamp" => $timestamp
            ,"startTimeStr" => $stime
            ,"endTimeStr" => $etime
            ,"signature" => $sign
        );
        $url = "http://jsz.ebet.im:8888/api/userbethistory";
        $mReturn = self::sendToEbet($SendData,$url);
        $xReturn = json_decode($mReturn, true);
        $list = $xReturn['betHistories'];

        $result = array();
        if($xReturn['status']!=200)
        {
            return "0.00";
            exit();
        }
        $totalBet = 0;
        foreach($list as $item)
        {
            $betMap = $item['betMap'];
            $betMoney = 0;
            foreach($betMap as $bet)
            {
                $betMoney = $betMoney + $bet['betMoney'];
            }
            $totalBet = $totalBet + $betMoney;
        }

        return $totalBet;
    }

    // 生成签名函数
    public static function signEbet($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('EBET_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToEbet($SentData,$url)
    {
        $options = array(
            'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($SentData)));
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }
}




?>