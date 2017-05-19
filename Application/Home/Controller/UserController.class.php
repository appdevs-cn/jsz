<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:50
 */

namespace Home\Controller;


use Think\Controller;
use Home\Model\UserModel as User;
use Home\Model\QuestionModel as Question;
use Home\Model\UserQuestionModel as UserQuestion;
use Home\Model\UserBankModel as UserBank;
use Home\Model\CommonModel as Common;
use Home\Model\MgGameModel as MgGame;
use Home\Model\PtGameModel as PtGame;
use Home\Model\EbetGameModel as EbetGame;
use Home\Model\AgGameModel as AgGame;
use Think\Think;

class UserController extends CommonController
{
    public $User;
    public $Question;
    public $UserQuestion;

    public function index()
    {
        $this->User = new User();
        $this->menu = "user";
        $this->managermenu = 'user';
        $userInfo = $this->User->getUserInfo();

        if($userInfo->UserBankList!=null)
        {
            $UserBankList = $userInfo->UserBankList;
            $bankNameArr = C("BANKNAME");
            $UserBank = $UserBankList[0];
            $this->bankName = $bankNameArr[$UserBank['bank_id']];
            $this->bankNumber = substr($UserBank['account_num'],0,4)."**** **** ".substr($UserBank['account_num'],-4);
        }
        else
        {
            $this->bankName = '<a href="/User/edit/type/bindbank" class="layui-btn layui-btn-primary layui-btn-mini"><i class="layui-icon">&#xe608;</i>绑 定 银 行 名 称</a>';
            $this->bankNumber = '<a href="/User/edit/type/bindbank" class="layui-btn layui-btn-primary layui-btn-mini"><i class="layui-icon">&#xe608;</i>绑 定 银 行 卡 号</a>';;
        }
        $this->display();
    }

    // 用户资料修改
    public function edit()
    {
        $this->menu = "useredit";
        $this->type = I("get.type","other");
        $this->managermenu = 'user';
        $this->display();
    }

    // 获取昵称和个新签名的信息
    public function getUserBickInfomation()
    {
         $uid = session("SESSION_ID");
         $xReturn = array();
         $result = M("user")->field('nickname,loginask')->where(array("id"=>$uid))->find();
         $xReturn['nickname'] = (!empty($result['nickname'])) ? $result['nickname'] : "";
         $xReturn['loginask'] = (!empty($result['loginask'])) ? $result['loginask'] : "这家伙很懒,什么都没留下!";
         echo json_encode($xReturn);
    }

    // 查询是否绑定银行卡持卡人
    public function getBindRealname()
    {
        $User = new User();
        $xReturn = array();
        $xReturn['realname'] = ($User->checkBindRealname()!="") ? mb_substr($User->checkBindRealname(),0,1)."**" : "";

        $uid = session("SESSION_ID");
        $res = M("user")->field('acc_password')->where(array("id"=>$uid))->find();
        $xReturn['fundcode'] = ($res['acc_password']=="") ? 0 : 1;
        echo json_encode($xReturn);
    }

    // 查询已绑定的银行卡
    public function getBindCard()
    {
        $UserBank = new UserBank();
        $UserBankList = $UserBank->checkUserBank();
        $xReturn = array();
        foreach($UserBankList as $item)
        {
            $temp['id'] = $item['id'];
            $temp['bank'] = C('BANKNAME')[$item['bank_id']];
            $temp['card'] = substr($item['account_num'],0,4)." **** ****".substr($item['account_num'],-4);
            $temp['realname'] = mb_substr($item['realname'],0,1)."**";
            $temp['moren'] = $item['moren'];
            $xReturn[] = $temp;
        }
        echo json_encode($xReturn);
    }

    // 改变银行状态
    public function ChangeBankCardMoren()
    {
        $id = I("post.id");
        if(!empty($id))
        {
            $userBank =  M("user_bank");
            $userBank->where(array("userid"=>session("SESSION_ID")))->save(array("moren"=>0));
            $userBank->where(array("id"=>$id))->save(array("moren"=>1));
        }
    }

    // 查询是否已经设置过资金密码
    public function isSetFundCode()
    {
        $uid = session("SESSION_ID");
        $xReturn = M("user")->field('acc_password')->where(array("id"=>$uid))->find();
        echo ($xReturn['acc_password']=="") ? false : true;
    }

    // 查询是否已经设置过密保问题
    public function isSetSecurity()
    {
        $UserQuestion = new UserQuestion();
        echo $UserQuestion->checkUserQuestion();
    }

