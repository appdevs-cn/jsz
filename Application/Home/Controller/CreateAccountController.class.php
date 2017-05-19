<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/10
 * Time: 下午7:41
 */

namespace Home\Controller;

use Home\Model\UserIntervalModel as UserInterval;
use Home\Model\UserModel as User;
use Home\Model\MgGameModel as MgGame;
use Home\Model\EbetGameModel as EbetGame;
use Home\Model\PtGameModel as PtGame;
use Home\Model\AgGameModel as AgGame;
class CreateAccountController extends CommonController
{

    public function index()
    {
        if($this->userType==1) exit();
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxymanager = 'createuser';

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
        $this->bonus_level = $userBonusInfo["bonus_level"];
        $this->klcBonusContent = $userBonusInfo["klc_bonus_content"];

        $UserInterval = new UserInterval();
        $this->isQuate = $UserInterval->isQuate();
        $result = M("regist")->where(array("userid"=>session("SESSION_ID")))->order("createtime desc")->select();
        import("Class.XDeode");
        $_xDe=new \XDeode();
        foreach($result as $item){
            $item["ser"] = $_xDe->encode($item["id"]);
            $temp[] = $item;
        }
        $this->list = $temp;
        $this->display();
    }

    /**
     * 获取用户配额
     */
    public function getQuate()
    {
        $UserInterval = new UserInterval();
        $list = $UserInterval->selectByUserid();
        $temp = array();
        foreach($list as $key=>$item)
        {
            if(in_array($key,array("128","127","126","125","124")))
            {
                $temp[] = array("key"=>$key,"item"=>$item);
            }
        }
        echo json_encode($temp);
    }

