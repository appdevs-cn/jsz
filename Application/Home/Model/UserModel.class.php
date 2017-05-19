<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/6
 * Time: 下午8:03
 */

namespace Home\Model;
use Think\Model;
use Home\Model\UserLoginModel as userLogin;
use Home\Model\UserBankModel as UserBank;
use Home\Model\UserQuestionModel as UserQuestion;
use Home\Model\UserIntervalModel as UserInterval;
use Home\Model\AccordingTimeModel as AccordingTime;
use Home\Model\CommonModel as Common;
use Home\Model\TransferRecordModel as TransferRecord;
use \Home\Model\FoundChangeMongoModel as FoundChange;
use Home\Model\SessionToMongoModel as SessionMongo;
class UserModel extends Model
{
    protected $trueTableName = "user";
    /*字段映射*/
    protected $_map = array(
        "name" => "username",
        "pwd" => "password"
    );
    /*自动验证*/
    protected $_validate = array(
        array("username","require","用户名不能为空",self::MUST_VALIDATE),
        array("password","require","密码不能为空",self::MUST_VALIDATE)
    );

    public $obj;
    public $userlogin;
    public $UserBank;
    public $UserQuestion;


    // 发送短信
    public function SendSms($tel,$param)
    {
        $Appkey = C("MessageCodeKey");
        $TemplateId = C("TemplateId");
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

    /**
     * 用户登录
     *
     * @return string
     */
    public function checkLoginModel()
    {
        $this->userlogin = new userLogin();
        $SessionMongo = new SessionMongo();
        $loginstrikeModel = M("loginstrike");
        $loginData = array();
        $data = $this->data();
        $name = $data["username"];
        $pwd = $data["password"];

        //如果该用户在当天的登陆错误次数达到4次，锁定帐号
        $stime = time()-600;
        $etime = time();
        $_map["_string"] = "strike_time>=".$stime." and $stime<=".$etime;
        $_map["username"] = $name;
        $error_login_count = $loginstrikeModel->where($_map)->count();
        if($error_login_count>4) {
            return "0-请10分钟之后尝试或者联系客服​​!";
            exit();
        }


        $userInfo = $this->where(array("username"=>$name,"status"=>1))->find();
        if(!empty($userInfo) && $userInfo["isPassword"]==0)
        {
            $msg = "0-请使用谷歌验证进行登录";
            return $msg;
            exit();
        }
        if(empty($userInfo))
        {
            $msg = "0-用户名不存在";
        }
        else{
            $password = md5(md5($pwd . C("PASSWORD_HALT")) . $userInfo['safe_key']);
            if($userInfo["password"] != $password)
            {
                $_data = array("strike_time"=>time(),"username"=>$name,"ip"=>ip2long(get_client_ip()),"location"=>"前台用户登录");
                $loginstrikeModel->add($_data);
                $msg = "0-登录密码错误";
            }
            else
            {
                if (!in_array($userInfo['group_id'], array(3, 4, 5))) {
                    $msg = "0-该账号无权限登录";
                }
                else
                {
                    /*获取上次登录的IP地址*/
                    $beforeLogin = $this->userlogin->where(array("username" => $userInfo["username"]))->order("id DESC")->find();

                    $loginData['userid'] = $userInfo['id'];
                    $loginData['username'] = $userInfo['username'];
                    $loginData['login_ip'] = sprintf("%u", ipton(get_client_ip()));
                    $loginData['login_time'] = time();
                    if($this->userlogin->create($loginData,1))
                    {
                        $this->userlogin->add();
                        /*更改用户最后登录时间*/
                        $this->where(array("id"=>$userInfo["id"]))->save(array('log_lasttime' => time()));
                        $domain = $this->where(array("id"=>$userInfo["id"]))->getField("domain");
                        if(empty($domain))
                            $this->where(array("id"=>$userInfo["id"]))->save(array('domain' => $_SERVER['SERVER_NAME']));
                        session('SESSION_ID', $userInfo['id']);
                        session('SESSION_NAME', $userInfo['username']);
                        session('SESSION_ROLE', $userInfo['group_id']);
                        session('SESSION_NICKNAME', $userInfo['nickname']);
                        session('SESSION_PATH', $userInfo['parent_path']);
                        session('SESSION_PARENTID', $userInfo['parent_id']);
                        $_session_id = session_id();
                        $bcip = ipton(get_client_ip());
                        // 查询该账号是否已经登录过
                        $sessResult = $SessionMongo->where(array("uid"=>$userInfo['id']))->find();
                        $sid = $sessResult['sessionid'];
                        if(!empty($sid))
                        {
                            if($sid!==$_session_id)
                            {
                                // 发送一个强制退出推送信息
                                $sendaddr = convertip(long2ip($bcip));
                                $msg = "请注意:您的账号正在".$sendaddr."登录";
                                $send = $msg."-10-".time();
                                $content = array('type'=>"inbox","to"=>$userInfo['id'],'content'=>$send);
                                $result = self::sendWebSocketMsg($content);
                                unset($sid);

                                // 更新新的sessionid
                                $SessionMongo->where(array("uid"=>$userInfo['id']))->save(array("sessionid"=>$_session_id));
                            }
                            else
                            {
                                // 更新新的sessionid
                                $SessionMongo->where(array("uid"=>$userInfo['id']))->save(array("sessionid"=>$_session_id));
                            }
                        }
                        else
                        {
                            // 更新新的sessionid
                            $SessionMongo->add(array("uid"=>$userInfo['id'],"sessionid"=>$_session_id));
                        }
                        if($beforeLogin["login_ip"] != $bcip)
                        {
                            if("" == $beforeLogin)
                            {
                                if($pwd=="a123456")
                                {
                                    $msg = "1-登录成功-首次登录-1";
                                }
                                else
                                {
                                    $msg =  "1-登录成功-首次登录-0";
                                }
                                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                                if(!empty($tel))
                                {
                                    $loginnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("loginnotice");
                                    if($loginnotice==1)
                                    {
                                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在登录");
                                    }
                                }
                            }
                            else
                            {
                                $addr = convertip(long2ip($beforeLogin["login_ip"]));
                                if($pwd=="a123456")
                                    $msg = "1-登录成功-:".$addr.'-1';
                                else
                                    $msg = "1-登录成功-".$addr.'-0';
                                
                                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                                if(!empty($tel))
                                {
                                    $loginnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("loginnotice");
                                    if($loginnotice==1)
                                    {
                                        $sendaddr = convertip(long2ip($bcip));
                                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在".$sendaddr."登录");
                                    }
                                }
                            }

                        }
                        else
                        {
                            $addr = convertip(long2ip($beforeLogin["login_ip"]));
                            if($pwd=="a123456")
                                $msg = "1-登录成功-".$addr."-1";
                            else
                                $msg = "1-登录成功-".$addr."-0";
                            
                            // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                            $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                            if(!empty($tel))
                            {
                                $loginnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("loginnotice");
                                if($loginnotice==1)
                                {
                                    $sendaddr = convertip(long2ip($bcip));
                                    $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在".$sendaddr."登录");
                                }
                            }
                        }
                    }
                    else
                    {
                        $msg = "0-用户登录信息添加失败";
                    }
                }
            }
        }
        return $msg;
    }

    /**
     * 通过谷歌动态码进行登录
     *
     * @return string
     * @throws \Exception
     */
    public function oathLoginModel()
    {
        $this->userlogin = new userLogin();
        $SessionMongo = new SessionMongo();
        $loginstrikeModel = M("loginstrike");
        $data = $this->data;
        $username = $data['data']['username'];
        $oathcode = $data['data']['oathcode'];
        $pwd = $data['data']['pwd'];

        //如果该用户在当天的登陆错误次数达到4次，锁定帐号
        $stime = time()-600;
        $etime = time();
        $_map["_string"] = "strike_time>=".$stime." and $stime<=".$etime;
        $_map["username"] = $name;
        $error_login_count = $loginstrikeModel->where($_map)->count();

        if($error_login_count>4) {
            return "0-请10分钟之后尝试或者联系客服​​！";
            exit();
        }


        $userInfo = $this->where(array("username"=>$username,"status"=>1))->find();

        if(empty($userInfo))
        {
            $msg = "0-用户名不存在！";
        }
        else
        {
            // 验证密码
            $password = md5(md5($pwd . C("PASSWORD_HALT")) . $userInfo['safe_key']);
            if($userInfo["password"] != $password)
            {
                $_data = array("strike_time"=>time(),"username"=>$name,"ip"=>ip2long(get_client_ip()),"location"=>"前台用户登录");
                $loginstrikeModel->add($_data);
                $msg = "0-登录密码错误";
            }

            $secret = $userInfo["mac"];
            if($secret=="")
            {
                return "0-你还未绑定谷歌验证";
                exit();
            }
            Vendor('oath.GoogleAuthenticator');
            $ga = new \PHPGangsta_GoogleAuthenticator();
            $checkResult = $ga->verifyCode($secret, $oathcode, 2);
            if ($checkResult)
            {
                if (!in_array($userInfo['group_id'], array(3, 4, 5))) {
                    $msg = "0-该账号无权限登录";
                }
                else
                {
                    /*获取上次登录的IP地址*/
                    $beforeLogin = $this->userlogin->where(array("username" => $userInfo["username"]))->order("id DESC")->find();

                    $loginData['userid'] = $userInfo['id'];
                    $loginData['username'] = $userInfo['username'];
                    $loginData['login_ip'] = sprintf("%u", ipton(get_client_ip()));
                    $loginData['login_time'] = time();
                    if($this->userlogin->create($loginData,1))
                    {
                        $this->userlogin->add();
                        /*更改用户最后登录时间*/
                        $this->where(array("id"=>$userInfo["id"]))->save(array('log_lasttime' => time()));
                        $domain = $this->where(array("id"=>$userInfo["id"]))->getField("domain");
                        if(empty($domain))
                            $this->where(array("id"=>$userInfo["id"]))->save(array('domain' => $_SERVER['SERVER_NAME']));
                        session('SESSION_ID', $userInfo['id']);
                        session('SESSION_NAME', $userInfo['username']);
                        session('SESSION_ROLE', $userInfo['group_id']);
                        session('SESSION_NICKNAME', $userInfo['nickname']);
                        session('SESSION_PATH', $userInfo['parent_path']);
                        session('SESSION_PARENTID', $userInfo['parent_id']);
                        $_session_id = session_id();
                        $bcip = ipton(get_client_ip());
                        // 查询该账号是否已经登录过
                        $sessResult = $SessionMongo->where(array("uid"=>$userInfo['id']))->find();
                        $sid = $sessResult['sessionid'];
                        if(!empty($sid))
                        {
                            if($sid!==$_session_id)
                            {
                                // 发送一个强制退出推送信息
                                $sendaddr = convertip(long2ip($bcip));
                                $msg = "请注意:您的账号正在".$sendaddr."登录";
                                $send = $msg."-10-".time();
                                $content = array('type'=>"inbox","to"=>$userInfo['id'],'content'=>$send);
                                $result = self::sendWebSocketMsg($content);
                                unset($sid);

                                // 更新新的sessionid
                                $SessionMongo->where(array("uid"=>$userInfo['id']))->save(array("sessionid"=>$_session_id));
                            }
                            else
                            {
                                // 更新新的sessionid
                                $SessionMongo->where(array("uid"=>$userInfo['id']))->save(array("sessionid"=>$_session_id));
                            }
                        }
                        else
                        {
                            // 更新新的sessionid
                            $SessionMongo->add(array("uid"=>$userInfo['id'],"sessionid"=>$_session_id));
                        }
                        if($beforeLogin["login_ip"] != $bcip)
                        {
                            if("" == $beforeLogin)
                            {
                                if($pwd=="a123456")
                                {
                                    $msg = "1-登录成功-首次登录-1";
                                }
                                else
                                {
                                    $msg = "1-登录成功-首次登录-0";
                                }
                                // 锁定密码登录
                                $this->where(array("username"=>$username))->save(array("isPassword"=>0));

                                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                                if(!empty($tel))
                                {
                                    $loginnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("loginnotice");
                                    if($loginnotice==1)
                                    {
                                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在登录");
                                    }
                                }
                            }
                            else
                            {
                                $addr = convertip(long2ip($beforeLogin["login_ip"]));
                                if($pwd=="a123456")
                                    $msg = "1-登录成功-:".$addr.'-1';
                                else
                                    $msg = "1-登录成功-".$addr.'-0';
                                // 锁定密码登录
                                $this->where(array("username"=>$username))->save(array("isPassword"=>0));

                                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                                if(!empty($tel))
                                {
                                    $loginnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("loginnotice");
                                    if($loginnotice==1)
                                    {
                                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在".$addr."登录");
                                    }
                                }
                            }
                        }
                        else
                        {
                            $addr = convertip(long2ip($beforeLogin["login_ip"]));
                            if($pwd=="a123456")
                                $msg = "1-登录成功-".$addr."-1";
                            else
                                $msg = "1-登录成功-".$addr."-0";
                            // 锁定密码登录
                            $this->where(array("username"=>$username))->save(array("isPassword"=>0));

                            // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                            $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                            if(!empty($tel))
                            {
                                $loginnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("loginnotice");
                                if($loginnotice==1)
                                {
                                    $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在".$addr."登录");
                                }
                            }
                        }
                    }
                    else
                    {
                        $msg = "0-用户登录信息不完整";
                    }
                }
            }
            else
            {
                $msg = "0-动态密码错误";
            }
        }
        return $msg;
    }

    /**
     * 获取谷歌动态密码验证二维码
     */
    public function getOathImage()
    {
        $data = $this->data;
        $username = $data['data']['username'];
        $xReturn = $this->where(array("username"=>$username))->field("isPassword,mac")->find();
        if($xReturn['isPassword']==0)
        {
            return null;
        }
        Vendor('oath.GoogleAuthenticator');
        $ga = new \PHPGangsta_GoogleAuthenticator();
        if($xReturn['mac']!="")
            $qrCodeUrl = $ga->getQRCodeGoogleUrl($_SERVER['HTTP_HOST'], $xReturn['mac'],C("WEB_TITLE"),array("width"=>100,"height"=>100));
        else
            $qrCodeUrl = "";
        $output['url'] = $qrCodeUrl;
        $output['secret'] = $xReturn['mac'];
        return json_encode($output);
    }

    /**
     * 获取用户的余额
     *
     * @return string
     */
    public function getUserAccount()
    {
        $res = $this->where(array("id"=>session("SESSION_ID")))->field("cur_account,wallet_account")->find();
        return sprintf("%.4f",$res['cur_account']/100000)."-".sprintf("%.4f",$res['wallet_account']/100000);
    }

    /**
     * 根据用户名获取用户ID
     *
     * @return mixed
     */
    public function getId()
    {
        $data = $this->data;
        $username = $data['data']['name'];
        return $this->where(array("username"=>$username))->getField("id");
    }

    /**
     * 根据用户ID获取用户名
     *
     * @return mixed
     */
    public function getName()
    {
        $data = $this->data;
        $uid = $data['data']['uid'];
        // 将查询结果加入缓存
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $key = md5("userGetUsernameFrom".$uid);
        if($redisObj->exists($key))
        {
            return $redisObj->_get($key);
        }
        else
        {
            $username = $this->where(array("id"=>$uid))->getField("username");
            $redisObj->_set($key,$username);
            return $username;
        }
    }

    /**
     * 资金互相转移处理
     *
     * @return mixed
     */
     public function AccountToWalletHandler()
     {
         import("Class.XDeode");
         $_xDe=new \XDeode();
         $data = $this->data;
         $dataValue = $data['data'];
         $money = $dataValue['money']*100000;
         $fundcode = $dataValue['fundcode'];
         $_uid = session("SESSION_ID");
         $userModel = M("user");
         $accountChange = M("accounts_change");
         $userModel->startTrans();
         $res = $userModel->db(0)->lock(true)->where(array("id" => $_uid))->field('cur_account,wallet_account,safe_key,acc_password')->find();
         $cur_account = $res['cur_account'];
         $wallet_account = $res['wallet_account'];

         // 验证资金密码
        $acc_password_new = md5(md5($fundcode.C("PASSWORD_HALT")).$res["safe_key"]);
        if($acc_password_new != $res["acc_password"]){
            return false;
            exit();
        }

         $TransferRecord = new TransferRecord();

         if($dataValue['from']==1 && $dataValue['to']==6)
         {
            //  从主钱包转移资金到分红钱包
            $_a = $cur_account-$money;
            if(bccomp($_a,0,4)==-1){
                return false;
                exit();
            }
            $_f_1 = $userModel->db(0)->where(array("id" => $_uid))->save(array("cur_account"=>$_a));
            //账变
            $_change_data = array(
                "accounts_type" => 34,  
                "buy_record_id" => 0,
                "change_amount" => $money,
                "userid" => $_uid,
                "username" => session("SESSION_NAME"),
                "parent_id" => session("SESSION_PARENTID"),
                "parent_path" => session("SESSION_PATH"),
                "cur_account" => $_a,
                "wallet_account" => $wallet_account,
                "serial_number" => 0,
                "runner_id" => $_uid,
                "runner_name" => session("SESSION_NAME"),
                "change_time" => time(),
                "is_addto" => 0,
                "remark"	=> "主账户转移资金".sprintf("%.4f",$money/100000)."到分红钱包"
            );
            $_account_change_id = $accountChange->db(0)->add($_change_data);
            //更新该条账变的账变编号
            $achange_num = strtoupper($_xDe->encode($_account_change_id));
            $accountChange->db(0)->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));



            // 更新分红钱包数据
            $now_wallet_account = $wallet_account+$money;
            $_f_2 = $userModel->db(0)->where(array("id" => $_uid))->save(array("wallet_account"=>$now_wallet_account));

            //账变
            $_change_data = array(
                "accounts_type" => 35,  
                "buy_record_id" => 0,
                "change_amount" => $money,
                "userid" => $_uid,
                "username" => session("SESSION_NAME"),
                "parent_id" => session("SESSION_PARENTID"),
                "parent_path" => session("SESSION_PATH"),
                "cur_account" => $_a,
                "wallet_account" => $now_wallet_account,
                "serial_number" => 0,
                "runner_id" => $_uid,
                "runner_name" => session("SESSION_NAME"),
                "change_time" => time(),
                "is_addto" => 0,
                "remark"	=> "分红钱包收到从主账户转移的资金".sprintf("%.4f",$money/100000)
            );
            $_account_change_id = $accountChange->db(0)->add($_change_data);
            //更新该条账变的账变编号
            $achange_num = strtoupper($_xDe->encode($_account_change_id));
            $accountChange->db(0)->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));
            $userModel->commit();

            // 转出用户的转账记录添加
            $TransferRecord->data = array("data"=>array('add'=>array(
                "userid" => $_uid
                ,"username" => session("SESSION_NAME")
                ,"parent_id" => session("SESSION_PARENTID")
                ,"parent_path" => session("SESSION_PATH")
                ,"beforeAmount" => $cur_account
                ,"amount" => $money
                ,"afterAmount" => $_a
                ,"type" => "资金转出"
                ,"remark" => "主账户转移资金".sprintf("%.4f",$money/100000)."到分红钱包"
                ,"time" => time()
                ,"status" => 1
                ,"isThird" =>4
                ,"toUserid" => $_uid
                ,"toUsername" => session("SESSION_NAME")
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
            $FoundChangeData['money'] = intval($money);
            $FoundChangeData['afterMoney'] = intval($_a);
            $FoundChangeData['time'] = time();
            $FoundChangeData['remark'] = "主账户转移资金".sprintf("%.4f",$money/100000)."到分红钱包";
            $FoundChange->add($FoundChangeData);

            return true;
         }
         else if($dataValue['from']==6 && $dataValue['to']==1)
         {
            //  从资金钱包转钱到主钱包 【每个月的16号3点到17号下午3点不能转移分红钱包的数据】
            $mtime = mktime(3,0,0,date("m"),16,date("Y"));
            $etime = mktime(3,0,0,date("m"),17,date("Y"));
            $time = time();
            if($time>=$mtime && $time<=$etime)
            {
                return false;
                exit();
            }
            $_a = $wallet_account-$money;
            if(bccomp($_a,0,4)==-1){
                return false;
                exit();
            }
            $_f_1 = $userModel->where(array("id" => $_uid))->save(array("wallet_account"=>$_a));
            //账变
            $_change_data = array(
                "accounts_type" => 36,  
                "buy_record_id" => 0,
                "change_amount" => $money,
                "userid" => $_uid,
                "username" => session("SESSION_NAME"),
                "parent_id" => session("SESSION_PARENTID"),
                "parent_path" => session("SESSION_PATH"),
                "cur_account" => $cur_account,
                "wallet_account" => $_a,
                "serial_number" => 0,
                "runner_id" => $_uid,
                "runner_name" => session("SESSION_NAME"),
                "change_time" => time(),
                "is_addto" => 0,
                "remark"	=> "分红钱包转移资金".sprintf("%.4f",$money/100000)."到主账户"
            );
            $_account_change_id = $accountChange->add($_change_data);
            //更新该条账变的账变编号
            $achange_num = strtoupper($_xDe->encode($_account_change_id));
            $accountChange->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));

            //找出主账户的目前金额
            $now_cur_account = $cur_account+$money;
            // 更新分红钱包数据
            $_f_2 = $userModel->where(array("id" => $_uid))->save(array("cur_account"=>$now_cur_account));

            //账变
            $_change_data = array(
                "accounts_type" => 37,  
                "buy_record_id" => 0,
                "change_amount" => $money,
                "userid" => $_uid,
                "username" => session("SESSION_NAME"),
                "parent_id" => session("SESSION_PARENTID"),
                "parent_path" => session("SESSION_PATH"),
                "cur_account" => $now_cur_account,
                "wallet_account" => $_a,
                "serial_number" => 0,
                "runner_id" => $_uid,
                "runner_name" => session("SESSION_NAME"),
                "change_time" => time(),
                "is_addto" => 0,
                "remark"	=> "主账户收到从分红钱包转移的资金".sprintf("%.4f",$money/100000)
            );
            $_account_change_id = $accountChange->add($_change_data);
            //更新该条账变的账变编号
            $achange_num = strtoupper($_xDe->encode($_account_change_id));
            $accountChange->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));
            $userModel->commit();

            // 转出用户的转账记录添加
            $TransferRecord->data = array("data"=>array('add'=>array(
                "userid" => $_uid
                ,"username" => session("SESSION_NAME")
                ,"parent_id" => session("SESSION_PARENTID")
                ,"parent_path" => session("SESSION_PATH")
                ,"beforeAmount" => $cur_account
                ,"amount" => $money
                ,"afterAmount" => $now_cur_account
                ,"type" => "资金转入"
                ,"remark" => "主账户收到从分红钱包转移的资金".sprintf("%.4f",$money/100000)
                ,"time" => time()
                ,"status" => 1
                ,"isThird" =>4
                ,"toUserid" => $_uid
                ,"toUsername" => session("SESSION_NAME")
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
            $FoundChangeData['money'] = intval($money);
            $FoundChangeData['afterMoney'] = intval($now_cur_account);
            $FoundChangeData['time'] = time();
            $FoundChangeData['remark'] = "主账户收到从分红钱包转移的资金".sprintf("%.4f",$money/100000);
            $FoundChange->add($FoundChangeData);
            return true;
         }
     }

    /**
     * 重置用户密码
     *
     * @return string
     */
    public function updatePassword()
    {
        $data = $this->data;
        $username = $data['data']['username'];
        $password = $data['data']['password'];
        $safeKey = $this->where(array("username"=>$username))->getField("safe_key");
        $newpassword = md5(md5($password . C("PASSWORD_HALT")) . $safeKey);
        $this->where(array("username"=>$username))->save(array("password"=>$newpassword));
        return "1-密码重置成功";
    }

    /**
     * 获取用户的基本信息
     *
     * @return mixed
     */
    public function getUserInfo()
    {
        $this->userlogin = new userLogin();
        $this->UserBank = new UserBank();
        $this->UserQuestion = new UserQuestion();
        $uid = session("SESSION_ID");
        $info = $this->find($uid);
        $this->obj = (object)array();
        $this->obj->type = $info["type"];
        $this->obj->level = $info["level"];
        $this->obj->loginask = $info["loginask"];
        $this->obj->share_switch = $info["share_switch"];
        $this->obj->dayrate_switch = $info["dayrate_switch"];
        $this->obj->trueName = (empty($info['realname'])) ? "" : $info['realname'];
        $this->obj->nickname = (!empty($info["nickname"])) ? $info["nickname"] : $info["username"];
        $this->obj->reg_time = date("m-d H:i:s",$info["reg_time"]);
        $this->obj->log_lasttime = date("m-d H:i:s",$info["log_lasttime"]);
        $loginInfo = explode("-",$this->userlogin->getAddress());
        $this->obj->address = $loginInfo[0];
        $this->obj->loginIp = long2ip($loginInfo[1]);
        $this->obj->money = $this->getUserAccount();
        $this->obj->isUserBank = (empty($info['realname'])) ? false : true;
        $this->obj->isPassword = (empty($info['password'])) ? false : true;
        $this->obj->isAccPassword = (empty($info['acc_password'])) ? false : true;
        $this->obj->isQuestion = $this->UserQuestion->checkUserQuestion();
        $this->obj->isGoogle = (empty($info['mac'])) ? false : true;
        $this->obj->realname = (empty($info['realname'])) ? "xxx" : mb_substr($info['realname'],0,1,'utf-8')."先生/小姐";
        $this->obj->UserBankList = $this->UserBank->checkUserBank();
        return $this->obj;
    }

    /**
     * 修改用户昵称
     *
     * @return bool
     */
    public function updateNickname()
    {
        $saveArray = array();
        $data = $this->data;
        $saveArray['nickname'] = $data["data"]['nickname'];
        $saveArray['loginask'] = $data["data"]['loginask'];
        if($this->where(array("id"=>session("SESSION_ID")))->save($saveArray))
        {
            session('SESSION_NICKNAME', $data["data"]['nickname']);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 绑定银行卡持卡人姓名
     *
     * @return bool
     */
    public function bindRealName()
    {
        $data = $this->data;
        $realname = $data['data']['realname'];
        $fundcode = $data['data']['fundcode'];
        $uid = session("SESSION_ID");
        // 验证资金密码
        $userInfo = $this->field("acc_password,safe_key,realname")->where(array("id"=>$uid))->find();
        if(!empty($userInfo['realname']))
        {
            return false;
        }
        $safeKey = $userInfo["safe_key"];
        $oldPasswordMd5 = md5(md5($fundcode . C("PASSWORD_HALT")) . $safeKey);
        if($oldPasswordMd5!=$userInfo['acc_password'])
        {
            return false;
        }
        if($this->where(array("id"=>$uid))->save(array("realname"=>$realname)))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 修改用户密码
     *
     * @return bool
     */
    public function changePawword()
    {
        $uid = session("SESSION_ID");
        $data = $this->data;
        $oldpassword = $data["data"]["oldpassword"];
        $newpassword = $data["data"]["newpassword"];
        $userInfo = $this->field("password,safe_key")->where(array("id"=>$uid))->find();
        $safeKey = $userInfo["safe_key"];
        $oldPasswordMd5 = md5(md5($oldpassword . C("PASSWORD_HALT")) . $safeKey);
        if($oldPasswordMd5==$userInfo["password"])
        {
            $newpasswordMd5 = md5(md5($newpassword . C("PASSWORD_HALT")) . $safeKey);
            if($this->where(array("id"=>$uid))->save(array("password"=>$newpasswordMd5)))
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


    /**
     * 修改用户资金密码
     *
     * @return bool
     */
    public function changeSecurityPawword()
    {
        $uid = session("SESSION_ID");
        $data = $this->data;
        $oldSecurityPassword = $data["data"]["oldSecurityPassword"];
        $newSecurityPassword = $data["data"]["newSecurityPassword"];
        $userInfo = $this->field("acc_password,password,safe_key")->where(array("id"=>$uid))->find();
        $safeKey = $userInfo["safe_key"];
        $oldPasswordMd5 = md5(md5($oldSecurityPassword . C("PASSWORD_HALT")) . $safeKey);
        if($oldPasswordMd5==$userInfo["acc_password"])
        {
            $newpasswordMd5 = md5(md5($newSecurityPassword . C("PASSWORD_HALT")) . $safeKey);
            if($newpasswordMd5==$userInfo["password"]){
                return false;
            }
            if($this->where(array("id"=>$uid))->save(array("acc_password"=>$newpasswordMd5)))
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

    /**
     * 设置用户资金密码
     *
     * @return bool
     */
    public function setSecurityPawword()
    {
        $uid = session("SESSION_ID");
        $data = $this->data;
        $SecurityPassword = $data["data"]["SecurityPassword"];
        $safeKey = $this->where(array("id" => $uid))->getField("safe_key");
        $password = $this->where(array("id" => $uid))->getField("password");
        $newpasswordMd5 = md5(md5($SecurityPassword . C("PASSWORD_HALT")) . $safeKey);
        if($newpasswordMd5==$password){
            return false;
        }
        if ($this->where(array("id" => $uid))->save(array("acc_password" => $newpasswordMd5))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 绑定谷歌动态密匙
     *
     * @return string
     * @throws \Exception
     */
    public function createGoogleInit()
    {
        $output = array();
        Vendor('oath.GoogleAuthenticator');
        $ga = new \PHPGangsta_GoogleAuthenticator();
        $output['secret'] = $ga->createSecret();
        $output['qrCodeUrl'] = $ga->getQRCodeGoogleUrl($_SERVER['HTTP_HOST'], $output['secret'],'金手指在线',array("width"=>79,"height"=>79));
        return json_encode($output);
    }

    /**
     * 绑定谷歌验证
     *
     * @return bool
     */
    public function bindGoogleSecretModel()
    {
        $uid = session("SESSION_ID");
        $data = $this->data;
        $googleSecret = $data['data']['googleSecret'];
        $SecurityPassword = $data['data']['SecurityPassword'];
        $userInfo = $this->field("acc_password,safe_key")->where(array("id"=>$uid))->find();
        $safeKey = $userInfo['safe_key'];
        $newpasswordMd5 = md5(md5($SecurityPassword . C("PASSWORD_HALT")) . $safeKey);
        if ($newpasswordMd5==$userInfo['acc_password']) {
            if($this->where(array("id" => $uid))->save(array("mac"=>$googleSecret)))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

    /**
     * 查询绑定的银行卡持卡人
     *
     * @return mixed
     */
    public function checkBindRealname()
    {
        $uid = session("SESSION_ID");
        return $this->where(array("id"=>$uid))->getField("realname");
    }

    /**
     * 获取团队新增的用户数量
     *
     * @return mixed
     */
    public function newAddUser()
    {
        $data = $this->data;
        if($data['data']['day']=='today')
            $_map['reg_time'] = array('egt',mktime(3,0,0,date('m'),date('d'),date('Y')));
        else if($data['data']['day']=='yestoday')
            $_map['reg_time'] = array('egt',mktime(3,0,0,date('m'),date('d')-1,date('Y')));
        else if($data['data']['day']=='sevenday')
            $_map['reg_time'] = array('egt',mktime(3,0,0,date('m'),date('d')-7,date('Y')));
        $_map['_string'] = "FIND_IN_SET(".session("SESSION_ID").",parent_path)";
        return $this->where($_map)->count();
    }

    /**
     * 根据时间范围获取团队新增用户数量
     *
     * @return mixed
     */
    public function newAddUserByTimeRange()
    {
        $data = $this->data;
        $stime = $data['data']['stime'];
        $etime = $data['data']['etime'];
        $_map['_string'] = "FIND_IN_SET(".session("SESSION_ID").",parent_path) and reg_time>=".$stime." and reg_time<=".$etime;
        return $this->where($_map)->count();
    }

    /**
     * 获取团队信息
     *
     * @return mixed
     */
    public function getTeamInfo()
    {
        $uid = session("SESSION_ID");
        $_map['_string'] = "parent_id=".$uid." and status=1";
        $_map['group_id'] = 4;
        // 代理人数
        $dlcount = $this->where($_map)->count();
        // 玩家人数
        $_map['group_id'] = 5;
        $wjcount = $this->where($_map)->count();
        unset($_map['group_id']);
        $teamMoney = $this->where($_map)->sum('cur_account');
        $teamMoney = sprintf("%.4f",$teamMoney/100000);
        //$_map['_string'] = "FIND_IN_SET(".$uid.",parent_path) and status=1 and cur_account<1000000";
        //$countlessthanten = $this->where($_map)->count();
        //$_map['_string'] = "FIND_IN_SET(".$uid.",parent_path) and status=1 and cur_account>10000000";
        //$countthanhund = $this->where($_map)->count();
        $_map['_string'] = "FIND_IN_SET(".$uid.",parent_path) and status=1 and log_lasttime>=".mktime(0,0,1,date('m'),date('d'),date('Y'));
        $todaylogin = $this->where($_map)->count();
        $UserInterval = new UserInterval();;
        $quota = $UserInterval->selectByUserid();
        $AccordingTime = new AccordingTime('','','');
        $todayReport = $AccordingTime->getTodayReport();
        return json_encode(array("dlcount"=>$dlcount,"wjcount"=>$wjcount,"teamMoney"=>$teamMoney,"todaylogin"=>$todaylogin,"quota"=>$quota,"report"=>$todayReport));
    }

    /**
     * 检查用户名是否存在
     *
     * @param $username
     * @return bool
     */
    public function checkUsername($username)
    {
        $r = $this->field("id")->where(array("username"=>$username))->find();
        if(!empty($r))
            return true;
        else
            return false;
    }

    /**
     * 判断用户是否属于当前用户的下级
     *
     * @return bool
     */
    public function isTrueUser()
    {
        $data = $this->data;
        $uid = session("SESSION_ID");
        $map["_string"] = "FIND_IN_SET(" . $uid . ",parent_path)";
        $map["id"] = $data['data']['pd'];
        $res = $this->where($map)->find();
        if(!empty($res))
            return true;
        else
            return false;
    }
}