    // 查询是否已经设置谷歌验证
    public function isSetGoogle()
    {
        $uid = session("SESSION_ID");
        $xReturn = M("user")->field('mac')->where(array("id"=>$uid))->find();
        echo ($xReturn['mac']=="") ? false : true;
    }

    // 查询是否已经设置过电话号码
    public function isSetTel()
    {
        $UserExtend = M("user_extend");
        $userid = session("SESSION_ID");
        $tel = $UserExtend->where(array("userid"=>$userid))->getField('tel');
        echo ($tel=="" || $tel==0) ?  "" : substr($tel,0,3)."******".substr($tel,-3);
    }

    // 查询是否已经设置过邮箱地址
    public function isSetMail()
    {
        $UserExtend = M("user_extend");
        $userid = session("SESSION_ID");
        $email = $UserExtend->where(array("userid"=>$userid))->getField('email');
        if(!empty($email))
        {
            $emailArray = explode("@",$email);
            $emailArray[0] = substr($emailArray[0],0,2)."***".substr($emailArray[0],-2);
            echo implode("@",$emailArray);
        }
        else
        {
            echo "";
        }
    }

    /**
     * 更新用户昵称和个性签名
     */
    public function updateNickname()
    {
        $User = new User();
        $nickname = I("post.nickname");
        $nickname = (empty($nickname)) ? session("SESSION_NAME") : $nickname;
        $loginask = I("post.loginask");
        $loginask = (empty($loginask)) ? "这家伙很懒,什么都没留下!" : $loginask;

        $User->data = array("nickname"=>$nickname,"loginask"=>$loginask);
        echo $User->updateNickname();
    }

    /**
     * 设置个性签名
     */
    public function updateLogiask()
    {
        $loginask = I("post.loginask");
        if(M("user")->where(array("id"=>session("SESSION_ID")))->save(array("loginask"=>$loginask)))
            echo true;
        else
            echo false;
    }