    /**
     * 添加用户
     */
    public function createUser()
    {
        if($this->userType==1) exit();
        $_group = I("post.group","");
        $_username = I("post.username","");
        $_username = strtolower(rtrim($_username));
        $_keeppoint = I("post.keeppoint","");
        $_klckeeppoint = I("post.klckeeppoint");
        $_keeppoint = sprintf("%.1f",$_keeppoint);
        $_password = "a123456";
        $_uid = session("SESSION_ID");
        $_name = session("SESSION_NAME");
        $_parentId = session("SESSION_PARENTID");
        $_parentPath = session("SESSION_PATH");
        $userIntervalModel = M("user_interval");
        $userModel = M("user");
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $key = md5("userbonus".$_uid);
        if($redisObj->exists($key))
        {
            $userBonusInfo = json_decode($redisObj->_get($key), true);
        }
        else
        {
            $userBonusInfo = M("user_bonus")->where(array('userid'=>$_uid))->find();
            $redisObj->_set($key,json_encode($userBonusInfo));
        }
        $bonusLevel = $userBonusInfo["bonus_level"];
        $klcbonuscontent = $userBonusInfo["klc_bonus_content"];
        
        
        $this->checkParameter($_group,$_username,$_keeppoint,$bonusLevel);

        if(bccomp($_klckeeppoint,0,2)==-1 || bccomp($_klckeeppoint,0.8,2)==1)
        {
            echo "开户失败";
            exit();
        }

        if(bccomp($_klckeeppoint,$klcbonuscontent,2)==1)
        {
            echo "幸运28的返点设置不正确";
            exit();
        }

        if(bccomp($_keeppoint,12.3,2)==1 && bccomp($_keeppoint,12.8,2)!=0) { //包括11.1 需要配额进行开号
            //step 1 找出返点所在的区间
            if(bccomp($_keeppoint,12.8,2)==0) {
                $_k = "interval_1";
                $_interval_str = 1;
            } else if(bccomp($_keeppoint,12.7,2)==0) {
                $_k = "interval_2";
                $_interval_str = 2;
            } else if(bccomp($_keeppoint,12.6,2)==0) {
                $_k = "interval_3";
                $_interval_str = 3;
            } else if(bccomp($_keeppoint,12.5,2)==0) {
                $_k = "interval_4";
                $_interval_str = 4;
            } else if(bccomp($_keeppoint,12.4,2)==0) {
                $_k = "interval_5";
                $_interval_str = 5;
            }
            //step 2 更新用户在开号区间上的配额数量
            //该区间变化之前的值
            $_bfefor_interval = $userIntervalModel->where(array("userid" => $_uid))->getField($_k);
            //更新区间配额个数
            $_after = $_bfefor_interval-1;
            $userIntervalModel->where(array("userid" => $_uid))->save(array($_k=>$_after));
            //区间配额个数变化之后的值
            $_after_interval = $userIntervalModel->where(array("userid" => $_uid))->getField($_k);
            //将用户基本信息添加到用户表
            $safe_key= md5(microtime()+mt_rand());
            $nickname = self::createNickname();

            //查询用户昵称是否存在
            $isHaveNickname = M("user")->where("nickname like '%".$nickname."%'")->field("id")->find();
            if(!empty($isHaveNickname))
            {
                $nickname = self::createRandWord("\\u" . dechex(rand(29968, 50895)));
            }

            // 查询用户domain 
            $domain = M("user")->where(array("id"=>$_uid))->getField("domain");
            $_user_data = array(
                "username" => $_username,
                "nickname" => $nickname,
                "group_id" => $_group,
                "parent_id" => $_uid,
                "parent_path" => $_parentPath.",".$_uid,
                "reg_ip" => sprintf("%u", ipton(get_client_ip())),
                "reg_time" => time(),
                "log_lasttime" => time(),
                "password" => md5(md5($_password.C("PASSWORD_HALT")).$safe_key),
                "acc_password" => "",
                "safe_key" => $safe_key,
                "cur_account" => 0,
                "domain"    =>  $domain
            );
            $userid = $userModel->add($_user_data);
            if($userid>0) {
                //插入用户奖金信息表
                $reward_array = C("REWARDS");
                $rewardKey = strval($_keeppoint);
                $user_bonus_data = array(
                    "userid" => $userid,
                    "parent_id" => $_uid,
                    "parent_path" => $_parentPath.",".$_uid,
                    "bonus_content" => $reward_array[$rewardKey],
                    "klc_bonus_content" => $_klckeeppoint,
                    "bonus_level" => $_keeppoint,
                    "last_bonus_level"=>$_keeppoint
                );
                $userBonusId = M("user_bonus")->add($user_bonus_data);
                if($userBonusId>0) {
                    //配额帐变
                    $_account_data = array(
                        "userid" => $_uid,
                        "username" => $_name,
                        "parent_id" => $_parentId,
                        "parent_path" => $_parentPath,
                        "relation_username" => $_username,
                        "relation_userid" => $userid,
                        "interval_range" => $_interval_str,
                        "remark" => 1,
                        "past_interval" => $_bfefor_interval,
                        "inr_interval" => 0,
                        "dec_interval" => 1,
                        "current_interval" => $_after_interval,
                        "interval_time" => time()
                    );
                    M("interval_change")->add($_account_data);
                    //添加配额表数据
                    M("user_interval")->add(array("userid"=>$userid,"parent_id"=>$_uid,"parent_path"=>$_parentPath.",".$_uid,"interval_time"=>time()));
                    //增加默认配额
                    if ($_group == 4 && bccomp($_keeppoint, 12.4, 2) == 1) {
                        //查询初始配额
                        $user_peie_init_res = M("user_peie_init")->find();
                        $interval_1 = 0;
                        $interval_2 = 0;
                        $interval_3 = 0;
                        $interval_4 = 0;
                        $interval_5 = 0;
                        if (bccomp($_keeppoint, 12.8, 2) == 0 || bccomp($_keeppoint, 12.9, 2) == 0) {
                            $interval_2 = $user_peie_init_res["pei127"];
                            $interval_3 = $user_peie_init_res["pei126"];
                            $interval_4 = $user_peie_init_res["pei125"];
                            $interval_5 = $user_peie_init_res["pei124"];
                        } else if (bccomp($_keeppoint, 12.7, 2) == 0) {
                            $interval_3 = $user_peie_init_res["pei126"];
                            $interval_4 = $user_peie_init_res["pei125"];
                            $interval_5 = $user_peie_init_res["pei124"];
                        } else if (bccomp($_keeppoint, 12.6, 2) == 0) {
                            $interval_4 = $user_peie_init_res["pei125"];
                            $interval_5 = $user_peie_init_res["pei124"];
                        } else if (bccomp($_keeppoint, 12.5, 2) == 0) {
                            $interval_5 = $user_peie_init_res["pei124"];
                        }
                        for ($i = 1; $i <= 5; $i++) {
                            $name = "interval_" . $i;
                            if ($$name > 0) {
                                //上级减少相应的配额
                                M()->execute("UPDATE user_interval SET $name=$name+" . $$name . " WHERE userid=" . $userid);
                                $current_interval = M("user_interval")->where(array("userid" => $userid))->getField($name);
                                unset($account);
                                $account = array(
                                    "userid" => $userid,
                                    "username" => $_username,
                                    "parent_id" => $_uid,
                                    "parent_path" => $_parentPath . "," . $_uid,
                                    "relation_userid" => $_uid,
                                    "relation_username" => $_name,
                                    "interval_range" => $i,
                                    "remark" => 15,
                                    "past_interval" => 0,
                                    "inr_interval" => $$name,
                                    "dec_interval" => 0,
                                    "current_interval" => $current_interval,
                                    "interval_time" => time()
                                );
                                M("interval_change")->add($account);
                            }
                        }
                    }
                    // 增加MG用户
                    $MgGame = new MgGame();
                    $MgGame->createMgUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));
                    
                    // 增加EBET用户
                    $EbetGame = new EbetGame();
                    $EbetGame->createEbetUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));

