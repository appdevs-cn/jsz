<?php
namespace Home\Model;

use Think\Model;
use Home\Model\UserModel as User;
use Home\Model\TransferRecordModel as TransferRecord;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class PtGameModel extends Model
{
    protected $autoCheckFields = false;
    protected $trueTableName = 'pt_user';

    // 创建PT账号
    public function createPtUser($username, $uid, $parent_id, $parent_path, $group_id, $reg_time, $reg_ip)
    {
        $timestamp = time();
        // 系统自动创建一个密码
        $safe_key= md5(microtime()+mt_rand());
        $password = md5(md5("a123456" . C("PASSWORD_HALT")) . $safe_key);

        // 用户名增加前缀
        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);

        // 生成签名
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "createplayer"
                ,"parameters" => array(
                    "membercode" => $username
                    ,"password" => $password
                    ,"currency" => "CNY"
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0)
            {
                $isHave = $this->where(array("EBJSZ_username"=>$username))->field('id')->find();
                if(empty($isHave))
                {
                    // 如果PT用户注册成功，将数据增加到pt_user数据表中
                    $ptUserData = array(
                        "userid" => $uid
                        ,"parent_id" => $parent_id
                        ,"parent_path" => $parent_path
                        ,"group_id" => $group_id
                        ,"EBJSZ_username" => $username
                        ,"EBJSZ_password" => $password
                        ,"EBJSZ_balance" => 0
                        ,"reg_time" => $reg_time
                        ,"reg_ip" => $reg_ip
                        ,"status" => 1
                        ,"safe_key" => $safe_key
                    );
                    if($this->add($ptUserData))
                        return true;
                    else
                        return false;
                }
                else
                {
                    return true;
                }
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
        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "checkplayerexists"
                ,"parameters" => array(
                    "membercode" => $username
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==54 || $result['Code']==0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    // 查询金额
    public function SearchUserBlance($username)
    {
        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
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
                    "membercode" => $username
                    ,"producttype"  =>  0
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0)
            {
                return $result['Balance'];
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

    //  冻结玩家
    public function FreeUser($username, $frozenstatus)
    {
        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "freezeplayer"
                ,"parameters" => array(
                    "membercode" => $username
                    ,"frozenstatus" => $frozenstatus
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    // 重新设置密码
    public function UpdateUserPtPwd($username, $pwd)
    {
        $password = $pwd;

        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);

        M("pt_user")->where(array("EBJSZ_username"=>$username))->save(array("EBJSZ_password"=>$password));
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "resetpassword"
                ,"parameters" => array(
                    "membercode" => $username
                    ,"password" => $password
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    // 删除游戏会议
    public function KillUserSession($username)
    {
        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);
        $password = M("pt_user")->where(array("EBJSZ_username"=>$username))->getField("EBJSZ_password");
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "killsession"
                ,"parameters" => array(
                    "membercode" => $username
                    ,"password" => $password
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    // 验证玩家
    public function AuthUser($username)
    {
        $username = "EBJSZ_".$username;
        // 将用户名转换为大写
        $username = strtoupper($username);
        $password = M("pt_user")->where(array("EBJSZ_username"=>$username))->getField("EBJSZ_password");
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "authenticateplayer"
                ,"parameters" => array(
                    "membercode" => $username
                    ,"password" => $password
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    // 向Pt平台进行充值 
    public function transferToPt($username,$amount,$fundCode)
    {

        // 查询玩家在PT是否存在
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
        try{
            // 将用户名转换为大写
            $ptusername = strtoupper("EBJSZ_".$username);
            // 向PT平台进行转账
            $ptUserInfo = $this->where(array("EBJSZ_username"=>$ptusername))->find();
            $timestamp = time();
            // 生成签名
            $channelId = 10;
            $thirdParty = "ipm";
            $tag = "ipm_ebet8prod_jsz";
            $plaintext = $channelId.$thirdParty.$tag.$timestamp;
            $sign = self::sign($plaintext);

            $txId = "pt_in_".time()."_".$_xDe->encode($result["id"]);

            // 组合参数
            $SendData = array(
                "channelId" => $channelId
                ,"thirdParty" => $thirdParty
                ,"tag" => $tag
                ,"action" => array(
                    "command" => "createtransaction"
                    ,"parameters" => array(
                        "membercode" => $ptUserInfo['EBJSZ_username']
                        ,"amount" => $amount
                        ,"externaltransactionid" => $txId
                        ,"producttype" => 0
                    )
                )
                ,"live" => 1
                ,"timestamp" => $timestamp
                ,"signature" => $sign
            );
            $mReturn = self::sendToPt($SendData);
            $xReturn = json_decode($mReturn, true);
            if($xReturn['status']==200)
            {
                $response = json_decode($xReturn['result'],true);
                if($response['Code']==0)
                {
                    if($response['Status']=="Approved")
                    {
                        $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                        //账变
                        $_change_data = array(
                            "accounts_type" => 42,  //转账转出
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
                            "remark"	=> "平台转账".$amount."元到PT"
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
                            ,"remark" => "平台转账".$amount."元到PT"
                            ,"time" => time()
                            ,"status" => 1
                            ,"isThird" =>3
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
                        $FoundChangeData['remark'] = "平台转账".$amount."元到PT";
                        $FoundChange->add($FoundChangeData);
                        $userModel->commit();
                        return true;
                    }
                    else if($response['Status']=="Declined")
                    {
                        $userModel->rollback();
                        return false;
                    }
                }
                else
                {
                    $userModel->rollback();
                    return false;
                }
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

    // 从PT转资金到平台 
    public function transferToCp($username,$amount,$fundCode)
    {

        // 查询玩家在PT是否存在
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

        // 查询MG平台账户余额
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

        try
        {
            // 向Pt平台进行提款
            $ptUsername = strtoupper("EBJSZ_".$username);
            $ptUserInfo = $this->where(array("EBJSZ_username"=>$ptUsername))->find();
            $timestamp = time();
            // 生成签名
            $channelId = 10;
            $thirdParty = "ipm";
            $tag = "ipm_ebet8prod_jsz";
            $plaintext = $channelId.$thirdParty.$tag.$timestamp;
            $sign = self::sign($plaintext);

            $txId = "pt_out_".time()."_".$_xDe->encode($result["id"]);

            // 组合参数
            $SendData = array(
                "channelId" => $channelId
                ,"thirdParty" => $thirdParty
                ,"tag" => $tag
                ,"action" => array(
                    "command" => "createtransaction"
                    ,"parameters" => array(
                        "membercode" => $ptUserInfo['EBJSZ_username']
                        ,"amount" => "-".$amount
                        ,"externaltransactionid" => $txId
                        ,"producttype" => 0
                    )
                )
                ,"live" => 1
                ,"timestamp" => $timestamp
                ,"signature" => $sign
            );
            $mReturn = self::sendToPt($SendData);
            $xReturn = json_decode($mReturn, true);
            if($xReturn['status']==200)
            {
                $response = json_decode($xReturn['result'],true);
                if($response['Code']==0)
                {
                    if($response['Status']=="Approved")
                    {
                        $_a = $cur_account+$liamount;
                        $_f_1 = $userModel->db(0)->where(array("id" => $result['id']))->save(array("cur_account"=>$_a));
                        //账变
                        $_change_data = array(
                            "accounts_type" => 43,  //转账转出
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
                            "remark"	=> "PT转账".$amount."元回平台"
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
                            ,"remark" => "PT转账".$amount."元回平台"
                            ,"time" => time()
                            ,"status" => 1
                            ,"isThird" =>3
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
                        $FoundChangeData['remark'] = "PT转账".$amount."元回平台";
                        $FoundChange->add($FoundChangeData);
                        $userModel->commit();
                        return true;
                    }
                    else if($response['Status']=="Declined")
                    {
                        $userModel->rollback();
                        return false;
                    }
                }
                else
                {
                    $userModel->rollback();
                    return false;
                }
            }
            else
            {
                $userModel->rollback();
                return false;
            }
        }catch(Exception $ex)
        {
            $userModel->rollback();
            return false;
        }
    }

    // 查询转账
    public function SearchTransaction($username,$externaltransactionid)
    {
        $username = strtoupper("EBJSZ_".$username);
        // 生成签名
        $timestamp = time();
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "checktransaction"
                ,"parameters" => array(
                    "membercode" => $username
                    ,"externaltransactionid" => $externaltransactionid
                    ,"producttype" => 0
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'],true);
            if($result['Code']==0 && $result['Status']=="Approved")
            {
                return "交易成功";
            }
            else if($result['Code']==0 && $result['Status']=="Declined")
            {
                return "转账失败";
            }
            else if($result['Code']==0 && $result['Status']=="Processed")
            {
                return "转账处理中";
            }
            else
            {
                return "交易号不存在或玩家不存在";
            }
        }
        else
        {
            return "无效的产品类型";
        }
    }

    // 获取游戏连接
    public function GetLaunghGame($gameid)
    {
        $ptUser = M("pt_user");
        $password = $ptUser->where(array("EBJSZ_username"=>"EBJSZ_".session("SESSION_NAME")))->getField("EBJSZ_password");
        return "http://app.ptonline.club:3001/flashIPM.html?username=EBJSZ_".strtoupper(session("SESSION_NAME"))."&password=".$password."&lang=ZH-CN&game=".$gameid;
    }

    // 获取投注数据
    public function GetPtRecord($username)
    {
        $accordStartTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y")));
        $accordEndTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d")+1, date("Y")));

        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? $starttime." 0:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? $endtime." 0:0:0" : $accordEndTime;
        
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

        $ip = get_client_ip();
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => array(
                    "membercode" => "EBJSZ_".$username
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
        if($xReturn['status']==200)
        {
            return json_decode($xReturn['betHistories'],true);
            
        }
        else
        {
            return "";
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
}




?>