    // 获取短信验证码
    public function GetVcode()
    {
        $tel = I("post.tel","rtrim");
        if($tel=="")
        {
            echo false;
            exit();
        }
        $Appkey = C("MessageCodeKey");
        $TemplateId = C("TemplateId");
        $param = substr(time(),-6);
        $key = md5("Vcode".session("SESSION_NAME"));
        session($key,null);
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

    // 查询号码是否已经被绑定过
    public function IsCheckTel()
    {
        $tel = I("post.tel");
        $isHave = M("user_extend")->where(array("tel"=>$tel))->find();
        if(!empty($isHave))
        {
            echo false;
        }
        else
        {
            echo true;
        }
    }

    // 绑定手机号码
    public function bindTel()
    {
        $tel = I("post.tel","rtrim");
        $vcode = I("post.vcode","rtrim");
        if(empty($tel) || empty($vcode))
        {
            echo false;
            exit();
        }
        $key = md5("Vcode".session("SESSION_NAME"));
        if($vcode!=session($key))
        {
            echo false;
        }
        else
        {   
            $isHave = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->find();
            if(!empty($isHave))
                M("user_extend")->where(array("userid"=>session("SESSION_ID")))->save(array("tel"=>$tel));
            else
                M("user_extend")->add(array("userid"=>session("SESSION_ID"),"qq"=>"","email"=>"","tel"=>$tel,"imgPath"=>C('HEADIMG'),"img100Path"=>C('TRUMBHEADIMG')));
            session($key,null);
            echo true;
        }
    }

    /**
     * 绑定持卡人姓名
     */
    public function bindRealname()
    {
        $this->User = new User();
        $this->User->data = array("realname"=>I("post.name"),"fundcode"=>I("post.fundcode"));
        echo $this->User->bindRealName();
    }

    /**
     * 修改用户密码
     */
    public function changePassword()
    {
        $this->User = new User();
        $oldpassword = I("post.oldpassword");
        $newpassword = I("post.newpassword");
        $comfirmpassword = I("post.comfirmpassword");
        if(empty($oldpassword) || empty($newpassword) || empty($comfirmpassword))
        {
            echo false;
            exit();
        }
        else
        {
            if($newpassword!=$comfirmpassword)
            {
                echo false;
                exit();
            }
            else
            {
                $this->User->data = array("oldpassword"=>$oldpassword,"newpassword"=>$newpassword);
                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                if(!empty($tel))
                {
                    $updateloginpwdnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("updateloginpwdnotice");
                    if($updateloginpwdnotice==1)
                    {
                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在修改登录密码");
                    }
                }
                echo $this->User->changePawword();
            }
        }
    }

    /**
     * 修改用户资金密码
     */
    public function changeSecurityPassword()
    {
        $this->User = new User();
        $oldSecurityPassword = I("post.oldSecurityPassword");
        $newSecurityPassword = I("post.newSecurityPassword");
        $comfirmSecurityPassword = I("post.comfirmSecurityPassword");
        if(empty($oldSecurityPassword) || empty($newSecurityPassword) || empty($comfirmSecurityPassword))
        {
            echo false;
            exit();
        }
        else
        {
            if($newSecurityPassword!=$comfirmSecurityPassword)
            {
                echo false;
                exit();
            }
            else
            {
                $this->User->data = array("oldSecurityPassword"=>$oldSecurityPassword,"newSecurityPassword"=>$newSecurityPassword);
                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                if(!empty($tel))
                {
                    $updatefundpwdnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("updatefundpwdnotice");
                    if($updatefundpwdnotice==1)
                    {
                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在修改资金密码");
                    }
                }
                echo $this->User->changeSecurityPawword();
            }
        }
    }

    /**
     * 设置资金密码
     */
    public function setSecurityPassword()
    {
        $this->User = new User();
        $SecurityPassword = I("post.SecurityPassword");
        $comfSecurityPassword = I("post.comfSecurityPassword");
        if(empty($SecurityPassword) || empty($comfSecurityPassword))
        {
            echo false;
            exit();
        }
        else
        {
            if($SecurityPassword!=$comfSecurityPassword)
            {
                echo false;
                exit();
            }
            else
            {
                $this->User->data = array("SecurityPassword"=>$SecurityPassword);
                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                if(!empty($tel))
                {
                    $updatefundpwdnotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("updatefundpwdnotice");
                    if($updatefundpwdnotice==1)
                    {
                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在设置资金密码");
                    }
                }
                echo $this->User->setSecurityPawword();
            }
        }
    }

    /**
     * 获取问题列表
     *
     * @return mixed
     */
    public function getQuestion()
    {
        $this->Question = new Question();
        echo json_encode($this->Question->select());
    }

    /**
     * 设置用户密保问题
     */
    public function setQuestion()
    {
        $this->User = new User();
        $this->UserQuestion = new UserQuestion();
        $question = I("post.question");
        $answer = I("post.answer");
        $SecurityPassword = I("post.SecurityPassword");
        if(empty($question) || empty($answer) || empty($SecurityPassword))
        {
            echo false;
            exit();
        }
        else
        {
            $this->UserQuestion->data = array("question"=>$question,"answer"=>$answer,"SecurityPassword"=>$SecurityPassword);
            echo $this->UserQuestion->setQuestionModel();
            exit();
        }

    }

    /**
     * 谷歌动态码生成
     */
    public function createGoogleSecret()
    {
        $User = new User();
        echo $User->createGoogleInit();
    }

    /**
     * 绑定谷歌动态码
     */
    public function bindGoogleSecret()
    {
        $User = new User();
        $googleSecret = I("post.googleSecret","");
        $SecurityPassword = I("post.SecurityPassword","");
        if(empty($googleSecret) || empty($SecurityPassword))
        {
            echo false;
            exit();
        }
        $User->data = array("googleSecret"=>$googleSecret,"SecurityPassword"=>$SecurityPassword);
        echo $User->bindGoogleSecretModel();
    }

    /**
     * 银行的基本信息
     */
    public function isBindRealname()
    {
        $output = array();
        $User = new User();
        $output['isBindRealname'] = (!empty($User->checkBindRealname())) ? mb_substr($User->checkBindRealname(),0,1,'utf-8')."**" : "";
        $output['bindbank'] = json_encode(C('BANKNAME'));
        $output['bindprovince'] = json_encode(C('BANKPROVINCE'));
        echo json_encode($output);
    }

    /**
     * 获取城市列表
     */
    public function getCity()
    {
        $output = array();
        $province = I("post.province");
        $bankcity = C('BANKSITY');
        $output['city'] = json_encode($bankcity[$province]);
        echo json_encode($output);
    }

    /**
     * 绑定银行卡
     */
    public function bindCard()
    {
        $UserBank = new UserBank();
        $bank_id = I("post.bank_id");
        $account_num = I("post.account_num");
        $comfirm_account_num = I("post.comfirm_account_num");
        $bankprov = I("post.bankprov");
        $bankcity = I("post.bankcity");
        $bindSecurityPassword = I("post.bindSecurityPassword");
        if(empty($bank_id) || empty($account_num) || $bankprov=="" || $bankcity=="" || empty($comfirm_account_num) || empty($bindSecurityPassword))
        {
            echo "error4";exit(); //数据提交有错误
        }
        else
        {
            if($account_num!=$comfirm_account_num)
            {
                echo "error5";exit(); //两次输入的账号不一致
            }
            else
            {
                $UserBank->data = array("bank_id"=>$bank_id,"account_num"=>$account_num,"bankprov"=>$bankprov,"bankcity"=>$bankcity,"bindSecurityPassword"=>$bindSecurityPassword);
                // 如果用户绑定了手机，设置过取款短信提醒。那么发送短信提醒
                $tel = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->getField("tel");
                if(!empty($tel))
                {
                    $bindbanknotice = M("sms")->where(array("userid"=>session("SESSION_ID")))->getField("bindbanknotice");
                    if($bindbanknotice==1)
                    {
                        $this->SendSms($tel,"金手指提醒:".session("SESSION_NAME")."正在绑定银行卡");
                    }
                }
                echo $UserBank->bindBankCard();
            }
        }
    }

    /**
     * 删除用户银行卡
     */
    public function delCard()
    {
        $bankid = I("post.bankid");
        $SecurityPassword = I("post.SecurityPassword");
        if(empty($bankid) || empty($SecurityPassword))
        {
            echo false;exit();
        }
        else
        {
            $UserBank = new UserBank();
            $UserBank->data = array("bankid"=>$bankid,"SecurityPassword"=>$SecurityPassword);
            echo $UserBank->delBankCard();
        }
    }

    /**
     * 验证银行卡实名认证
     */
    public function isRealyBankCard()
    {
        $account_num = I("post.account_num");
        $UserBank = new UserBank();
        echo $UserBank->checkUserAndBank($account_num);
    }

    /**
    * 改变银行卡状态
    */
    public function changeBankStatus()
    {
        $id = I("post.filed");
        $UserBank = new UserBank();
        $UserBank->where(array("userid"=>session("SESSION_ID")))->save(array("moren"=>0));
        if($UserBank->where(array("id"=>$id,"userid"=>session("SESSION_ID")))->save(array("moren"=>1)))
        {
            echo "ok";
        }
    }

    // 资金互相转移
    public function AccountToWallet()
    {
        $money = intval(I('post.money'));
        $from = I('post.from');
        $to = I('post.to');
        if($from==$to) return false;
        $User = new User();
        $User->data = array('money'=>$money,'from'=>$from,'to'=>$to);
        echo $User->AccountToWalletHandler();
    }

    /**
     * 在线用户列表
     */
    public function onlineList()
    {
        $uid = session("SESSION_ID");
        $nickname = session("SESSION_NICKNAME");
        $parentid = session("SESSION_PARENTID");
        $userExtend = M("user_extend");
        $loginask = M("user")->where(array("id"=>$uid))->getField("loginask");
        $list = M("user")->where("parent_id=".$uid)->field("id,username,nickname,loginask")->select();
        $UserList = array();
        $avatar = $userExtend->where(array("userid"=>$uid))->getField("img100Path");
        $avatar = ($avatar=="") ? "https://".$_SERVER['HTTP_HOST']."/Uploads/original/20170510/trumb100_59129bea8f9be.png" : $avatar;
        $mine = array("username"=>$nickname,"id"=>$uid,"status"=>"online","sign"=>$loginask,"avatar"=>$avatar);
        $onlineCount=0;
        foreach($list as $item)
        {
            $loginask = (empty($item['loginask'])) ? "这家伙太懒,什么都没留下" : $item['loginask'];
            $avatar = $userExtend->where(array("userid"=>$item['id']))->getField("img100Path");
            $avatar = ($avatar=="") ? "https://".$_SERVER['HTTP_HOST']."/Uploads/original/20170510/trumb100_59129bea8f9be.png" : $avatar;
            $str = array('username'=>$item['nickname']."[".$item['username']."]",'id'=>$item['id'],'avatar'=>$avatar,'remark'=>$loginask);
            array_push($UserList,$str);
            ++$onlineCount;
        }
        $ParentList = array();
        $parentCount = 0;
        $parentList = M("user")->where("id=".$parentid)->field("id,nickname,loginask")->select();
        foreach($parentList as $item)
        {
            $loginask = (empty($item['loginask'])) ? "这家伙太懒,什么都没留下" : $item['loginask'];
            $avatar = $userExtend->where(array("userid"=>$item['id']))->getField("img100Path");
            $avatar = ($avatar=="") ? "https://".$_SERVER['HTTP_HOST']."/Uploads/original/20170510/trumb100_59129bea8f9be.png" : $avatar;
            $str = array('username'=>$item['nickname']."[".$item['username']."]",'id'=>$item['id'],'avatar'=>$avatar,'remark'=>$loginask);
            array_push($ParentList,$str);
            ++$parentCount;
        }
        $kefuList = array(
            array('username'=>"小薇",'id'=>10000,'avatar'=>'https://'.$_SERVER['HTTP_HOST'].'/Uploads/original/20170510/trumb100_59129bea8f9be.png','remark'=>"目前请使用在线客服")
        );
        echo '{
              "code": 0
              ,"msg": ""
              ,"data": {
                "mine": '.json_encode($mine).'
                ,"friend": [{
                  "groupname": "下级用户"
                  ,"id": 1
                  ,"online": '.$onlineCount.'
                  ,"list": '.json_encode($UserList).'
                },{
                  "groupname": "上级用户"
                  ,"id": 1
                  ,"online": '.$parentCount.'
                  ,"list": '.json_encode($ParentList).'
                },{
                  "groupname": "在线客服"
                  ,"id": 10000
                  ,"online": 1
                  ,"list": '.json_encode($kefuList).'
                }]
              }
            }';
    }

