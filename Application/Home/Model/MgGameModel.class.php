<?php
namespace Home\Model;

use Think\Model;
use Home\Model\UserModel as User;
use Home\Model\TransferRecordModel as TransferRecord;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class MgGameModel extends Model
{
    protected $autoCheckFields = false;
    protected $trueTableName = 'mg_user';

    // 创建MG账号
    public function createMgUser($username, $uid, $parent_id, $parent_path, $group_id, $reg_time, $reg_ip)
    {
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz_".$username;
        // 将用户变为小写
        $username = strtolower($username);
        // 系统自动创建一个密码
        $password = self::SelfMD5($username.$timestamp);

        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "createmember"
                ,"parameters" => array(
                    "username" => $username
                    ,"password" => $password
                    ,"currency" => "CNY"
                    ,"language" => "en"
                    ,"casinoIsEnabled" => true
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $isHave = $this->where(array("mg_username"=>$username))->field('id')->find();
            if(empty($isHave))
            {
                // 如果MG用户注册成功，将数据增加到mg_user数据表中
                $mgUserData = array(
                    "userid" => $uid
                    ,"parent_id" => $parent_id
                    ,"parent_path" => $parent_path
                    ,"group_id" => $group_id
                    ,"mg_username" => $username
                    ,"mg_password" => $password
                    ,"mg_balance" => 0
                    ,"reg_time" => $reg_time
                    ,"reg_ip" => $reg_ip
                    ,"status" => 1
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
        // 将用户变为小写
        $username = strtolower("jsz_".$username);
        $mgUserInfo = $this->where(array("mg_username"=>$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "memberdetail"
                ,"parameters" => array(
                    "username" => $mgUserInfo['mg_username']
                    ,"password" => $mgUserInfo['mg_password']
                    ,"currencyCode" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $xReturn = json_decode($mReturn, true);
        return $xReturn;
    }

    // 查询玩家账户余额
    public function searchUserBalance($username)
    {
        // 将用户变为小写
        $username = strtolower("jsz_".$username);
        $mgUserInfo = $this->where(array("mg_username"=>$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "memberdetail"
                ,"parameters" => array(
                    "username" => $mgUserInfo['mg_username']
                    ,"password" => $mgUserInfo['mg_password']
                    ,"currencyCode" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $UserInfo = json_decode($mReturn, true);
        $xReturn = json_decode($UserInfo['result'],true);
        return $xReturn['credit-balance'];
    }

    // 获取游戏连接
    public function getGameUrl($gameid)
    {
        // 将用户变为小写
        $username = strtolower("jsz_".session("SESSION_NAME"));
        $mgUserInfo = $this->where(array("mg_username"=>$username))->find();
        if(empty($mgUserInfo)) return "";
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getlaunchgameurl"
                ,"parameters" => array(
                    "username" => $mgUserInfo['mg_username']
                    ,"password" => $mgUserInfo['mg_password']
                    ,"currencyCode" => "CNY"
                    ,"language" => "en"
                    ,"gameId" => $gameid
                    ,"demoMode" => false
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $xReturn = json_decode($mReturn, true);
        return $xReturn;
    }

    // 获取MG平台投注信息
    public function searchGameOrder($username,$gameid="")
    {
        // 查询参数
        // 将用户变为小写
        $username = strtolower("jsz_".$username);
        $parameters['username'] = $username;
        $accordStartTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y")));
        $accordEndTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));

        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? $starttime." 0:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? $endtime." 0:0:0" : $accordEndTime;
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;
        if($gameid!="") $parameters['gameId'] = $gameid;

        $mgUserInfo = $this->where(array("mg_username"=>"jsz_".$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

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
        $xReturn = json_decode($mReturn, true);
        return $xReturn;
    }

    // 向MG平台进行充值 
    public function transferToMg($username,$amount,$fundCode)
    {

        $MgUserInfo = $this->searchUser($username);
        if($MgUserInfo['status']!=200)
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
            // 向MG平台进行转账
            // 将用户变为小写
            $username = strtolower("jsz_".$username);
            $mgUserInfo = $this->where(array("mg_username"=>$username))->find();
            $timestamp = time();
            // 生成签名
            $channelId = 10;
            $thirdParty = "mg";
            $tag = "mg_jsz";
            $plaintext = $channelId.$thirdParty.$tag.$timestamp;
            $sign = self::sign($plaintext);

            $txId = "mg_in_".time()."_".$_xDe->encode($result["id"]);

            // 组合参数
            $SendData = array(
                "channelId" => $channelId
                ,"thirdParty" => $thirdParty
                ,"tag" => $tag
                ,"action" => array(
                    "command" => "deposit"
                    ,"parameters" => array(
                        "username" => $mgUserInfo['mg_username']
                        ,"password" => $mgUserInfo['mg_password']
                        ,"currencyCode" => "CNY"
                        ,"product" => "casino"
                        ,"operation" => "topup"
                        ,"amount" => $amount
                        ,"tx-id" => $txId
                    )
                )
                ,"live" => 1
                ,"timestamp" => $timestamp
                ,"signature" => $sign
            );
            $mReturn = self::sendToMg($SendData);
            $xReturn = json_decode($mReturn, true);
            if($xReturn['status']==200)
            {
                $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                //账变
                $_change_data = array(
                    "accounts_type" => 38,  //转账转出
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
                    "remark"	=> "平台转账".$amount."元到MG"
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
                    ,"remark" => "平台转账".$amount."元到MG"
                    ,"time" => time()
                    ,"status" => 1
                    ,"isThird" =>1
                    ,"toUserid" => $result['id']
                    ,"toUsername" => $result['username']
                    ,"thirdOrder" => $txId
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
                $FoundChangeData['remark'] = "平台转账".$amount."元到MG";
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

    // 从MG转资金到平台
    public function transferToCp($username,$amount,$fundCode)
    {
        $MgUserInfo = $this->searchUser($username);
        if($MgUserInfo['status']!=200)
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

        // 查询MG平台账户余额
        $blance = $this->searchUserBalance($username);

        // 比较MG余额是否足够提款
        if(bccomp($amount,$blance,4)==1)
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

        // 向MG平台进行提款
        // 将用户变为小写
        $username = strtolower("jsz_".$username);
        $mgUserInfo = $this->where(array("mg_username"=>$username))->find();
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        $txId = "mg_out_".time()."_".$_xDe->encode($result["id"]);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "withdraw"
                ,"parameters" => array(
                    "username" => $mgUserInfo['mg_username']
                    ,"password" => $mgUserInfo['mg_password']
                    ,"currencyCode" => "CNY"
                    ,"product" => "casino"
                    ,"operation" => "withdraw"
                    ,"amount" => $amount
                    ,"tx-id" => $txId
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $_a = $cur_account+$liamount;

            try{
                $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                //账变
                $_change_data = array(
                    "accounts_type" => 39,  //转账转出
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
                    "remark"	=> "MG转账".$amount."元回平台"
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
                    ,"type" => "资金转入"
                    ,"remark" => "MG转账".$amount."元回平台"
                    ,"time" => time()
                    ,"status" => 1
                    ,"isThird" =>1
                    ,"toUserid" => $result['id']
                    ,"toUsername" => $result['username']
                    ,"thirdOrder" => $txId
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
                $FoundChangeData['remark'] = "MG转账".$amount."元回平台";
                $FoundChange->add($FoundChangeData);

                $userModel->commit();
                return true;
            } catch(Exception $ex)
            {
                // 如果加入数据库出现异常，那么从MG转出的钱重新充值到MG
                // 向MG平台进行转账
                $mgUserInfo = $this->where(array("mg_username"=>"jsz_".$username))->find();
                $timestamp = time();
                // 生成签名
                $channelId = 10;
                $thirdParty = "mg";
                $tag = "mg_jsz";
                $plaintext = $channelId.$thirdParty.$tag.$timestamp;
                $sign = self::sign($plaintext);

                $txId = "mg_in_".time()."_".$_xDe->encode($result["id"]);

                // 组合参数
                $SendData = array(
                    "channelId" => $channelId
                    ,"thirdParty" => $thirdParty
                    ,"tag" => $tag
                    ,"action" => array(
                        "command" => "deposit"
                        ,"parameters" => array(
                            "username" => $mgUserInfo['mg_username']
                            ,"password" => $mgUserInfo['mg_password']
                            ,"currencyCode" => "CNY"
                            ,"product" => "casino"
                            ,"operation" => "topup"
                            ,"amount" => $amount
                            ,"tx-id" => $txId
                        )
                    )
                    ,"live" => 1
                    ,"timestamp" => $timestamp
                    ,"signature" => $sign
                );
                $mReturn = self::sendToMg($SendData);
                $userModel->rollback();
                return false;
            }
        }
        else
        {
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
}



?>