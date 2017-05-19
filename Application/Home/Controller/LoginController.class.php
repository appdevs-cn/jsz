<?php
namespace Home\Controller;

use \Home\Model\UserModel as user;
use \Home\Model\UserQuestionModel as UserQuestion;
use \Home\Model\CommonModel as Common;
use Home\Model\AgGameModel as AgGame;
use Think\Log\Driver\Safe;
use Think\Controller;

class LoginController extends Controller
{

    private $User;

    private $UserQuestion;

    private $Common;

    public function _initialize()
    {
        // CC防御模块
        $this->_Securityconfig = get_cfg_value();
        $this->SAFE_LOG_POWER = $this->_Securityconfig['SAFE_LOG_POWER'];
         $this->URL_XSS_DEFEND = $this->_Securityconfig['URL_XSS_DEFEND'];
        if(is_string($this->_Securityconfig['SECRITY_ATTACKEVASIVE'])) {
            $attackevasive_tmp = explode('|', $this->_Securityconfig['SECRITY_ATTACKEVASIVE']);
            $attackevasive = 0;
            foreach($attackevasive_tmp AS $key => $value) {
                $attackevasive += intval($value);
            }
            unset($attackevasive_tmp);
        } else {
            $attackevasive = $this->_Securityconfig['SECRITY_ATTACKEVASIVE'];
        }
        $lastrequest = cookie('lastrequest') ? authcode(cookie('lastrequest'), 'DECODE') : '';

        if($attackevasive & 1 || $attackevasive & 4) {
            $cookieParams['expire'] = time() + 816400;
            $cookieParams['httponly'] = 1;
            cookie('lastrequest', authcode(TIMESTAMP, 'ENCODE'), $cookieParams);
        }

        if($attackevasive & 1) {
            if(TIMESTAMP - $lastrequest < 1) {
                self::securitymessage('attackevasive_1_subject', 'attackevasive_1_message');
            }
        }

        if(($attackevasive & 2) && ($_SERVER['HTTP_X_FORWARDED_FOR'] ||
                $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] ||
                $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_CACHE_INFO'] ||
                $_SERVER['HTTP_PROXY_CONNECTION'])) 
        {
            self::securitymessage('attackevasive_2_subject', 'attackevasive_2_message', FALSE);
        }

        if($attackevasive & 4) {
            if(empty($lastrequest) || TIMESTAMP - $lastrequest > 300) {
                self::securitymessage('attackevasive_4_subject', 'attackevasive_4_message');
            }
        }

        if($attackevasive & 8) {
            list($visitcode, $visitcheck, $visittime) = explode('|', authcode(cookie('visitcode'), 'DECODE'));
            if(!$visitcode || !$visitcheck || !$visittime || TIMESTAMP - $visittime > 60 * 60 * 4 ) {
                $cookieParams['expire'] = time() + 816400;
                $cookieParams['httponly'] = 1;
                if(empty($_POST['secqsubmit']) || ($visitcode != md5($_POST['answer']))) {
                    $answer = 0;
                    $question = '';
                    for ($i = 0; $i< rand(2, 5); $i ++) {
                        $r = rand(1, 20);
                        $question .= $question ? ' + '.$r : $r;
                        $answer += $r;
                    }
                    $question .= ' = ?';

                    cookie('visitcode', authcode(md5($answer).'|0|'.TIMESTAMP, 'ENCODE'), $cookieParams);
                    self::securitymessage($question, '<input type="text" name="answer" size="8" maxlength="150" /><input type="submit" name="secqsubmit" class="button" value=" Submit " />', FALSE, TRUE);
                } else {
                    cookie('visitcode', authcode($visitcode.'|1|'.TIMESTAMP, 'ENCODE'), $cookieParams);
                }
            }
        }