    public function uploadfile()
    {
        $config = array(
            'maxSize'    =>    1000*1000,
            'rootPath'   =>    './Uploads/original/',
            'savePath'   =>    '',
            'saveName'   =>    array('uniqid',''),
            'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub'    =>    true,
            'subName'    =>    array('date','Ymd'),
        );
        $upload = new \Think\Upload($config);

        $info = $upload->uploadOne($_FILES['head']);
        if(!$info)
        {
            echo json_encode(array("status"=>false,"result"=>"上传头像失败"));
        }
        else
        {
            $path = '/Uploads/original/'.$info['savepath'].$info['savename'];

            $image = new \Think\Image();
            $image->open(".".$path);
            $image->thumb(42, 42, \Think\Image::IMAGE_THUMB_SCALE)->save("./Uploads/original/".$info['savepath']."trumb_".$info['savename']);
            $thumbPath = "/Uploads/original/".$info['savepath']."trumb_".$info['savename'];
            $image->open(".".$path);
            $image->thumb(200, 200, \Think\Image::IMAGE_THUMB_SCALE)->save("./Uploads/original/".$info['savepath']."trumb100_".$info['savename']);
            $thumb100Path = "/Uploads/original/".$info['savepath']."trumb100_".$info['savename'];
            M("user_extend")->where(array("userid"=>session("SESSION_ID")))->delete();

            $res = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->find();
            if(empty($res))
            {
                M("user_extend")->add(array(
                    "userid"=>session("SESSION_ID")
                    ,"imgPath"=>$thumbPath
                    ,"img100Path" => $thumb100Path
                ));
            }
            else
            {
                M("user_extend")->where(array("userid"=>session("SESSION_ID")))->save(array("imgPath"=>$thumbPath,"img100Path"=>$thumb100Path));
            }
            
            echo json_encode(array("status"=>true,"result"=>$thumb100Path));
        }
    }

