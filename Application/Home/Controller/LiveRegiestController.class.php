<?php

namespace Home\Controller;


use Think\Controller;
use Home\Model\MgGameModel as MgGame;
use Home\Model\EbetGameModel as EbetGame;
use Home\Model\PtGameModel as PtGame;
use Home\Model\AgGameModel as AgGame;
use Think\Log\Driver\Safe;

class LiveRegiestController extends Controller
{
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
        $this->display();
    }

    public function creat_verify() {
        $config = array(
            'fontSize' => 15, // 验证码字体大小
            'length' => 3, // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'reset' => false, // 验证成功后是否重置
            'useCurve' => false,
            'useImgBg' => false,
            'fontttf' => '5.ttf',
            'imageH' => '40',
            'imageW' => '95'
        );
        $verify = new \Think\Verify($config);
        $verify->codeSet = '0123456789';
        $verify->entry('liveregistyzm');
    }

    public function regiestHandler()
    {
        $username = I("post.username");
        $username = strtolower(rtrim($username));
        $password1 = I("post.password1");
        $nickname = self::createNickname();
        $yzm = I("post.code");
        if($username=="" || $password1=="" || $yzm=="")
        {
            echo "注册失败";
            exit();
        }
        
        $verify = new \Think\Verify();
        if (!$verify->check($yzm, 'liveregistyzm')) {
            echo "验证码输入不正确";
            exit();
        }

        // 根据域名查询上级ID
        $HTTPHOST = $_SERVER['HTTP_HOST'];
        if(strpos($HTTPHOST,":")!==false)
        {
            $HTTPHOSTARR = explode(":",$HTTPHOST);
            $HTTPHOST = $HTTPHOSTARR[0];
        }
        $userModel = M("user");
        $userInfo = $userModel->where(array("domain"=>$HTTPHOST))->find();

        $group = 5;
        $point = "0.0";
        $klcpoint = "0.0";
        $parentid = $userInfo["id"];

        $exituser = $userModel->where(array("username"=>$username))->field("id")->find();
        if(!empty($exituser)){
            echo "该用户名已被注册,请另外更换一个";
            exit();
        }
        
        $parentuser = $userModel->where(array("id"=>$parentid))->getField("parent_path");
        $safe_key = md5(microtime() + mt_rand());
        $_user_data = array(
            "username" => $username,
            "nickname" => $nickname,
            "group_id" => $group,
            "parent_id" => $parentid,
            "parent_path" => $parentuser . "," . $parentid,
            "reg_ip" => sprintf("%u", ipton(get_client_ip())),
            "reg_time" => time(),
            "log_lasttime" => time(),
            "password" => md5(md5($password1 . C("PASSWORD_HALT")) . $safe_key),
            "acc_password" => "",
            "safe_key" => $safe_key,
            "cur_account" => 0,
            "domain"    =>  $HTTPHOST,
            "type"  =>  1
        );
        $userid = $userModel->add($_user_data);
        if($userid>0){
            $reward_array = C("REWARDS");
            $rewardKey = strval($point);
            $user_bonus_data = array(
                "userid" => $userid,
                "parent_id" => $parentid,
                "parent_path" => $parentuser . "," . $parentid,
                "bonus_content" => $reward_array[$rewardKey],
                "klc_bonus_content" => $klcpoint,
                "bonus_level" => $point,
                "last_bonus_level" => $point
            );
            $userBonusId = M("user_bonus")->add($user_bonus_data);
            if($userBonusId>0){
                //添加配额表数据
                M("user_interval")->add(array("userid"=>$userid,"parent_id"=>$parentid,"parent_path"=>$parentuser.",".$parentid,"interval_time"=>time()));
                //更新注册链接个数
                M("regist")->where(array("id"=>$registid))->setDec("count",1);
                // 保存用户额外信息
                M('user_extend')->save(array("userid"=>$userid,'qq'=>0,'email'=>$email,'tel'=>0,'imgPath'=>'/Uploads/original/20170322/trumb_58d2377252c82.jpg','img100Path'=>'/Uploads/original/20170417/trumb100_58f4bf9aebfd5.png'));
                // 增加MG用户
                $MgGame = new MgGame();
                $MgGame->createMgUser($username, $userid, $parentid, $parentuser . "," . $parentid, $group, time(), sprintf("%u", ipton(get_client_ip())));
                
                // 增加EBET用户
                $EbetGame = new EbetGame();
                $EbetGame->createEbetUser($username, $userid, $parentid, $parentuser . "," . $parentid, $group, time(), sprintf("%u", ipton(get_client_ip())));

                // 增加Ag用户
                $AgGame = new AgGame();
                $AgGame->createAgUser($username, $userid, $parentid, $parentuser . "," . $parentid, $group, time(), sprintf("%u", ipton(get_client_ip())));

                // 增加PT用户
                $PtGame = new PtGame();
                $PtGame->createPtUser($username, $userid, $parentid, $parentuser . "," . $parentid, $group, time(), sprintf("%u", ipton(get_client_ip())));
                echo "用户注册成功";
                exit();
            } else {
                echo "用户注册失败";
                exit();
            }
        }
    }

    /**
    * 随机生成昵称
    */
    public static function createNickname()
    {
        $nicheng_tou=array('快乐的','冷静的','醉熏的','潇洒的','糊涂的','积极的','冷酷的','深情的','粗暴的','温柔的','可爱的','愉快的','义气的','认真的','威武的','帅气的','传统的','潇洒的','漂亮的','自然的','专一的','听话的','昏睡的','狂野的','等待的','搞怪的','幽默的','魁梧的','活泼的','开心的','高兴的','超帅的','留胡子的','坦率的','直率的','轻松的','痴情的','完美的','精明的','无聊的','有魅力的','丰富的','繁荣的','饱满的','炙热的','暴躁的','碧蓝的','俊逸的','英勇的','健忘的','故意的','无心的','土豪的','朴实的','兴奋的','幸福的','淡定的','不安的','阔达的','孤独的','独特的','疯狂的','时尚的','落后的','风趣的','忧伤的','大胆的','爱笑的','矮小的','健康的','合适的','玩命的','沉默的','斯文的','香蕉','苹果','鲤鱼','鳗鱼','任性的','细心的','粗心的','大意的','甜甜的','酷酷的','健壮的','英俊的','霸气的','阳光的','默默的','大力的','孝顺的','忧虑的','着急的','紧张的','善良的','凶狠的','害怕的','重要的','危机的','欢喜的','欣慰的','满意的','跳跃的','诚心的','称心的','如意的','怡然的','娇气的','无奈的','无语的','激动的','愤怒的','美好的','感动的','激情的','激昂的','震动的','虚拟的','超级的','寒冷的','精明的','明理的','犹豫的','忧郁的','寂寞的','奋斗的','勤奋的','现代的','过时的','稳重的','热情的','含蓄的','开放的','无辜的','多情的','纯真的','拉长的','热心的','从容的','体贴的','风中的','曾经的','追寻的','儒雅的','优雅的','开朗的','外向的','内向的','清爽的','文艺的','长情的','平常的','单身的','伶俐的','高大的','懦弱的','柔弱的','爱笑的','乐观的','耍酷的','酷炫的','神勇的','年轻的','唠叨的','瘦瘦的','无情的','包容的','顺心的','畅快的','舒适的','靓丽的','负责的','背后的','简单的','谦让的','彩色的','缥缈的','欢呼的','生动的','复杂的','慈祥的','仁爱的','魔幻的','虚幻的','淡然的','受伤的','雪白的','高高的','糟糕的','顺利的','闪闪的','羞涩的','缓慢的','迅速的','优秀的','聪明的','含糊的','俏皮的','淡淡的','坚强的','平淡的','欣喜的','能干的','灵巧的','友好的','机智的','机灵的','正直的','谨慎的','俭朴的','殷勤的','虚心的','辛勤的','自觉的','无私的','无限的','踏实的','老实的','现实的','可靠的','务实的','拼搏的','个性的','粗犷的','活力的','成就的','勤劳的','单纯的','落寞的','朴素的','悲凉的','忧心的','洁净的','清秀的','自由的','小巧的','单薄的','贪玩的','刻苦的','干净的','壮观的','和谐的','文静的','调皮的','害羞的','安详的','自信的','端庄的','坚定的','美满的','舒心的','温暖的','专注的','勤恳的','美丽的','腼腆的','优美的','甜美的','甜蜜的','整齐的','动人的','典雅的','尊敬的','舒服的','妩媚的','秀丽的','喜悦的','甜美的','彪壮的','强健的','大方的','俊秀的','聪慧的','迷人的','陶醉的','悦耳的','动听的','明亮的','结实的','魁梧的','标致的','清脆的','敏感的','光亮的','大气的','老迟到的','知性的','冷傲的','呆萌的','野性的','隐形的','笑点低的','微笑的','笨笨的','难过的','沉静的','火星上的','失眠的','安静的','纯情的','要减肥的','迷路的','烂漫的','哭泣的','贤惠的','苗条的','温婉的','发嗲的','会撒娇的','贪玩的','执着的','眯眯眼的','花痴的','想人陪的','眼睛大的','高贵的','傲娇的','心灵美的','爱撒娇的','细腻的','天真的','怕黑的','感性的','飘逸的','怕孤独的','忐忑的','高挑的','傻傻的','冷艳的','爱听歌的','还单身的','怕孤单的','懵懂的');

        $nicheng_wei=array('嚓茶','凉面','便当','毛豆','花生','可乐','灯泡','哈密瓜','野狼','背包','眼神','缘分','雪碧','人生','牛排','蚂蚁','飞鸟','灰狼','斑马','汉堡','悟空','巨人','绿茶','自行车','保温杯','大碗','墨镜','魔镜','煎饼','月饼','月亮','星星','芝麻','啤酒','玫瑰','大叔','小伙','哈密瓜，数据线','太阳','树叶','芹菜','黄蜂','蜜粉','蜜蜂','信封','西装','外套','裙子','大象','猫咪','母鸡','路灯','蓝天','白云','星月','金手指','微笑','摩托','板栗','高山','大地','大树','电灯胆','砖头','楼房','水池','鸡翅','蜻蜓','红牛','咖啡','机器猫','枕头','大船','诺言','钢笔','刺猬','天空','飞机','大炮','冬天','洋葱','春天','夏天','秋天','冬日','航空','毛衣','豌豆','黑米','玉米','眼睛','老鼠','白羊','帅哥','美女','季节','鲜花','服饰','裙子','白开水','秀发','大山','火车','汽车','歌曲','舞蹈','老师','导师','方盒','大米','麦片','水杯','水壶','手套','鞋子','自行车','鼠标','手机','电脑','书本','奇迹','身影','香烟','夕阳','台灯','宝贝','未来','皮带','钥匙','心锁','故事','花瓣','滑板','画笔','画板','学姐','店员','电源','饼干','宝马','过客','大白','时光','石头','钻石','河马','犀牛','西牛','绿草','抽屉','柜子','往事','寒风','路人','橘子','耳机','鸵鸟','朋友','苗条','铅笔','钢笔','硬币','热狗','大侠','御姐','萝莉','毛巾','期待','盼望','白昼','黑夜','大门','黑裤','钢铁侠','哑铃','板凳','枫叶','荷花','乌龟','仙人掌','衬衫','大神','草丛','早晨','心情','茉莉','流沙','蜗牛','战斗机','冥王星','猎豹','棒球','篮球','乐曲','电话','网络','世界','中心','鱼','鸡','狗','老虎','鸭子','雨','羽毛','翅膀','外套','火','丝袜','书包','钢笔','冷风','八宝粥','烤鸡','大雁','音响','招牌','胡萝卜','冰棍','帽子','菠萝','蛋挞','香水','泥猴桃','吐司','溪流','黄豆','樱桃','小鸽子','小蝴蝶','爆米花','花卷','小鸭子','小海豚','日记本','小熊猫','小懒猪','小懒虫','荔枝','镜子','曲奇','金针菇','小松鼠','小虾米','酒窝','紫菜','金鱼','柚子','果汁','百褶裙','项链','帆布鞋','火龙果','奇异果','煎蛋','唇彩','小土豆','高跟鞋','戒指','雪糕','睫毛','铃铛','手链','香氛','红酒','月光','酸奶','银耳汤','咖啡豆','小蜜蜂','小蚂蚁','蜡烛','棉花糖','向日葵','水蜜桃','小蝴蝶','小刺猬','小丸子','指甲油','康乃馨','糖豆','薯片','口红','超短裙','乌冬面','冰淇淋','棒棒糖','长颈鹿','豆芽','发箍','发卡','发夹','发带','铃铛','小馒头','小笼包','小甜瓜','冬瓜','香菇','小兔子','含羞草','短靴','睫毛膏','小蘑菇','跳跳糖','小白菜','草莓','柠檬','月饼','百合','纸鹤','小天鹅','云朵','芒果','面包','海燕','小猫咪','龙猫','唇膏','鞋垫','羊','黑猫','白猫','万宝路','金毛','山水','音响');

        $tou_num=rand(0,331);

        $wei_num=rand(0,325);

        $nicheng=$nicheng_tou[$tou_num].$nicheng_wei[$wei_num];

        return $nicheng;
    }
}




?>