                    // 增加PT用户
                    $PtGame = new PtGame();
                    $PtGame->createPtUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));
                    
                    // 增加AG用户
                    $AgGame = new AgGame();
                    $AgGame->createAgUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));
                    echo "开户成功-".$_username.'-'.$userid.'-'.$_uid;
                } else {
                    echo "开户失败";
                }
            } else {
                echo "开户失败";
            }
        } else {  //不需要配额进行开号
            //将用户基本信息添加到用户表
            $safe_key = md5(microtime() + mt_rand());
            $nickname = self::createNickname();

            // 查询用户domain 
            $domain = M("user")->where(array("id"=>$_uid))->getField("domain");

            $_user_data = array(
                "username" => $_username,
                "nickname" => $nickname,
                "group_id" => $_group,
                "parent_id" => $_uid,
                "parent_path" => $_parentPath . "," . $_uid,
                "reg_ip" => sprintf("%u", ipton(get_client_ip())),
                "reg_time" => time(),
                "log_lasttime" => time(),
                "password" => md5(md5($_password . C("PASSWORD_HALT")) . $safe_key),
                "acc_password" => "",
                "safe_key" => $safe_key,
                "cur_account" => 0,
                "domain"    =>  $domain
            );
            $userid = $userModel->add($_user_data);
            if ($userid > 0) {
                //插入用户奖金信息表
                $reward_array = C("REWARDS");
                $rewardKey = strval($_keeppoint);
                $user_bonus_data = array(
                    "userid" => $userid,
                    "parent_id" => $_uid,
                    "parent_path" => $_parentPath . "," . $_uid,
                    "bonus_content" => $reward_array[$rewardKey],
                    "klc_bonus_content" => $_klckeeppoint,
                    "bonus_level" => $_keeppoint,
                    "last_bonus_level" => $_keeppoint
                );
                $userBonusId = M("user_bonus")->add($user_bonus_data);
                if($userBonusId>0){
                    //添加配额表数据
                    M("user_interval")->add(array("userid"=>$userid,"parent_id"=>$_uid,"parent_path"=>$_parentPath.",".$_uid,"interval_time"=>time()));
                    // 增加MG用户
                    $MgGame = new MgGame();
                    $MgGame->createMgUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));
                    
                    // 增加EBET用户
                    $EbetGame = new EbetGame();
                    $EbetGame->createEbetUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));

                    // 增加PT用户
                    $PtGame = new PtGame();
                    $PtGame->createPtUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));
                    
                    // 增加AG用户
                    $AgGame = new AgGame();
                    $AgGame->createAgUser($_username, $userid, $_uid, $_parentPath . "," . $_uid, $_group, time(), sprintf("%u", ipton(get_client_ip())));
                    echo "开户成功-".$_username.'-'.$userid.'-'.$_uid;
                } else {
                    echo "开户失败";
                }
            } else {
                echo "开户失败";
            }
        }
    }


    /**
     * 添加用户参数检查
     */
    public function checkParameter($_group,$_username,$_keeppoint,$bonusLevel)
    {
        if(session("SESSION_ROLE")==5 || !in_array($_group, array(4,5))
            || !preg_match("/^[0-9a-zA-Z]{5,10}$/", $_username) || !preg_match("/^(\d)+(.\d)+$/", $_keeppoint) || bccomp($_keeppoint,$bonusLevel,2)==1)
        {
            echo "请按要求提交数据[用户名长度5到10位]";exit();
        }
        $UserInterval = new UserInterval();
        $userInterval = $UserInterval->getQuateList();
        $_a_1 = $userInterval["interval_1"];
        $_a_2 = $userInterval["interval_2"];
        $_a_3 = $userInterval["interval_3"];
        $_a_4 = $userInterval["interval_4"];
        $_a_5 = $userInterval["interval_5"];
        if(bccomp($_keeppoint,12.3,2)==1 && bccomp($_keeppoint,12.8,2)!=0) {
            if(bccomp($_keeppoint,12.8,2)==0) {
                if(bccomp($_a_1,0,2)==0)
                {
                    echo "返点[12.8]区间配额不足";
                    exit();
                }
            } else if(bccomp($_keeppoint,12.7,2)==0) {
                if(bccomp($_a_2,0,2)==0)
                {
                    echo "返点[12.7]区间配额不足";
                    exit();
                }
            } else if(bccomp($_keeppoint,12.6,2)==0) {
                if(bccomp($_a_3,0,2)==0)
                {
                    echo "返点[12.6]区间配额不足";
                    exit();
                }
            } else if(bccomp($_keeppoint,12.5,2)==0) {
                if(bccomp($_a_4,0,2)==0)
                {
                    echo "返点[12.5]区间配额不足";
                    exit();
                }
            } else if(bccomp($_keeppoint,12.4,2)==0) {
                if(bccomp($_a_5,0,2)==0)
                {
                    echo "返点[12.4]区间配额不足";
                    exit();
                }
            }
        }
        $User = new User();
        if($User->checkUsername($_username))
        {
            echo '用户名已经存在,请更换用户名注册';
            exit();
        }
        if(bccomp($bonusLevel,$_keeppoint,3)==-1){
            echo "返点设置不正确";
            exit();
        }
    }

    /**
     * 创建开户连接
     */
    public function createRegist()
    {
        if($this->userType==1) exit();
        $_uid = session("SESSION_ID");
        //检查返点是否在设置范围内
        $point = I("post.keeppoint","12.3");
        $regklckeeppoint = I("post.regklckeeppoint","0");
        $point = sprintf("%.1f",$point);
        if(bccomp($point,12.3,3)==1 || bccomp($point,0.0,3)==-1){
            echo "返点设置不正确";
            exit();
        }
        if(bccomp($regklckeeppoint,0,2)==-1 || bccomp($regklckeeppoint,0.8,2)==1)
        {
            echo "返点设置不正确";
            exit();
        }
        // 查询幸运28的返点
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $key = md5("userbonus".$_uid);
        if($redisObj->exists($key))
        {
            $userBonusInfo = json_decode($redisObj->_get($key), true);
        }
        else
        {
            $userBonusInfo = M("user_bonus")->where(array('userid'=>$_uid))->find();
            $redisObj->_set($key,json_encode($userBonusInfo));
        }
        $klcbonuscontent = $userBonusInfo["klc_bonus_content"];
        
        if(bccomp($regklckeeppoint,$klcbonuscontent,2)==1)
        {
            echo "幸运28的返点设置不能高于自身返点";
            exit();
        }

        // 查询用户domain 
        $domain = M("user")->where(array("id"=>$_uid))->getField("domain");

        $group = I("post.reggroup","5");
        $limitday = I("post.limitday","99999999");
        $count = I("post.count","9999");
        $id = M("regist")->add(array(
            "userid" => $_uid,
            "point" => $point,
            "klcpoint" => $regklckeeppoint,
            "group" => $group,
            "limitday" => $limitday,
            "count" => $count,
            "createtime" => time(),
            "endtime" => (time()+$limitday*3600*24),
            "domain"    =>  $domain
        ));
        if($id)
        {
            import("Class.XDeode");
            $_xDe=new \XDeode();
            $item['ser'] = $_xDe->encode($id);
            $item['msg'] = '开户连接创建成功';
            $item['group'] = ($group==5) ? '会员' : '代理';
            $item['point'] = $point;
            $item['klcpoint'] = $regklckeeppoint;
            $item['lefttime'] = $limitday*24;
            $item['count'] = $count;
            $item['createtime'] = date("Y-m-d H:i:s",time());
            $item['domain'] = $domain;
            $item['flag'] = true;
        }
        else
        {
            $item['msg'] = '开户连接创建失败';
            $item['falg'] = false;
        }
        echo json_encode($item);
    }

    /**
     * 删除开户连接
     */
    public function delRegist()
    {
        $ser = I("post.ser","");
        $uid = session("SESSION_ID");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $id = $_xDe->decode($ser);
        M("regist")->where(array("id"=>$id,"userid"=>$uid))->delete();
        echo "链接删除成功";
        exit();
    }

    /**
    *随机生成汉字
    */
    public static function createRandWord()
    {
        // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
        $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
        preg_match_all($pattern, $name, $matches);
        if (!empty($matches)) {
            $name = '';
            for ($j = 0; $j < count($matches[0]); $j++) {
                $str = $matches[0][$j];
                if (strpos($str, '\\u') === 0) {
                    $code = base_convert(substr($str, 2, 2), 16, 10);
                    $code2 = base_convert(substr($str, 4), 16, 10);
                    $c = chr($code) . chr($code2);
                    $c = iconv('UCS-2', 'UTF-8', $c);
                    $name.= $c;
                } else {
                    $name.= $str;
                }
            }
        }
        return $name;
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