    // 获取用户综合信息
    public function getUserInfomation()
    {
        $xReturn = array();
        $User = new user();
        $this->userInfo = $User->getUserInfo();
        $count = 0;
        if($this->userInfo->isUserBank) ++$count;
        if($this->userInfo->isPassword) ++$count;
        if($this->userInfo->isAccPassword) ++$count;
        if($this->userInfo->isQuestion) ++$count;
        if($this->userInfo->isGoogle) ++$count;
        if($count==1)
        {
            $lecelStatus = "很低";
            $score = 20;
        } 
        else if($count==2)
        {
            $lecelStatus = "低";
            $score = 40;
        }
        else if($count==3)
        {
            $lecelStatus = "中";
            $score = 60;
        }
        else if($count==4)
        {
            $lecelStatus = "中高";
            $score = 80;
        }
        else if($count==5)
        {
            $lecelStatus = "高";
            $score = 100;
        }
        $xReturn['lecelStatus'] = $lecelStatus;
        $xReturn['score'] = $score;

        $info = $User->getUserInfo();
        $xReturn['log_lasttime'] = $info->log_lasttime;
        $xReturn['loginIp'] = $info->loginIp;
        $xReturn['address'] = $info->address;

        $res = M("user")->where(array("id"=>session("SESSION_ID")))->field("cur_account,wallet_account")->find();
        $xReturn['cur_account'] = number_format(sprintf("%.4f",$res['cur_account']/100000),4,",",".");
        $xReturn['wallet_account'] = number_format(sprintf("%.4f",$res['wallet_account']/100000),4,",",".");

        // 查询MG的余额
        $mg = M("mg_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(empty($mg))
        {
            $xReturn['mg_account'] = number_format(0,2,",",".");
        }
        else
        {
            $MgGame = new MgGame();
            $mgAccount = $MgGame->searchUserBalance(session("SESSION_NAME"));
            $xReturn['mg_account'] = number_format($mgAccount,2,",",".");
        }

        // 查询EBET余额
        $ebet = M("ebet_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(empty($ebet))
        {
            $xReturn['ebet_account'] = number_format(0,2,",",".");
        }
        else
        {
            $EbetGame = new EbetGame();
            $ebetAccount = $EbetGame->searchUserBalance(session("SESSION_NAME"));
            $xReturn['ebet_account'] = number_format($ebetAccount,2,",",".");
        }

        // 查询PT余额
        $PtGame = new PtGame();
        $isHave = M("pt_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($isHave))
        {
            $ptAccount = $PtGame->SearchUserBlance(session('SESSION_NAME'));
            $xReturn['pt_account'] = number_format($ptAccount,2,",",".");
        }
        else
        {
            $xReturn['pt_account'] = "0.00";
        }

        // 查询AG余额
        $AgGame = new AgGame();
        $isHave = M("ag_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($isHave))
        {
            $agAccount = $AgGame->SearchUserBlance(session('SESSION_NAME'));
            $xReturn['ag_account'] = number_format($agAccount,2,",",".");
        }
        else
        {
            $xReturn['ag_account'] = "0.00";
        }
        
        echo json_encode($xReturn);
    }


    // 查询邮箱是否已经被绑定过
    public function IsCheckEmail()
    {
        $email = I("post.email");
        $isHave = M("user_extend")->where(array("email"=>$email))->find();
        if(!empty($isHave))
        {
            echo false;
        }
        else
        {
            echo true;
        }
    }

    // 发送电子邮件
    public function SendEmailVcode()
    {

        $email = I("post.email");

        Vendor('phpmailer.phpmailer');
        
        $mail = new \PHPMailer(true);

        $mail->IsSMTP();

        $SMTP = C("SMTP");
        $STMPEMAIL = C("STMPEMAIL");
        $STMPEMAILPASSWORD = C("STMPEMAILPASSWORD");

        try {
            $mail->CharSet ="UTF-8";
            $mail->Host       = $SMTP; 
            $mail->SMTPDebug  = 1;
            $mail->SMTPAuth   = true;
            $mail->Port       = 465;
            $mail->SMTPSecure = "ssl";

            $mail->Username   = $STMPEMAIL; //SMTP服务器的用户帐号
            $mail->Password   = $STMPEMAILPASSWORD;        //SMTP服务器的用户密码
            $mail->AddReplyTo($STMPEMAIL, '回复'); //收件人回复时回复到此邮箱,可以多次执行该方法
            $mail->AddAddress($email, '金手指'); 
            $mail->SetFrom($STMPEMAIL, '发件人');//发件人的邮箱
            $mail->Subject = '金手指邮箱绑定验证';

            //以下是邮件内容
            $key = "EmailVcode".session("SESSION_NAME");
            $vcode = substr(time(),-6);
            session($key,$vcode);
            $mail->Body = '金手指邮箱绑定验证:'.$vcode;
            $mail->IsHTML(false);


            $mail->Send();
            echo true;
            } catch (phpmailerException $e) {
                echo false;
            } catch (Exception $e) {
                echo false;
        }
    }

    // 绑定电子邮件
    public function BindEmail()
    {
        $email = I("post.email");
        $emailVcode = I("post.emailVcode");

        if(empty($email) || empty($emailVcode))
        {
            echo false;
            exit();
        }

        $key = "EmailVcode".session("SESSION_NAME");
        if(rtrim($emailVcode)!=session($key))
        {
            echo false;
            exit();
        }
        else
        {
            $isHave = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->find();
            if(!empty($isHave))
                M("user_extend")->where(array("userid"=>session("SESSION_ID")))->save(array("email"=>$email));
            else
                M("user_extend")->add(array("userid"=>session("SESSION_ID"),"qq"=>"","email"=>$email,"tel"=>"","imgPath"=>C('HEADIMG'),"img100Path"=>C('TRUMBHEADIMG')));
            session($key,null);
            echo true;
        }
    }

    // 获取第三方游戏账号余额
    public function SearchUserThriedAccount()
    {
        $xReturn = array();
        // 获取MG游戏账户余额
        $MgGame = new MgGame();
        $isHave = M("mg_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($isHave))
        {
            $mgAccount = $MgGame->searchUserBalance(session('SESSION_NAME'));
            $xReturn['mgAccount'] = number_format($mgAccount,2,",",".");
        }
        else
        {
            $xReturn['mgAccount'] = "0.00";
        }
        
        // 获取Ebet游戏的账户余额
        $EbetGame = new EbetGame();
        $isEbetHave = M("ebet_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($isEbetHave))
        {
            $ebetAccount = $EbetGame->searchUserBalance(session('SESSION_NAME'));
            $xReturn['ebetAccount'] = number_format($ebetAccount,2,",",".");
        }
        else
        {
            $xReturn['ebetAccount'] = "0.00";
        }

        // 获取PT游戏账户余额
        $PtGame = new PtGame();
        $isHave = M("pt_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($isHave))
        {
            $ptAccount = $PtGame->SearchUserBlance(session('SESSION_NAME'));
            $xReturn['ptAccount'] = number_format($ptAccount,2,",",".");
        }
        else
        {
            $xReturn['ptAccount'] = "0.00";
        }

        // 获取AG游戏账户余额
        $AgGame = new AgGame();
        $isHave = M("ag_user")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($isHave))
        {
            $agAccount = $AgGame->SearchUserBlance(session('SESSION_NAME'));
            $xReturn['agAccount'] = number_format($agAccount,2,",",".");
        }
        else
        {
            $xReturn['agAccount'] = "0.00";
        }

        echo json_encode($xReturn);
    }

    // 资金转移
    public function Transfer()
    {
        $money = intval(I('post.money'));
        $from = I('post.from');
        $to = I('post.to');
        $fundcode = I("post.fundcode");
        $username = session("SESSION_NAME");
        if($from==$to || ($from!=1 && $to!=1)) return false;
        $User = new User();
        if($from==1)
        {
            if($to==2)
            {
                // 查询是否已经开通MG游戏
                $mgusername = strtolower("jsz_".$username);
                $isHave = M("mg_user")->where(array("mg_username"=>$mgusername))->find();
                if(!empty($isHave))
                {
                    $MgGame = new MgGame();
                    echo $MgGame->transferToMg($username,$money,$fundcode);// 向MG转移金额
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($to==3)
            {
                // 查询是否已经开通EBET游戏
                $ebetusername = strtolower("ebet_".$username);
                $isHave = M("ebet_user")->where(array("ebet_username"=>$ebetusername))->find();
                if(!empty($isHave))
                {
                    $EbetGame = new EbetGame();
                    echo $EbetGame->transferToEbet($username,$money,$fundcode);// 向Ebet转移金额
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($to==4)
            {
                // 查询是否已经开通ag游戏
                $agusername = strtolower("jsz".$username);
                $isHave = M("ag_user")->where(array("ag_username"=>$agusername))->find();
                if(!empty($isHave))
                {
                    $AgGame = new AgGame();
                    echo $AgGame->transferToAg($username,$money,$fundcode);// 向Ag转移金额
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($to==5)
            {
                // 查询是否已经开通pt游戏
                $ptusername = strtoupper("EBJSZ_".$username);
                $isHave = M("pt_user")->where(array("EBJSZ_username"=>$ptusername))->find();
                if(!empty($isHave))
                {
                    $PtGame = new PtGame();
                    echo $PtGame->transferToPt($username,$money,$fundcode);// 向Pt转移金额
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($to==6)
            {
                $User->data = array("fundcode"=>$fundcode,"money"=>$money,"from"=>$from,"to"=>$to);
                echo $User->AccountToWalletHandler();// 向分红钱包转移金额
                exit();
            }
        }
        else if($to==1)
        {
            if($from==2)
            {

                // 查询是否已经开通MG游戏
                $mgusername = strtolower("jsz_".$username);
                $isHave = M("mg_user")->where(array("mg_username"=>$mgusername))->find();
                if(!empty($isHave))
                {
                    $MgGame = new MgGame();
                    echo $MgGame->transferToCp($username,$money,$fundcode);// MG转移金额到平台
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($from==3)
            {
                // 查询是否已经开通EBET游戏
                $ebetusername = strtolower("ebet_".$username);
                $isHave = M("ebet_user")->where(array("ebet_username"=>$ebetusername))->find();
                if(!empty($isHave))
                {
                    $EbetGame = new EbetGame();
                    echo $EbetGame->transferToCp($username,$money,$fundcode);// Ebet转移金额到平台
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($from==4)
            {
                // 查询是否已经开通ag游戏
                $agusername = strtolower("jsz".$username);
                $isHave = M("ag_user")->where(array("ag_username"=>$agusername))->find();
                if(!empty($isHave))
                {
                    $AgGame = new AgGame();
                    echo $AgGame->transferToCp($username,$money,$fundcode);// Ag转移金额转移金额到平台
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($from==5)
            {
                // 查询是否已经开通pt游戏
                $ptusername = strtoupper("EBJSZ_".$username);
                $isHave = M("pt_user")->where(array("EBJSZ_username"=>$ptusername))->find();
                if(!empty($isHave))
                {
                    $PtGame = new PtGame();
                    echo $PtGame->transferToCp($username,$money,$fundcode);// Pt转移金额到平台
                    exit();
                }
                else
                {
                    echo false;
                    exit();
                }
            }
            if($from==6)
            {
                $User->data = array("fundcode"=>$fundcode,"money"=>$money,"from"=>$from,"to"=>$to);
                echo $User->AccountToWalletHandler();// 分红钱包转移金额到主账户
                exit();
            }
        }
    }

    // 修改用户PT密码
    public function changePtPassword()
    {
        $oldpassword = I("post.oldpassword");
        $newpassword = I("post.newpassword");
        $comfirmpassword = I("post.comfirmpassword");
        if(empty($oldpassword) || empty($newpassword) || empty($comfirmpassword))
        {
            echo false;
            exit();
        }
        else
        {
            if($newpassword!=$comfirmpassword)
            {
                echo false;
                exit();
            }
            else
            {
                $ptusername = strtoupper("EBJSZ_".session("SESSION_NAME"));

                $result = M('pt_user')->where(array("EBJSZ_username"=>$ptusername))->field("safe_key,EBJSZ_password")->find();
                $safe_key = $result['safe_key'];
                $pwd = $result['EBJSZ_password'];
                $password = md5(md5($oldpassword . C("PASSWORD_HALT")) . $safe_key);

                if($password!=$pwd)
                {
                    echo false;
                    exit();
                }
                else
                {
                    $PtGame = new PtGame();
                    $newpwd = md5(md5($newpassword . C("PASSWORD_HALT")) . $safe_key);
                    echo $PtGame->UpdateUserPtPwd(session("SESSION_NAME"),$newpwd);
                }
            }
        }
    }

    // 修改用户EBET密码
    public function changeEbetPassword()
    {
        $oldpassword = I("post.oldpassword");
        $newpassword = I("post.newpassword");
        $comfirmpassword = I("post.comfirmpassword");
        if(empty($oldpassword) || empty($newpassword) || empty($comfirmpassword))
        {
            echo false;
            exit();
        }
        else
        {
            if($newpassword!=$comfirmpassword)
            {
                echo false;
                exit();
            }
            else
            {
                $ebetusername = strtolower("ebet_".session("SESSION_NAME"));

                $result = M('ebet_user')->where(array("ebet_username"=>$ebetusername))->field("safe_key,ebet_password")->find();
                $safe_key = $result['safe_key'];
                $pwd = $result['ebet_password'];
                $password = md5(md5($oldpassword . C("PASSWORD_HALT")) . $safe_key);

                if($password!=$pwd)
                {
                    echo false;
                    exit();
                }
                else
                {
                    $newpwd = md5(md5($newpassword . C("PASSWORD_HALT")) . $safe_key);
                    M('ebet_user')->where(array("ebet_username"=>$ebetusername))->save(array("ebet_password"=>$newpwd,"ebet_token"=>""));
                    echo true;
                }
            }
        }
    }
    
}