        $this->Common = new Common();
        $this->Common->checkInputValue();
        if (! empty(session("SESSION_ID")) && ! empty(session("SESSION_NAME"))) {
            redirect('Index/index');
        }
    }

    private function securitymessage($subject, $message, $reload = TRUE, $form = FALSE) {
        $scuritylang = array(
            'attackevasive_1_subject' => '&#x9891;&#x7e41;&#x5237;&#x65b0;&#x9650;&#x5236;',
            'attackevasive_1_message' => '&#x60a8;&#x8bbf;&#x95ee;&#x672c;&#x7ad9;&#x901f;&#x5ea6;&#x8fc7;&#x5feb;&#x6216;&#x8005;&#x5237;&#x65b0;&#x95f4;&#x9694;&#x65f6;&#x95f4;&#x5c0f;&#x4e8e;&#x4e24;&#x79d2;&#xff01;&#x8bf7;&#x7b49;&#x5f85;&#x9875;&#x9762;&#x81ea;&#x52a8;&#x8df3;&#x8f6c;&#x20;&#x2e;&#x2e;&#x2e;',
            'attackevasive_2_subject' => '&#x4ee3;&#x7406;&#x670d;&#x52a1;&#x5668;&#x8bbf;&#x95ee;&#x9650;&#x5236;',
            'attackevasive_2_message' => '&#x672c;&#x7ad9;&#x73b0;&#x5728;&#x9650;&#x5236;&#x4f7f;&#x7528;&#x4ee3;&#x7406;&#x670d;&#x52a1;&#x5668;&#x8bbf;&#x95ee;&#xff0c;&#x8bf7;&#x53bb;&#x9664;&#x60a8;&#x7684;&#x4ee3;&#x7406;&#x8bbe;&#x7f6e;&#xff0c;&#x76f4;&#x63a5;&#x8bbf;&#x95ee;&#x672c;&#x7ad9;&#x3002;',
            'attackevasive_4_subject' => '&#x9875;&#x9762;&#x91cd;&#x8f7d;&#x5f00;&#x542f;',
            'attackevasive_4_message' => '&#x6b22;&#x8fce;&#x5149;&#x4e34;&#x672c;&#x7ad9;&#xff0c;&#x9875;&#x9762;&#x6b63;&#x5728;&#x91cd;&#x65b0;&#x8f7d;&#x5165;&#xff0c;&#x8bf7;&#x7a0d;&#x5019;&#x20;&#x2e;&#x2e;&#x2e;'
        );
        list($attackevasive, $level, $subjectTmp) = explode('_', $subject);
        $subject = $scuritylang[$subject] ? $scuritylang[$subject] : $subject;
        $message = $scuritylang[$message] ? $scuritylang[$message] : $message;
        $this->SAFE_LOG_POWER && Safe::logFile(null,  __FUNCTION__);
        if(IS_AJAX) {
            //self::security_ajaxshowheader();
            //echo '<div id="attackevasive_1" class="popupmenu_option"><b style="font-size: 16px">'.$subject.'</b><br /><br />'.$message.'</div>';
            //self::security_ajaxshowfooter();
        } else {
            echo '<html>';
            echo '<head>';
            echo '<link href="/Resourse/Home/css/loaders.css" rel="stylesheet" />';
            echo '<title>'.C("WEB_TITLE").'</title>';
            echo '</head>';
            echo '<body style="background-color:darkorange">';
            if($reload) {
                echo '<script language="JavaScript">';
                echo 'function reload() {';
                echo '	document.location.reload();';
                echo '}';
                echo 'setTimeout("reload()", 1001);';
                echo '</script>';
            }
            if($form) {
                echo '<form action="'.$GLOBALS['_SERVER']['REQUEST_URI'].'" method="post" autocomplete="off">';
            }
            if($reload) {
                echo '<div style="position:absolute;top:50%;left: 47%">';
                echo '  <div class="loader-inner ball-pulse-rise">';
                echo '    <div></div><div></div><div></div><div></div><div></div></div></div>';
            }
            else{
                echo '<table cellpadding="0" cellspacing="0" border="0" width="700" align="center" height="85%">';
                echo '  <tr align="center" valign="middle">';
                echo '    <td>';
                echo '    <table cellpadding="10" cellspacing="0" border="0" width="80%" align="center" style="font-family: Verdana, Tahoma; color: #666666; font-size: 11px">';
                echo '    <tr>';
                echo '      <td valign="middle" align="center" bgcolor="#EBEBEB">';
                echo '     	<br /><br /> <b style="font-size: 16px">'.$subject.'</b> <br /><br />';
                echo $message;
                echo '        <br /><br />';
                echo '      </td>';
                echo '    </tr>';
                echo '    </table>';
                echo '    </td>';
                echo '  </tr>';
                echo '</table>';
            }
            if($form) {
                echo '</form>';
            }
            echo '</body>';
            echo '</html>';
            exit();
        }
    }

    private static function security_ajaxshowheader() {
        $charset = 'UTF-8';
        ob_end_clean();
        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");
        header("Content-type: application/xml");
        echo "<?xml version=\"1.0\" encoding=\"".$charset."\"?>\n<root><![CDATA[";
    }

    private static function security_ajaxshowfooter() {
        echo ']]></root>';
        exit();
    }

    private static function formhash($specialadd = '') {
        $hashadd = defined('IN_ADMINCP') ? 'Admin Control Panel' : '';
        return substr(md5(substr(TIMESTAMP, 0, -7).$GLOBALS['username'].$GLOBALS['uid'].C('AUTHKEY').$hashadd.$specialadd), 8, 8);
    }

    public function index()
    {
        // 判断当前的域名地址是否是真人老虎机的域名地址
        $HTTPHOST = $_SERVER['HTTP_HOST'];
        if(strpos($HTTPHOST,":")!==false)
        {
            $HTTPHOSTARR = explode(":",$HTTPHOST);
            $HTTPHOST = $HTTPHOSTARR[0];
        }
        $liveDomain = M("livedomain")->where(array("domain"=>$HTTPHOST))->field("agUser")->find();
        if(empty($liveDomain))
        {
            $this->isShow = false;
        }
        else
        {
            $AgGame = new AgGame();
            $agUser = str_replace("jsz","",$liveDomain['agUser']);
            $this->gameUrl = $AgGame->getDemoGameUrl($agUser,12);
            $this->isShow = true;
        }

        
        $this->display();
    }

    // 判断用户是否已经绑定过谷歌验证,如果绑定过，前台进行显示
    public function isGoogle()
    {
        $username = I("post.username");
        $google = M("user")->where(array("username"=>$username))->getField("mac");
        echo ($google!="") ?  true : false;
    }

    /**
     * 输出验证码并把验证码的值保存的session中
     * 验证码保存到session的格式为： array('verify_code' => '验证码值', 'verify_time' => '验证码创建时间');
     *
     * @access public
     * @param string $id
     *            要生成验证码的标识
     * @return void
     */
    public function creat_verify()
    {
        $config = array(
            'fontSize' => 15, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => true, // 关闭验证码杂点
            'reset' => false, // 验证成功后是否重置
            'useCurve' => true,
            'useNoise' => false,
            'useImgBg' => false,
            'fontttf' => '5.ttf',
            'imageH' => '40',
            'imageW' => '120'
        );
        $verify = new \Think\Verify($config);
        $verify->codeSet = '0123456789';
        $verify->entry('yzmbiaoshi');
    }

    public function checkVerif()
    {
        $username = I("post.username");
        $username = rtrim($username);
        session($username."checkverify",null);
        // 检查用户是否存在
        $isHave = M("user")->where(array("username"=>$username))->field("id")->find();
        if(1==count($isHave))
        {
            $md5 = substr(md5($username.mktime()),0,32);
            session($username."checkverify",$md5);
            echo $md5;
        }
        else
        {
            echo false;
        }
    }

    /**
     * 用户登录
     */
    public function login()
    {
        $this->User = new user();
        $uap = I("post.uap");
        $pk = I("post.param");
        $iv = I("post.param");
        $iv = substr($iv, 0, 16);
        $data = base64_decode($uap);
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $pk, $data, MCRYPT_MODE_CBC, $iv);
        $dataarr = explode("|",$data);
        $username = $dataarr[0];
        $username = rtrim($dataarr[0]);
        $pwd = $dataarr[1];
        $yzm = $dataarr[2];
        $checkyzm = session($username."checkverify");
        if (strcmp(rtrim($yzm),rtrim($checkyzm))!=0) {
            session($username."checkverify",null);
            echo "0-验证信息不正确";
        } else {
            session($username."checkverify",null);
            $data = array(
                "name" => $username,
                "pwd" => $pwd
            );
            if ($this->User->create($data, 1)) {
                echo $this->User->checkLoginModel();
            } else {
                exit();
            }
        }
    }

    /**
     * 根据用户名获取密保问题
     *
     * @return bool
     */
    public function getQuestion()
    {
        $username = I("post.name");
        if(!empty($username))
        {
            $User = new user();
            $User->data = array("name"=>$username);
            $userid = $User->getId();
            if($userid=="")
            {
                echo "";exit();
            }
            $UserQuestion = new UserQuestion();
            $UserQuestion->data = array("userid"=>$userid);
            echo $UserQuestion->getQuestion();
        }
        else
        {
            echo "";
        }
    }

    // 获取短信验证码
    public function GetTelVcode()
    {
        $tel = I("post.tel","rtrim");
        $username = I("post.username","rtrim");
        if($tel=="" || $username=="")
        {
            echo false;
            exit();
        }
        // 查询用户名
        $isHave = M("user")->where(array("username"=>$username))->field("id")->find();
        if(empty($isHave))
        {
            echo false;
            exit();
        }
        // 查询电话号码是否是该用户绑定的电话
        $isTelHave = M("user_extend")->where(array("userid"=>$isHave['id']))->field("tel")->find();
        if(empty($isTelHave['tel']))
        {
            echo false;
            exit();
        }
        $Appkey = C("MessageCodeKey");
        $TemplateId = C("TemplateId");
        $param = substr(time(),-6).rand(100,999);
        $key = md5("ForgetPwdVcode".$username);
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

    public function forgetpwd()
    {
        $username = I("post.name");
        $telVcode = I("post.telVcode");
        $newpassword = I("post.newpwd");
        $tel = I("post.tel");
        $User = new user();
        $key = md5("ForgetPwdVcode".$username);
        $baseVcode = session($key);
        if(!empty($username) && !empty($telVcode) && !empty($newpassword) && !empty($tel))
        {
            $User->data = array("name"=>$username);
            $userid = $User->getId();
            if($userid=="")
            {
                echo "0-用户不存在";
                exit();
            }
            else
            {
                // 验证绑定的手机号码
                $bindtel = M("user_extend")->where(array("userid"=>$userid))->getField("tel");
                if(empty($bindtel))
                {
                    echo "0-你尚未绑定手机号码，请联系在线客服!";
                    exit();
                }
                else
                {
                    if($bindtel!=$tel)
                    {
                        echo "0-输入的号码与绑定的号码不符合!";
                        exit();
                    }
                }
                if($baseVcode!=$telVcode)
                {
                    session($key,null);
                    echo "0-验证码输入不正确!";
                    exit();
                }
                session($key,null);
                $User->data = array("username"=>$username,"password"=>$newpassword);
                echo $User->updatePassword();
            }
        }
    }

    /**
     * 动态密码登录
     */
    public function oathimg()
    {
        $this->User = new user();
        $this->User->data = array(
            "username" => I("post.name"),
            "oathcode" => I("post.oathcode"),
            "pwd"   =>  I("post.pwd")
        );
        echo $this->User->oathLoginModel();
    }

    /**
     * 创建动态密码二维码
     */
    public function createOathImage()
    {
        $this->User = new user();
        $this->User->data = array(
            "username" => I("post.name")
        );
        echo $this->User->getOathImage();
    }

    private function makePregIP($str)
    {
        $preg_limit = $preg = "";
        if (strstr($str, "-")) {
            $aIP = explode(".", $str);
            foreach ($aIP as $k => $v) {
                if (! strstr($v, "-")) {
                    $preg_limit .= $this->makePregIP($v);
                } else {
                    $aipNum = explode("-", $v);
                    for ($i = $aipNum[0]; $i <= $aipNum[1]; $i ++) {
                        $preg .= $preg ? "|" . $i : "[" . $i;
                    }
                    $preg_limit .= strrpos($preg_limit, ".", 1) == (strlen($preg_limit) - 1) ? $preg . "]" : "." . $preg . "]";
                }
            }
        } else {
            $preg_limit .= $str . ".";
        }
        return $preg_limit;
    }
}
