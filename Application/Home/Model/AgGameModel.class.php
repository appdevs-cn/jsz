<?php
namespace Home\Model;

use Think\Model;
use Home\Model\UserModel as User;
use Home\Model\TransferRecordModel as TransferRecord;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class AgGameModel extends Model
{
    protected $autoCheckFields = false;
    protected $trueTableName = 'ag_user';

    // 创建AG账户
    public function createAgUser($username, $uid, $parent_id, $parent_path, $group_id, $reg_time, $reg_ip)
    {
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;

        // 将用户变为小写
        $username = strtolower($username);
        // 系统自动创建一个密码
        $password = self::SelfMD5($username.$timestamp);

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "checkorcreategameaccount"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"password" => $password
                    ,"actype" => 1
                    ,"cur" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);

        $xml = simplexml_load_string($xReturn['result']);
        $xml=get_object_vars($xml);
        
        if($xReturn['status']==200)
        {
            if($xml['@attributes']['info']=="0")
            {
                $isHave = $this->where(array("ag_username"=>$username))->field('id')->find();
                if(empty($isHave))
                {
                    // 如果ag用户注册成功，将数据增加到ag_user数据表中
                    $agUserData = array(
                        "userid" => $uid
                        ,"parent_id" => $parent_id
                        ,"parent_path" => $parent_path
                        ,"group_id" => $group_id
                        ,"ag_username" => $username
                        ,"ag_password" => $password
                        ,"ag_balance" => 0
                        ,"reg_time" => $reg_time
                        ,"reg_ip" => $reg_ip
                        ,"status" => 1
                    );
                    if($this->add($agUserData))
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
        else
        {
            return false;
        }
    }

    // 查询玩家是否存在
    public function SearchUser($username)
    {
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;

        // 将用户变为小写
        $username = strtolower($username);

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getbalance"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"actype" => 1
                    ,"cur" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        $xml = simplexml_load_string($xReturn['result']);
        $xml=get_object_vars($xml);

         if($xReturn['status']==200)
         {
             if($xml['@attributes']['info']!="account_not_exist")
             {
                 return true;
             }
             else
             {
                 return false;
             }
         }
         else
         {
             return false;
         }
    }

    // 查询余额
    public function SearchUserBlance($username)
    {
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;

        // 将用户变为小写
        $username = strtolower($username);

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getbalance"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"actype" => 1
                    ,"cur" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        $xml = simplexml_load_string($xReturn['result']);
        $xml=get_object_vars($xml);

         if($xReturn['status']==200)
         {
             if($xml['@attributes']['info']!="key_error" && $xml['@attributes']['info']!="network_error" && $xml['@attributes']['info']!="account_not_exist" && $xml['@attributes']['info']!="error")
             {
                 return $xml['@attributes']['info'];
             }
             else
             {
                 return "0.00";
             }
         }
         else
         {
             return "0.00";
         }
    }

    // 转账到ag平台
    public function transferToAg($username,$amount,$fundCode)
    {

        // 查询玩家是否存在
        if(!$this->SearchUser($username))
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

        // 向AG平台发送预备转账
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;

        // 将用户变为小写
        $username = strtolower($username);

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        $txId = "ag_in_".time()."_".$_xDe->encode($result["id"]);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "preparetransfercredit"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"billno" => $txId
                    ,"actype" => 1
                    ,"type" => "IN"
                    ,"credit" => $amount
                    ,"cur" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);

        $xml = simplexml_load_string($xReturn['result']);
        $xml=get_object_vars($xml);

        if($xReturn['status']==200)
        {
            if($xml['@attributes']['info']=="0")
            {
                try
                {
                    $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                    //账变
                    $_change_data = array(
                        "accounts_type" => 44,  //转账转出
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
                        "remark"	=> "平台转账".$amount."元到AG"
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
                        ,"remark" => "平台转账".$amount."元到AG"
                        ,"time" => time()
                        ,"status" => 1
                        ,"isThird" =>2
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
                    $FoundChangeData['remark'] = "平台转账".$amount."元到AG";
                    $FoundChange->add($FoundChangeData);
                    $userModel->commit();

                    // 组合参数
                    $SendData = array(
                        "channelId" => $channelId
                        ,"thirdParty" => $thirdParty
                        ,"tag" => $tag
                        ,"action" => array(
                            "command" => "transfercreditconfirm"
                            ,"parameters" => array(
                                "loginname" => $username
                                ,"billno" => $txId
                                ,"actype" => 1
                                ,"type" => "IN"
                                ,"credit" => $amount
                                ,"flag" => 1
                                ,"cur" => "CNY"
                            )
                        )
                        ,"live" => 1
                        ,"timestamp" => $timestamp
                        ,"signature" => $sign
                    );
                    $mReturn = self::sendToAg($SendData);
                    $xReturn = json_decode($mReturn, true);

                    $xml = simplexml_load_string($xReturn['result']);
                    $xml=get_object_vars($xml);

                    if($xReturn['status']==200)
                    {
                        if($xml['@attributes']['info']=="0")
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }catch(Exception $ex)
                {
                    // 组合参数
                    $SendData = array(
                        "channelId" => $channelId
                        ,"thirdParty" => $thirdParty
                        ,"tag" => $tag
                        ,"action" => array(
                            "command" => "transfercreditconfirm"
                            ,"parameters" => array(
                                "loginname" => $username
                                ,"billno" => $txId
                                ,"actype" => 1
                                ,"type" => "IN"
                                ,"credit" => $amount
                                ,"flag" => 0
                                ,"cur" => "CNY"
                            )
                        )
                        ,"live" => 1
                        ,"timestamp" => $timestamp
                        ,"signature" => $sign
                    );
                    $mReturn = self::sendToAg($SendData);
                    $xReturn = json_decode($mReturn, true);

                    $xml = simplexml_load_string($xReturn['result']);
                    $xml=get_object_vars($xml);
                    return false;
                }
            }
        }
    }
    // 从AG转资金到平台
    public function transferToCp($username,$amount,$fundCode)
    {
        // 查询玩家是否存在
        if(!$this->SearchUser($username))
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

        // 查询AG平台账户余额
        $blance = $this->SearchUserBlance($username);

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

        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;

        // 将用户变为小写
        $username = strtolower($username);
        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        $txId = "ag_out_".time()."_".$_xDe->encode($result["id"]);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "preparetransfercredit"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"billno" => $txId
                    ,"actype" => 1
                    ,"type" => "OUT"
                    ,"credit" => $amount
                    ,"cur" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);

        $xml = simplexml_load_string($xReturn['result']);
        $xml=get_object_vars($xml);

         if($xReturn['status']==200)
        {
            if($xml['@attributes']['info']=="0")
            {
                $_a = $cur_account+$liamount;

                try{
                    $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                    //账变
                    $_change_data = array(
                        "accounts_type" => 45,  //转账转出
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
                        "remark"	=> "AG转账".$amount."元回平台"
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
                        ,"remark" => "AG转账".$amount."元回平台"
                        ,"time" => time()
                        ,"status" => 1
                        ,"isThird" =>2
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
                    $FoundChangeData['remark'] = "AG转账".$amount."元回平台";
                    $FoundChange->add($FoundChangeData);
                    $userModel->commit();

                    // 组合参数
                    $SendData = array(
                        "channelId" => $channelId
                        ,"thirdParty" => $thirdParty
                        ,"tag" => $tag
                        ,"action" => array(
                            "command" => "transfercreditconfirm"
                            ,"parameters" => array(
                                "loginname" => $username
                                ,"billno" => $txId
                                ,"actype" => 1
                                ,"type" => "OUT"
                                ,"credit" => $amount
                                ,"flag" => 1
                                ,"cur" => "CNY"
                            )
                        )
                        ,"live" => 1
                        ,"timestamp" => $timestamp
                        ,"signature" => $sign
                    );
                    $mReturn = self::sendToAg($SendData);
                    $xReturn = json_decode($mReturn, true);

                    $xml = simplexml_load_string($xReturn['result']);
                    $xml=get_object_vars($xml);

                    if($xReturn['status']==200)
                    {
                        if($xml['@attributes']['info']=="0")
                        {
                            return true;
                        }
                        else
                        {
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
                catch(Exception $ex)
                {
                    // 组合参数
                    $SendData = array(
                        "channelId" => $channelId
                        ,"thirdParty" => $thirdParty
                        ,"tag" => $tag
                        ,"action" => array(
                            "command" => "transfercreditconfirm"
                            ,"parameters" => array(
                                "loginname" => $username
                                ,"billno" => $txId
                                ,"actype" => 1
                                ,"type" => "OUT"
                                ,"credit" => $amount
                                ,"flag" => 0
                                ,"cur" => "CNY"
                            )
                        )
                        ,"live" => 1
                        ,"timestamp" => $timestamp
                        ,"signature" => $sign
                    );
                    $mReturn = self::sendToAg($SendData);
                    $xReturn = json_decode($mReturn, true);

                    $xml = simplexml_load_string($xReturn['result']);
                    $xml=get_object_vars($xml);
                    return false;
                }
            }
        }
    }

    // 获取游戏连接
    public function getGameUrl($username,$gameType)
    {
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;
        // 将用户变为小写
        $username = strtolower($username);

        $password = M("ag_user")->where(array("userid"=>session("SESSION_ID")))->getField("ag_password");

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $sid = 'cagentag_s54_jsz'.(self::get_total_millisecond());
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getforwardgameurl"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"actype" => 1
                    ,"gameType" => $gameType
                    ,"dm" => $_SERVER['HTTP_HOST']
                    ,'sid' => $sid
                    ,"lang" => "zh-cn"
                    ,"oddType" => "A"
                    ,"cur"  => "CNY"
                    ,"flashid" => ""
                    ,"password" => $password 
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        
        if($xReturn['status']==200)
        {
            return $xReturn['result'];
        }
        else
        {
            return false;
        }
    }


    // 获取试玩游戏连接
    public function getDemoGameUrl($username,$gameType)
    {
        $timestamp = time();
        // 用户名增加前缀
        $username = "jsz".$username;

        // 将用户变为小写
        $username = strtolower($username);

        $password = M("ag_user")->where(array("ag_username"=>$username))->getField("ag_password");

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $sid = 'cagentag_s54_jsz'.(self::get_total_millisecond());
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getforwardgameurl"
                ,"parameters" => array(
                    "loginname" => $username
                    ,"actype" => 0
                    ,"gameType" => $gameType
                    ,"dm" => $_SERVER['HTTP_HOST']
                    ,'sid' => $sid
                    ,"lang" => "zh-cn"
                    ,"oddType" => "A"
                    ,"cur"  => "CNY"
                    ,"flashid" => ""
                    ,"password" => $password 
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        
        if($xReturn['status']==200)
        {
            return $xReturn['result'];
        }
        else
        {
            return false;
        }
    }

    // 获取投注
    public function searchGameOrder($username,$gameType)
    {
        $username = "jsz_".$username;

        // 将用户变为小写
        $username = strtolower($username);

        $accordStartTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y")));
        $accordEndTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));

        $starttime = I("post.starttime","");
        $startTimeStr = (!empty($starttime)) ? $starttime." 0:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $endTimeStr = (!empty($endtime)) ? $endtime." 0:0:0" : $accordEndTime;

        $timestamp = time();

        $password = M("ag_user")->where(array("userid"=>session("SESSION_ID")))->getField("ag_password");

        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $sid = 'cagentag_s54_jsz'.(self::get_total_millisecond());
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
                    ,"gameType" => $gameType
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        
        if($xReturn['status']==200)
        {
            return $xReturn['result'];
        }
        else
        {
            return false;
        }
    }

    // 返回时间戳微妙
    public static function get_total_millisecond()  
    {  
        $time = explode (" ", microtime () );   
        $time = $time [1] . ($time [0] * 1000);   
        $time2 = explode ( ".", $time );
        $time = $time2 [0];  
        return $time;  
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
}




?>