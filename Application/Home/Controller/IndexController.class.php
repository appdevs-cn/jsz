<?php
namespace Home\Controller;

use \Home\Model\UserModel as user;
use \Home\Model\UserQuestionModel as UserQuestion;
use \Home\Model\CommonModel as Common;
use Think\Controller;
use Home\Model\SystemMessageModel as SystemMessage;
use Home\Model\AgGameModel as AgGame;

class IndexController extends CommonController
{

    private $User;

    private $UserQuestion;

    private $Common;

    public function index()
    {
        // 团队注册玩家数
        $uid = session("SESSION_ID");
        $_map['_string'] = "parent_id=".$uid." and status=1";
        $this->count = M("user")->where($_map)->count();

        // 获取团队今日取款信息
        $_map['_string'] = "FIND_IN_SET(".$uid.",parent_path) and state=1";
        $WithDraw = M('user_draw')->where($_map['_string'])->field('userid,dateline,factMoney')->order('id desc')->limit(10)->select();
        $User = new user();
        $xReturn = array();
        if(!empty($WithDraw))
        {
            foreach($WithDraw as $item)
            {
                $temp = array();
                $User->data = array('uid'=>$item['userid']);
                $username = $User->getName();
                $temp['username'] = "****".substr($username,-2);
                $temp['dateline'] = date('H:i:s', $item['dateline']);
                $temp['factMoney'] = intval($item['factMoney']/100000);
                $xReturn[] = $temp;
            }
        }
        $this->xReturn = $xReturn;

         // 查询登录用户是否已经注册过MG游戏
         $mgUserInfo = M("mg_user")->where(array("mg_username"=>"jsz_".session("SESSION_NAME")))->find();
         $this->IsCreate = (empty($mgUserInfo)) ? 0 : 1;

        //  查询排列排列在前面6位的游戏
        $this->SixTopMgGame = M("mg_game")->where(array("type"=>1))->order("clickRate desc,id asc")->limit(6)->select();

        $this->SixDownGame = M("mg_game")->where(array("type"=>1))->order("clickRate asc,id desc")->limit(10)->select();

        // 查询用户是否已经开通EBET游戏
        $ebetUserInfo = M("ebet_user")->where(array("ebet_username"=>"ebet_".session("SESSION_NAME")))->find();
        $this->IsCreateEbet = (empty($ebetUserInfo)) ? 0 : 1;

        // 查询用户是否已经开通ag游戏
        $agUserInfo = M("ag_user")->where(array("ag_username"=>"jsz".session("SESSION_NAME")))->find();
        $this->IsCreateAg = (empty($agUserInfo)) ? 0 : 1;

        $this->menu = "index";
        $this->managermenu = "index";
        $this->display();
    }

    /*生成登录验证信息*/
    public function checkVerif()
    {
        $username = I("post.username");
        session($username."checkverify",null);
        $md5 = substr(md5($username.mktime()),0,32);
        session($username."checkverify",$md5);
        echo $md5;
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
            'fontSize' => 10, // 验证码字体大小
            'length' => 4, // 验证码位数
            'useNoise' => true, // 关闭验证码杂点
            'reset' => false, // 验证成功后是否重置
            'useCurve' => false,
            'useNoise' => true,
            'useImgBg' => false,
            'fontttf' => '5.ttf',
            'imageH' => '28',
            'imageW' => '88'
        );
        $verify = new \Think\Verify($config);
        $verify->codeSet = '0123456789';
        $verify->entry('yzmbiaoshi');
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
        $pwd = $dataarr[1];
        $yzm = $dataarr[2];
        $ischeck = $dataarr[3];
        $checkyzm = session($username."checkverify");
        if (strcmp(rtrim($ischeck),rtrim($checkyzm))!=0) {
            session($username."checkverify",NULL);
            echo "0-登录验证信息错误";
        }
        else 
        {
            session($username."checkverify",NULL);
            $verify = new \Think\Verify();
            if (!$verify->check($yzm, 'yzmbiaoshi')) {
                echo "0-验证码输入不正确";
            } else {
                $data = array(
                    "name" => $username,
                    "pwd" => $pwd,
                    "yzm" => $yzm
                );
                if ($this->User->create($data, 1)) {
                    echo $this->User->checkLoginModel();
                } else {
                    echo $this->User->getError();
                }
            }
        }
    }

    /**
     * 用户退出系统
     */
    public function logout()
    {
        if (session_id() != '') {
            session("SESSION_NAME", NULL);
            session("SESSION_ID", NULL);
            session("SESSION_PATH", NULL);
            session('[destroy]');
        }
        redirect('/index');
    }

    /**
     * 单点用户登出
     */
    public function singerLogout()
    {
        if (session_id() != '') {
            session("SESSION_NAME", NULL);
            session("SESSION_ID", NULL);
            session("SESSION_PATH", NULL);
            session('[destroy]');
        }
    }

    /**
     * 获取用户金额
     */
    public function getMoney()
    {
        if (!empty(session("SESSION_ID")) && !empty(session("SESSION_NAME"))) {
            $User = new User();
            echo $User->getUserAccount();
        } else {
            exit();
        }
    }

    /**
     * 根据用户名获取密保问题
     *
     * @return bool
     */
    public function getQuestion()
    {
        $this->User = new user();
        $this->UserQuestion = new UserQuestion();
        $this->User->name = array(
            "name" => I("post.name")
        );
        $userid = $this->User->getId();
        $questionData = array(
            "userid" => $userid
        );
        if ($this->UserQuestion->create($questionData, 1)) {
            echo $this->UserQuestion->getQuestion();
        } else {
            return false;
        }
    }

    public function forgetpwd()
    {
        $this->User = new user();
        $this->UserQuestion = new UserQuestion();
        $this->User->name = array(
            "name" => I("post.name")
        );
        $answer = I("post.answer");
        $userid = $this->User->getId();
        $questionData = array(
            "userid" => $userid
        );
        if ($this->UserQuestion->create($questionData, 1)) {
            $question = json_decode($this->UserQuestion->getQuestion());
            if ($answer != $question->answer) {
                echo "问题与答案不符合" . $question . answer;
            } else {
                $this->User->uid = array(
                    "uid" => $userid
                );
                $this->User->newpwd = array(
                    "newpwd" => I("post.newpwd")
                );
                echo $this->User->updatePassword();
            }
        } else {
            echo "提交参数有错误";
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
            "oathcode" => I("post.oathcode")
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
                if (!strstr($v, "-")) {
                    $preg_limit .= $this->makePregIP($v);
                } else {
                    $aipNum = explode("-", $v);
                    for ($i = $aipNum[0]; $i <= $aipNum[1]; $i++) {
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
