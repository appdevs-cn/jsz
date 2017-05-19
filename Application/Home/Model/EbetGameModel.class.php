<?php

namespace Home\Model;

use Think\Model;
use Home\Model\UserModel as User;
use Home\Model\TransferRecordModel as TransferRecord;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class EbetGameModel extends Model
{
    protected $autoCheckFields = false;
    protected $trueTableName = 'ebet_user';

    // 创建EBET账号
    public function createEbetUser($username, $uid, $parent_id, $parent_path, $group_id, $reg_time, $reg_ip)
    {
        $timestamp = time();
        // 系统自动创建一个密码
        $safe_key= md5(microtime()+mt_rand());
        $password = md5(md5("a123456" . C("PASSWORD_HALT")) . $safe_key);

        // 用户名增加前缀
        $username = "ebet_".$username;
        // 将用户名转换为小写
        $username = strtolower($username);

        // 生成签名
        $channelId = 273;
        $plaintext = $username;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => $username
            ,"lang" => 1
            ,"signature" => $sign
        );
        $url = "http://jsz.ebet.im:8888/api/syncuser";
        $mReturn = self::sendToEbet($SendData,$url);
        $xReturn = json_decode($mReturn, true);

        if($xReturn['status']==200)
        {
            $isHave = $this->where(array("ebet_username"=>$username))->field('id')->find();
            if(empty($isHave))
            {
                // 如果Ebet用户注册成功，将数据增加到ebet_user数据表中
                $mgUserData = array(
                    "userid" => $uid
                    ,"parent_id" => $parent_id
                    ,"parent_path" => $parent_path
                    ,"group_id" => $group_id
                    ,"ebet_username" => $username
                    ,"ebet_password" => $password
                    ,"ebet_balance" => 0
                    ,"reg_time" => $reg_time
                    ,"reg_ip" => $reg_ip
                    ,"status" => 1
                    ,"safe_key" => $safe_key
                );
                if($this->add($mgUserData))
                    return true;
                else
                    return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    // 查询玩家
    public function searchUser($username)
    {
        $username = strtolower($username);
        $ebetUserInfo = $this->where(array("ebet_username"=>"ebet_".$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 273;
        $plaintext = $ebetUserInfo['ebet_username'].$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => $ebetUserInfo['ebet_username']
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $url = "http://jsz.ebet.im:8888/api/userinfo";
        $mReturn = self::sendToEbet($SendData,$url);
        $xReturn = json_decode($mReturn, true);
        return $xReturn;
    }

    // 查询玩家账户余额
    public function searchUserBalance($username)
    {
        $username = strtolower($username);
        $ebetUserInfo = $this->where(array("ebet_username"=>"ebet_".$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 273;
        $plaintext = $ebetUserInfo['ebet_username'].$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => $ebetUserInfo['ebet_username']
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $url = "http://jsz.ebet.im:8888/api/userinfo";
        $mReturn = self::sendToEbet($SendData,$url);
        $xReturn = json_decode($mReturn, true);
        return $xReturn['money'];
    }

    // 获取EBET平台投注信息
    public function searchGameOrder($username)
    {
        $username = strtolower($username);
        // 查询参数
        $username = "ebet_".$username;

        $accordStartTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y")));
        $accordEndTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));

        $starttime = I("post.starttime","");
        $startTimeStr = (!empty($starttime)) ? $starttime." 0:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $endTimeStr = (!empty($endtime)) ? $endtime." 0:0:0" : $accordEndTime;

        $ebetUserInfo = $this->where(array("ebet_username"=>"ebet_".$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 273;
        $plaintext = $ebetUserInfo['ebet_username'].$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => $ebetUserInfo['ebet_username']
            ,"timestamp" => $timestamp
            ,"startTimeStr" => $startTimeStr
            ,"endTimeStr" => $endTimeStr
            ,"signature" => $sign
        );
        $url = "http://jsz.ebet.im:8888/api/userbethistory";
        $mReturn = self::sendToEbet($SendData,$url);
        $xReturn = json_decode($mReturn, true);
        return $xReturn;
    }

    // 向EBET平台进行充值 
    public function transferToEbet($username,$amount,$fundCode)
    {
        // 查询玩家是否存在
        $EbetUserInfo = $this->searchUser($username);
        if($EbetUserInfo['status']!=200)
        {
            return false;
            exit();
        }

        import("Class.XDeode");
        $_xDe=new \XDeode();

        // 查询用户的平台金额
        $userModel = M("user");
        $accountChange = M("accounts_change");

        // 启动事物
        $userModel->startTrans();
        $result = $userModel->db(0)->lock(true)->where(array("username" => $username))->field("id,username,parent_id,parent_path,cur_account,acc_password,safe_key")->find();
        $cur_account = $result['cur_account'];
        $liamount = $amount*100000;
        // 比对账户的资金是否足够
        if(bccomp($cur_account,$liamount,2)==-1)
        {
            return false;
            exit();
        }

        // 验证资金密码
        $acc_password_new = md5(md5($fundCode.C("PASSWORD_HALT")).$result["safe_key"]);
        if($acc_password_new != $result["acc_password"]){
            return false;
            exit();
        }

        //从自己的资金里面扣除转账的钱
        $_a = $cur_account-$liamount;
        try{
            $ebetusername = strtolower("ebet_".$username);
            // 向EBET平台进行转账
            $ebetUserInfo = $this->where(array("ebet_username"=>$ebetusername))->find();
            $timestamp = time();
            // 生成签名

            // 先查询Ebet平台的用户余额
            $beforeEbetAccount = $this->searchUserBalance($username);


            $channelId = 273;
            $money = $amount;
            $plaintext = $ebetUserInfo['ebet_username'].$timestamp;
            $sign = self::sign($plaintext);
            $rechargeReqId = "ebet_in_".time()."_".$_xDe->encode($result["id"]);

            // 组合参数
            $SendData = array(
                "channelId" => $channelId
                ,"username" => $ebetUserInfo['ebet_username']
                ,"timestamp" => $timestamp
                ,"money" => $money
                ,"rechargeReqId" => $rechargeReqId
                ,"signature" => $sign
            );
            $url = "http://jsz.ebet.im:8888/api/recharge";
            $mReturn = self::sendToEbet($SendData,$url);
            $xReturn = json_decode($mReturn, true);
            // 查询充值是否成功
            $isStop = true;
            // 请求次数
            $requestCount = 0;
            do{
                if($requestCount>5)
                {
                    $isStop = false;
                }
                $sign = self::sign($rechargeReqId);
                $SendData = array(
                    "channelId" => $channelId
                    ,"rechargeReqId" => $rechargeReqId
                    ,"signature" => $sign
                );
                $url = "http://jsz.ebet.im:8888/api/rechargestatus";
                $mReturnStatus = self::sendToEbet($SendData,$url);
                $xReturnStatus = json_decode($mReturnStatus, true);
                if($xReturnStatus['status']==200)
                {
                    $isStop = false;
                }
                ++$requestCount;
                sleep(1);
            }while($isStop);

            if(!$isStop)
            {
                $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                //账变
                $_change_data = array(
                    "accounts_type" => 40,  //转账转出
                    "buy_record_id" => 0,
                    "change_amount" => $liamount,
                    "userid" => $result['id'],
                    "username" => $result['username'],
                    "parent_id" => $result['parent_id'],
                    "parent_path" => $result['parent_path'],
                    "cur_account" => $_a,
                    "serial_number" => 0,
                    "runner_id" => $result['id'],
                    "runner_name" => $result['username'],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark"	=> "平台转账".$amount."元到eBET"
                );
                $_account_change_id = $accountChange->add($_change_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($_account_change_id));
                $accountChange->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));
                $TransferRecord = new TransferRecord();
                // 转出用户的转账记录添加
                $TransferRecord->data = array("data"=>array('add'=>array(
                    "userid" => $result['id']
                    ,"username" => $result['username']
                    ,"parent_id" => $result['parent_id']
                    ,"parent_path" => $result['parent_path']
                    ,"beforeAmount" => $cur_account
                    ,"amount" => $liamount
                    ,"afterAmount" => $_a
                    ,"type" => "资金转出"
                    ,"remark" => "平台转账".$amount."元到Ebet"
                    ,"time" => time()
                    ,"status" => 1
                    ,"isThird" =>6
                    ,"toUserid" => $result['id']
                    ,"toUsername" => $result['username']
                    ,"thirdOrder" => $rechargeReqId
                )));
                $TransferRecord->addTransferRecord();

                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $_uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($liamount);
                $FoundChangeData['afterMoney'] = intval($_a);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "平台转账".$amount."元到Ebet";
                $FoundChange->add($FoundChangeData);
                $userModel->commit();
                return true;
            }
            else
            {
                $userModel->rollback();
                return false;
            }
        } catch(Exception $ex)
        {
            $userModel->rollback();
            return false;
        }
    }

    // 从Ebet转资金到平台
    public function transferToCp($username,$amount,$fundCode)
    {
        // 查询玩家是否存在
        $EbetUserInfo = $this->searchUser($username);
        if($EbetUserInfo['status']!=200)
        {
            return false;
            exit();
        }

        import("Class.XDeode");
        $_xDe=new \XDeode();

        // 查询用户的平台金额
        $userModel = M("user");
        $accountChange = M("accounts_change");

        // 启动事物
        $userModel->startTrans();
        $result = $userModel->db(0)->lock(true)->where(array("username" => $username))->field("id,username,parent_id,parent_path,cur_account,acc_password,safe_key")->find();
        $cur_account = $result['cur_account'];
        $liamount = $amount*100000;

        // 验证资金密码
        $acc_password_new = md5(md5($fundCode.C("PASSWORD_HALT")).$result["safe_key"]);
        if($acc_password_new != $result["acc_password"]){
            return false;
            exit();
        }

        //从自己的资金里面增加转账的钱
        $_a = $cur_account+$liamount;
        try{
            // 向EBET平台进行转账
            $ebetusername = strtolower("ebet_".$username);
            $ebetUserInfo = $this->where(array("ebet_username"=>$ebetusername))->find();
            $timestamp = time();
            // 先查询Ebet平台的用户余额
            $beforeEbetAccount = $this->searchUserBalance($username);

            // 比对账户的资金是否足够
            if(bccomp($beforeEbetAccount,$amount,2)==-1)
            {
                return false;
                exit();
            }

            $channelId = 273;
            $money = "-".$amount;
            $plaintext = $ebetUserInfo['ebet_username'].$timestamp;
            $sign = self::sign($plaintext);
            $rechargeReqId = "ebet_in_".time()."_".$_xDe->encode($result["id"]);

            // 组合参数
            $SendData = array(
                "channelId" => $channelId
                ,"username" => $ebetUserInfo['ebet_username']
                ,"timestamp" => $timestamp
                ,"money" => $money
                ,"rechargeReqId" => $rechargeReqId
                ,"signature" => $sign
            );
            $url = "http://jsz.ebet.im:8888/api/recharge";
            $mReturn = self::sendToEbet($SendData,$url);
            $xReturn = json_decode($mReturn, true);

            // 查询转账是否成功
            $isStop = true;
            $requestCount = 0;
            do{
                if($requestCount>5)
                {
                    $isStop = false;
                }
                $sign = self::sign($rechargeReqId);
                $SendData = array(
                    "channelId" => $channelId
                    ,"rechargeReqId" => $rechargeReqId
                    ,"signature" => $sign
                );
                $url = "http://jsz.ebet.im:8888/api/rechargestatus";
                $mReturnStatus = self::sendToEbet($SendData,$url);
                $xReturnStatus = json_decode($mReturnStatus, true);
                if($xReturnStatus['status']==200)
                {
                    $isStop = false;
                }
                ++$requestCount;
                sleep(1);
            }while($isStop);

            if(!$isStop)
            {
                $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                //账变
                $_change_data = array(
                    "accounts_type" => 41,  //转账转出
                    "buy_record_id" => 0,
                    "change_amount" => $liamount,
                    "userid" => $result['id'],
                    "username" => $result['username'],
                    "parent_id" => $result['parent_id'],
                    "parent_path" => $result['parent_path'],
                    "cur_account" => $_a,
                    "serial_number" => 0,
                    "runner_id" => $result['id'],
                    "runner_name" => $result['username'],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark"	=> "eBET转账".$amount."元回平台"
                );
                $_account_change_id = $accountChange->add($_change_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($_account_change_id));
                $accountChange->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));
                $TransferRecord = new TransferRecord();
                // 转出用户的转账记录添加
                $TransferRecord->data = array("data"=>array('add'=>array(
                    "userid" => $result['id']
                    ,"username" => $result['username']
                    ,"parent_id" => $result['parent_id']
                    ,"parent_path" => $result['parent_path']
                    ,"beforeAmount" => $cur_account
                    ,"amount" => $liamount
                    ,"afterAmount" => $_a
                    ,"type" => "资金转进"
                    ,"remark" => "eBET转账".$amount."元回平台"
                    ,"time" => time()
                    ,"status" => 1
                    ,"isThird" =>6
                    ,"toUserid" => $result['id']
                    ,"toUsername" => $result['username']
                    ,"thirdOrder" => $rechargeReqId
                )));
                $TransferRecord->addTransferRecord();

                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $_uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($liamount);
                $FoundChangeData['afterMoney'] = intval($_a);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "eBET转账".$amount."元回平台";
                $FoundChange->add($FoundChangeData);
                $userModel->commit();
                return true;
            }
            else
            {
                $userModel->rollback();
                return false;
            }
        } catch(Exception $ex)
        {
            $userModel->rollback();
            return false;
        }
    }

    // md5加密函数
    public static function SelfMD5($sources)
    {
        $Sources = $sources."JSZYL";
        return strtoupper(hash('md5', $Sources));
    }
    
    // 生成签名函数
    public static function sign($plaintext)
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