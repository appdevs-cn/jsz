<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:19
 */

namespace Home\Controller;


use Think\Controller;
use \Home\Model\CommonModel as Common;
use \Home\Model\UserModel as user;
use Think\Log\Driver\Safe;
use Home\Model\SystemMessageModel as SystemMessage;
class BettingController extends Controller
{
    public function _initialize()
    {
        if (!C("SWITCH")) echo "网站维护中...";
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


        //对输入的数据进行安全过滤
        if (isset($_POST) && !empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $val = remove_xss($value);
                $_POST[$key] = $val;
            }
        }
        if (isset($_GET) && !empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $val = remove_xss($value);
                $_GET[$key] = $val;
            }
        }
        if (isset($_GET) && !empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $_GET[$key] = dowith_sql($value);
            }
        }
        if (isset($_POST) && !empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = dowith_sql($value);
            }
        }

        if(!empty(session("SESSION_ID")) && !empty(session("SESSION_NAME")))
        {
            $Common = new Common();
            $Common->checkInputValue();
            $this->logined = true;
            $User = new user();
            $this->money = $User->getUserAccount();

            import("Class.RedisObject");
            $redisObj = new \RedisObject();
            $key = md5("userbonus".session("SESSION_ID"));
            if($redisObj->exists($key))
            {
                $userBonusInfo = json_decode($redisObj->_get($key), true);
            }
            else
            {
                $userBonusInfo = M("user_bonus")->where(array('userid'=>session("SESSION_ID")))->find();
                $redisObj->_set($key,json_encode($userBonusInfo));
            }
            $bonuslevel = $userBonusInfo["bonus_level"];
            if(bccomp($bonuslevel,12.7,2)!=-1)
            {
                $this->showDividend = true;
            }
            else
            {
                $this->showDividend = false;
            }
            //通知公告
            import("Class.RedisObject");
            $redisObj = new \RedisObject();
            $redisObj->_setOption();
            $newModel = M("news");
            if($redisObj->exists("notice")){
                $result = $redisObj->_get("notice");
            } else {
                $notice = $newModel->where(array("status"=>1))->order("id DESC")->limit(4)->select();
                $redisObj->_set("notice",$notice);
                $result = $notice;
            }
            $temp = array();
            foreach($result as $val){
                $val["id"] = $val["id"];
                $val["content"] = strip_tags(html_entity_decode($val["content"]));
                $val["time"] = date("Y/m/d H:i:s",$val["dateline"]);
                $temp[] = $val;
            }
            $this->result = $temp;

            // 头像显示路径
            $imgPathRes = M("user_extend")->where(array("userid"=>session("SESSION_ID")))->field('email,tel,img100Path,imgPath')->find();
            $this->imgPath = (!empty($imgPathRes['img100Path'])) ? $imgPathRes['img100Path'] : C('HEADIMG');
            $this->leftimgPath = (!empty($imgPathRes['imgPath'])) ? $imgPathRes['imgPath'] : C('TRUMBHEADIMG');
            $this->email = (!empty($imgPathRes['email'])) ? $imgPathRes['email'] : '<a href="/User/edit/type/bindEmail" class="layui-btn layui-btn-primary layui-btn-mini"><i class="layui-icon">&#xe608;</i>添 加 邮 箱</a>';
            $this->tel = (!empty($imgPathRes['tel'])) ? substr($imgPathRes['tel'],0,3)."*** *** ".substr($imgPathRes['tel'],-2) : '<a href="/User/edit/type/bindTel" class="layui-btn layui-btn-primary layui-btn-mini"><i class="layui-icon">&#xe608;</i>添 加 电 话</a>';
            
            // 消息总数
            $this->msgcount = M("mail")->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where("mail_to_user.tid=".session("SESSION_ID"))->count();

            //设置声音
            $username = session("SESSION_NAME");
            $this->bonussound = session("sound".$username."bonussound");
            if($this->bonussound==null)
            {
                session("sound".$username."bonussound",1);
                $this->bonussound = 1;
            }

            $User = new user();
            $this->userInfo = $User->getUserInfo();

            $this->userLevel = $this->userInfo->level;
            // 绑定银行卡姓名
            $this->trueName = $this->userInfo->trueName;

            // 查看是否属于真人用户
            $this->userType = $this->userInfo->type;

            $this->managermenu = "default";
            $this->menu = "default";
        }
    }
    
    public function index()
    {
        $this->topmenu = "about";
        $this->menu = "betting";
        $this->display();
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
}