<?php

namespace Home\Controller;

use Think\Controller;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use \Home\Model\OrderToMongoModel;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class SmallController extends CommonController {

    var $username = '';  //用户名
    var $userid = 0;     //用户ID
    var $group_id = 0;    //用户
    var $parent_id = 0;    //用户的直接上级用户ID
    var $parent_path = "";  //用户的上级路径
    var $lottery_id = 0;  //彩票ID
    var $lotteryName = 0;  //彩票名称
    var $lottery_num_id = 0;   //期号ID
    var $amount = 0;
    var $currentamount = 0;
    var $is_add = 0;        //追号时，该变量应该是为1，默认为0
    var $mul = 0;          //倍数
    var $yj = 0;           //圆角模式，0为元模式，1为角模式
    var $eleven = "";
    var $userinfos = array(); //用户信息数组。
    var $ip = '';
    var $series_number = '';
    var $redis = "";
    var $date_now = 0;
    var $lottery_config = array(); //彩票种类配置
    var $lottery_play_way = array(); //玩法配置数组
    var $bonusType = '';     //是否需要返点
    var $bonusArray = array();  //奖金组
    var $jj = 0;  //选择的奖金
    var $fd = 0;  //选择的返点
    var $jjstatus = 1;  //默认为需要返点
    var $isStop = 1;
    var $redisObj = null;  //定义redis对象
    var $randkey = null;
    var $tokenValue = null;   //新的token值
    var $orderToMongo = null;  //mongodb对象
    var $foundchange = null;  // 资金变化对象

    public function _initialize() {

        import("Class.RedisObject");
        $this->redisObj = new \RedisObject();

        //+++++++++++++++++++++++++++++++++++
        //防止订单重复提交
        //+++++++++++++++++++++++++++++++++++
        $this->randkey = I("post.com", "");
        $circulateRedisKey = md5($this->randkey);
        if ($this->redisObj->exists($circulateRedisKey)) {
            $circulateRandKey = $this->redisObj->_get($circulateRedisKey);
            if ($circulateRandKey == $this->randkey) {
                echo json_encode(array("replaykey" => $this->randkey));
                exit();
            }
        } else {
            $this->redisObj->_set($circulateRedisKey, $this->randkey);
            $this->redisObj->_expire($circulateRedisKey, 10);
        }

        //+++++++++++++++++++++++++++++++++++
        //对参数进行XSS过滤
        //+++++++++++++++++++++++++++++++++++
        foreach ($_POST as $key => $value) {
            $val = remove_xss($value);
            $_POST[$key] = $val;
        }
        //+++++++++++++++++++++++++++++++++++
        //对变量进行赋值
        //+++++++++++++++++++++++++++++++++++
        $this->userid = session("SESSION_ID");
        $this->lottery_id = I("post.lottery_id", "");
        $this->lottery_num_id = I("post.lottery_number_id", "");
        $this->is_add = I("post.is_add", "");
        $this->date_now = time();
        $this->isStop = $_POST["isStop"];
        $this->username = session("SESSION_NAME");
        $this->group_id = session("SESSION_ROLE");
        $this->parent_path = session("SESSION_PATH");
        $this->parent_id = session("SESSION_PARENTID");
        $this->ip = ipton(get_client_ip());
        $this->lottery_config = C('LOTTERY');
        $this->lottery_play_way = C('LOTTERY_PLAY_WAY');
        $this->orderToMongo = new OrderToMongoModel();
        $this->foundchange = new FoundChange();

        //+++++++++++++++++++++++++++++++++++
        //用户及彩票初始信息验证
        //+++++++++++++++++++++++++++++++++++

        if(empty(session("SESSION_ID"))|| empty(session("SESSION_NAME")))
        {
            exit();
        }
        $key = md5("userbonus".$this->userid);
        if($this->redisObj->exists($key))
        {
            $userBonusInfo = json_decode($this->redisObj->_get($key), true);
        }
        else
        {
            $userBonusInfo = M("user_bonus")->where(array('userid'=>$this->userid))->find();
            $this->redisObj->_set($key,json_encode($userBonusInfo));
        }
        $bonus_level = $userBonusInfo["bonus_level"];
        if(session("SESSION_ROLE")==3){
            echo json_encode(array("status"=>0,"info"=>"无投注权限！","key"=>$this->randkey));
            exit();
        }
        $lotterySwitchRes = M("lottery_switch")->where(array("lottery_id" => $this->lottery_id))->find();
        if ($lotterySwitchRes["switch"] == "close") {
            echo json_encode(array("status" => 0, "info" => "该彩票暂时关闭！", "key" => $this->randkey));
            exit();
        }
        $userResult = M("user")->where(array("id" => $this->userid))->find();
        //判断该用户的投注状态设置 0 正常 1禁止 2掉线 3延迟
        if($userResult['status']==2 || $userResult["tzstatus"] == 2)
        {
            session("SESSION_ID", null);
            session("SESSION_NAME", null);
            session("SESSION_ROLE", null);
            redirect("login");
        }
        if ($userResult["tzstatus"] == 1) {
            exit();
        }
        if ($userResult["tzstatus"] == 3) {
            sleep($userResult["delay"]);
        }
        //检查彩票ID是否合法
        if (!checkLotteryId($this->lottery_id)) {

            echo json_encode(array("status" => 0, "info" => "该彩票不存在！", "key" => $this->randkey));
            exit();
        }
        $lotteryArray = C("LOTTERY");
        $this->lotteryName = $lotteryArray[$this->lottery_id]['name'];


        //先判断该期号码是否已经开，如果已经开，不允许购买
        $now_series_number = M("lottery_number_memory")->field("series_number,id")->where(array("lottery_id" => $this->lottery_id, "starttime" => array("elt", $this->date_now), "endtime" => array("gt", $this->date_now)))->find();
        $now_lottery_number = M("lottery_number_swoop")->where(array("lottery_id" => $this->lottery_id, "series_number" => $now_series_number["series_number"]))->find();
        if (!empty($now_lottery_number)) {
            if ($now_lottery_number["lottery_number"] != "") {
                echo json_encode(array("status" => 0, "info" => "该期停止购买！", "key" => $this->randkey));
                exit();
            }
        }
        $this->series_number = $now_series_number["series_number"];
        //检查参数
        if (!in_array($this->is_add,array(0,1)) || !in_array($this->isStop,array(0,1)) || (!checkNumber($this->lottery_num_id, '', '', 'int') || $this->lottery_num_id <= 0)) {
            exit();
        }
        //判断提交地址来源[如果是龙虎地址的时候不检查]
        if(strpos($_SERVER['HTTP_REFERER'],"Lotter/LF/lf")==false)
        {
            if (!sourceURL($_SERVER['HTTP_REFERER'], $this->lottery_id)) {
                echo json_encode(array("status" => 0, "info" => "地址来源异常！".$_SERVER['HTTP_REFERER'], "key" => $this->randkey));
                exit();
            }
        }
        //判断提交的金额
        $this->amount = $_POST['amount'];
        if (!isNumber($this->amount) || $this->amount <= 0) {
            echo json_encode(array("status" => 0, "info" => "金额异常！", "key" => $this->randkey));
            exit();
        }
        //如果是正常购买，需要判断该期号ID是否属于当前进行期。
        if ($this->is_add == 0) {
            $this->series_number = getCurrentNumID($this->lottery_num_id, 0);
            if ($this->series_number == false) {
                echo json_encode(array("status" => 0, "info" => "该期已截止购买！", "key" => $this->randkey));
                exit();
            }
        } else if ($this->is_add == 1) {
            $this->series_number = getCurrentNumID($this->lottery_num_id, 1);
            if ($this->series_number == false) {
                echo json_encode(array("status" => 0, "info" => "该期已截止购买！", "key" => $this->randkey));
                exit();
            }
        }

        if (in_array($this->group_id,array(1,2,6,7))) {
            echo json_encode(array("status" => 0, "info" => "该账户不能进行游戏！", "key" => $this->randkey));
            exit();
        }

        if (empty($_POST['data'])) {
            exit();
        }


        //+++++++++++++++++++++++++++++++++++
        //用户奖金数据
        //+++++++++++++++++++++++++++++++++++
        $userbonus = $this->CreateUserBonus(0, '', false);
        $bonusArray = array();
        foreach ($userbonus as $key => $value) {
            switch ($key) {
                case '1' :
                    $bonusArray["SSC"] = array_map("doArray", $value);
                    break;
                case '5' :
                    $bonusArray["11X5"] = array_map("doArray", $value);
                    break;
                case '9' :
                    $bonusArray['KLSF'] = array_map("doArray", $value);
                    break;
                case '11' :
                    $bonusArray['P3D'] = array_map("doArray", $value);
                    break;
                case '13' :
                    $bonusArray['KL8'] = array_map("doArray", $value);
                    break;
                case '14' :
                    $bonusArray['SSQ'] = array_map("doArray", $value);
                    break;
                case '15' :
                    $bonusArray['KL3'] = array_map("doArray", $value);
                    break;
                default: break;
            }
        }
        $configArray = array(
            "1" => "SSC", "2" => "SSC", "3" => "SSC", "4" => "SSC", "17" => "SSC", "18" => "SSC", "19" => "SSC","30"=>"SSC","31"=>"SSC",
            "5" => "11X5", "6" => "11X5", "7" => "11X5", "8" => "11X5", "24" => "11X5", "25" => "11X5",
            "9" => "KLSF", "10" => "KLSF", "26" => "KLSF", "27" => "KLSF",
            "11" => "P3D", "12" => "P3D",
            "13" => "KL8", "14" => "SSQ",
            "15" => "KL3", "16" => "KL3","23" => "XY28","28" => "XY28","29" => "XY28", "32" => "SSC", "33"=>"SSC", "34"=>"SSC"
        );
        $key = $configArray[$this->lottery_id];
        $this->bonusArray = $bonusArray[$key];

        switch ($this->lottery_id) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 17:
            case 18:
            case 19:
            case 23:
            case 28:
            case 29:
            case 30:
            case 32:
            case 33:
            case 34:
            case 31:$this->lotteryid = 1;
                break;
            case 5:
            case 6:
            case 7:
            case 8:
            case 24:
            case 25:$this->lotteryid = 5;
                break;
            case 9:
            case 10:
            case 26:
            case 27:$this->lotteryid = 9;
                break;
            case 11:
            case 12:$this->lotteryid = 11;
                break;
            case 13:$this->lotteryid = 13;
                break;
            case 14:$this->lotteryid = 14;
                break;
            case 15:
            case 16:$this->lotteryid = 15;
                break;
        }
    }

    /*
     * 负责调度的一个方法。判断是正常购买调用正常购买的方法，否则调用追号购买
     *
     */

    public function index() {

        if ($this->is_add == 0) {
            $this->normalBuy();
        } elseif ($this->is_add == 1) {
            $this->addBuy();
        }
    }

    /*
     *
     * 正常购买程序
     *
     */

    public function normalBuy() {
        //data是购买号码格式。例如  五星直选复式:7,7,7,7,789:3:1:6:0
        $data = trim($_POST['data']);
        $data_arr = explode("||", $data);
        $money_total = 0;
        $data_array = array();
        $temp_data = array();
        $temp_amount = 0;
        $i = 0;
        foreach ($data_arr AS $val) {
            if (empty($val)) continue;
            /* $temp_data变成有5个单元的数组。
             *  $temp_data[0]     玩法名称
             *  $temp_data[1]     购买的号码字符串。例如：7,7,7,7,789
             *  $temp_data[2]     号码产生的注数
             *  $temp_data[3]      默认的倍数为1
             *  $temp_data[4]      所产生的金额
             *  $temp_data[5]      模式 0：元  1：角  2：分
             *  $temp_data[6]      奖金转返点
             *  $temp_data[7]      倍数
             */
            $temp_data = explode(":", $val);

            if ($this->is_add == 0) {

                if (!checkNumber($temp_data[7], '', '', 'int') || $temp_data[7] <= 0) {
                    echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                    exit();
                }
                if (!checkNumber($temp_data[5], '', '', 'int') || $temp_data[5] < 0 || !in_array($temp_data[5],array(0,1,2,3))) {

                    echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                    exit();
                }
            }
            if (!checkNumber($temp_data[3], '', '', 'int') || !checkNumber($temp_data[2], '', '', 'int')) {

                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }

            //购买号码获取
            $temp_buy_num = trim($temp_data[1]);
            if ($temp_buy_num == '' || $temp_buy_num === false) {
                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }

            //玩法名称获取
            $temp_wanfa = trim($temp_data[0]);
            if (isset($this->lottery_play_way["$temp_wanfa"]) == false || empty($this->lottery_play_way["$temp_wanfa"])) {

                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }

            //+++++++++++++++++++++++++++++++++++
            //玩法注数验证
            //+++++++++++++++++++++++++++++++++++
            import("Class.Ssc");
            $Ssc = new \Ssc();
            $Ssc->checknumformat($temp_buy_num, $temp_data[2], $this->lottery_id, $temp_wanfa, $temp_data[8], $this->lottery_num_id);

            //+++++++++++++++++++++++++++++++++++
            //用户返点
            //+++++++++++++++++++++++++++++++++++
            $bonus_content = M("userBonus")->where(array("userid" => $this->userid))->getField("bonus_content");
            if ($bonus_content != "") {
                $bonus = (array) json_decode($bonus_content);
                if (!empty($bonus)) {
                    foreach ($bonus as $k => $v) {
                        $userFandian ["bonus"] [$k] = (array) $v;
                    }
                }
            }

            //+++++++++++++++++++++++++++++++++++
            //快三彩票的时候 奖金模式默认为1
            //幸运28的时候 奖金默认为2
            //+++++++++++++++++++++++++++++++++++
            if (empty($data_base[6]) && in_array($this->lottery_id, array(15, 16))) {
                $jjstatus = 1;
            } else if(empty($data_base[6]) && ($this->lottery_id==23 || $this->lottery_id==28 || $this->lottery_id==29))
            {
                $jjstatus = 2;
            }
            //+++++++++++++++++++++++++++++++++++
            //快三时不用验证玩法的奖金返点
            //只需要验证具有高低奖金模式的彩种
            //+++++++++++++++++++++++++++++++++++
            if (!in_array($this->lottery_id, array(15, 16, 23, 28, 29))) {

                if (empty($temp_data[6])) {
                    echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                    exit();
                } else {
                    $bonusTypeArray = explode("|", trim($temp_data[6]));
                    $jj = $bonusTypeArray[0];
                    $fd = $bonusTypeArray[1];
                    $jjstatus = $bonusTypeArray[2];
                }

                //+++++++++++++++++++++++++++++++++++
                //验证低奖金模式
                //+++++++++++++++++++++++++++++++++++
                if ($jjstatus == 1) {
                    //[验证奖金]
                    if (strpos($temp_data[0], "混合组选") !== false || strpos($temp_data[0], "任三组选和值") !== false) {
                        $jjArray = explode("-", $jj);
                        if ($jjArray[0] != $this->bonusArray["前三组三"] || $jjArray[1] != $this->bonusArray["前三组六"]) {
                            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                            exit();
                        }
                    } else {
                        if ($jj != $this->bonusArray[$temp_data[0]]) {
                            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                            exit();
                        }
                    }
                    //[验证返点]
                    if (strpos($temp_data[0], "不定位") !== false) {
                        $_a_1 = $userFandian["bonus"][$this->lotteryid]["bdw_ret_point"];
                        if (bccomp($fd, $_a_1, 3) != 0) {
                            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                            exit();
                        }
                    } else {
                        $_a_2 = $userFandian["bonus"][$this->lotteryid]["common_ret_point"];
                        if (bccomp($fd, $_a_2, 3) != 0) {
                            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                            exit();
                        }
                    }
                }
                //+++++++++++++++++++++++++++++++++++
                //END
                //+++++++++++++++++++++++++++++++++++


                //+++++++++++++++++++++++++++++++++++
                //验证高奖金模式BEGIN
                //+++++++++++++++++++++++++++++++++++
                if ($jjstatus == 2) {
                    if (in_array($this->lottery_id, array(1, 2, 3, 4, 17, 18, 19,30,31, 32, 33,34)))
                        $changeBonus = C("changeBonus");
                    else if (in_array($this->lottery_id, array(11, 12)))
                        $changeBonus = C("p3dchangeBonus");
                    //[验证返点和奖金]
                    if (strpos($temp_data[0], "不定位") !== false) {
                        $_b_max = $userFandian["bonus"][$this->lotteryid]["bdw_ret_point"];
                        $A = $_b_max - $fd;
                        foreach ($changeBonus[$temp_data[0]] as $k => $v) {
                            if (bccomp($k, $A, 3) == 0)
                                $kv = $v;
                        }
                        if (bccomp($jj, $kv, 3) != 0) {
                            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                            exit();
                        }
                    } else {
                        $_c_max = $userFandian["bonus"][$this->lotteryid]["common_ret_point"];
                        $A = $_c_max - $fd;
                        if (strpos($temp_data[0], "混合组选") !== false || strpos($temp_data[0], "任三组选和值") !== false) {
                            $jjArray = explode("-", $jj);
                            foreach ($changeBonus["前三组三"] as $k => $v) {
                                if (bccomp($k, $A, 3) == 0)
                                    $kvzs = $v;
                            }
                            if (bccomp($jjArray[0], $kvzs, 3) != 0) {
                                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                                exit();
                            }
                            foreach ($changeBonus["前三组六"] as $k => $v) {
                                if (bccomp($k, $A, 3) == 0)
                                    $kvzl = $v;
                            }
                            if (bccomp($jjArray[1], $kvzl, 3) != 0) {
                                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                                exit();
                            }
                        } else {
                            foreach ($changeBonus[$temp_data[0]] as $k => $v) {
                                if (bccomp($k, $A, 3) == 0)
                                    $kv = $v;
                            }
                            if (bccomp($jj, $kv, 3) != 0) {
                                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                                exit();
                            }
                        }
                    }
                }
                //+++++++++++++++++++++++++++++++++++
                //END
                //+++++++++++++++++++++++++++++++++++
            }
            //+++++++++++++++++++++++++++++++++++
            //验证订单的购买金额[如果是快乐彩或者是时时彩的龙虎不验证金额]
            //+++++++++++++++++++++++++++++++++++
            if(!in_array($this->lottery_id,array(23,28,29)) && strpos($temp_data[0], "龙虎和") === false)
            {
                if ($temp_data[5] == 0) {
                    $fen = $temp_data[4];
                    $cmp = 2 * $temp_data[2] * $temp_data[7];
                    if ($fen != $cmp) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                } else if ($temp_data[5] == 1) {
                    $fen = $temp_data[4];
                    $cmp = 0.2 * $temp_data[2] * $temp_data[7];
                    if (bccomp($fen, $cmp, 4) != 0) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                } else if ($temp_data[5] == 2) {
                    $fen = $temp_data[4];
                    $cmp = 0.02 * $temp_data[2] * $temp_data[7];
                    if (bccomp($fen, $cmp, 4) != 0) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                } else if ($temp_data[5] == 3) {
                    $fen = $temp_data[4];
                    $cmp = 0.002 * $temp_data[2] * $temp_data[7];
                    if (bccomp($fen, $cmp, 4) != 0) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                }
            }

            // 幸运28彩票
            if($this->lottery_id==23 || $this->lottery_id==28 || $this->lottery_id==29)
            {
                $_com_fd = M("userBonus")->where(array("userid" => $this->userid))->getField("klc_bonus_content");
                $xy28bonus = C("xy28_bonus");
                $bonus = $xy28bonus[70]["$temp_buy_num"];
                $bonusType = $bonus."|".$_com_fd."|2";
            }
            else
            {
                $bonusType = $temp_data[6];
            }

            $temp_amount+=$temp_data[4];
            $this->currentamount += $temp_data[4] * 100000;

            $data_array[$i]['号码'] = $temp_buy_num;
            $data_array[$i]['玩法'] = $temp_wanfa;
            $data_array[$i]['倍数'] = $temp_data[7];
            $data_array[$i]['金额'] = $temp_data[4] * 100000;
            $data_array[$i]['注数'] = $temp_data[2];
            $data_array[$i]['模式'] = $temp_data[5];
            $data_array[$i]['奖金类别'] = $bonusType;
            $data_array[$i]['返点状态'] = $jjstatus;
            $data_array[$i]['位置'] = $temp_data[8];
            $i++;
        }

        if (bccomp($temp_amount, $this->amount, 3) != 0) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }

        //+++++++++++++++++++++++++++++++++++
        //订单信息保存到数据库
        //+++++++++++++++++++++++++++++++++++
        $userModel = M('user');
        $buy_model = M('buy_record');
        $buy_model_rep = M('buy_record_rep'); //报表购买表
        $acc_model = M('accounts_change');

        $user_acc_rows = $userModel->field('cur_account')->where('id=' . $this->userid)->find();
        if (empty($user_acc_rows)) {
            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }
        if ($user_acc_rows['cur_account'] < $this->currentamount) {
            echo json_encode(array("status" => 0, "info" => "余额不足!", "key" => $this->randkey));
            exit();
        }
        $parent_path_arr = explode(",", $this->parent_path);
        if (count($parent_path_arr) > 1) {
            $zd_userid = $parent_path_arr[1];
        } else {
            $zd_userid = $this->userid;
        }

        //总代的返点
        $bonus_content = M("userBonus")->where(array("userid" => $zd_userid))->getField("bonus_content");
        if ($bonus_content != "") {
            $bonus = json_decode($bonus_content, true);
            if (!empty($bonus)) {
                foreach ($bonus as $k => $v) {
                    $zd_bonus ["bonus"] [$k] = $v;
                }
            }
        }

        echo json_encode(array("key" => $this->randkey, "status" => 1, "info" => "购买成功！"));
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++使用php fastcgi函数开始
        fastcgi_finish_request();

        //购买人的奖金返点信息
        $buyer_bonus = $userFandian;
        $M = M();

        foreach ($data_array AS $val) {
            //+++++++++++++++++++++++++++++++++++
            //SeasLog记录用户提交的数据
            //+++++++++++++++++++++++++++++++++++
            \SeasLog::setLogger("GameCathectic");
            $log = $this->username."正常投注".$this->lotteryName.$this->series_number."期".urldecode($val['玩法'])."号码:\r\n".$val['号码']."\r\n注数:".$val['注数']."倍数:".$val['倍数']."模式:".$val['模式']."奖金返点:".$val['奖金类别']."总金额".sprintf("%.4f",$val['金额']/100000);

            if (strpos($val['玩法'], '不定位') !== false) {
                $return_point = $buyer_bonus['bonus'][$this->lotteryid]['bdw_ret_point'];
                $zd_return_point = $zd_bonus['bonus'][$this->lotteryid]['bdw_ret_point'];
            } else {
                $return_point = $buyer_bonus['bonus'][$this->lotteryid]['common_ret_point'];
                $zd_return_point = $zd_bonus['bonus'][$this->lotteryid]['common_ret_point'];
            }


            //购买获得自己的返点金额
            if ($val['返点状态'] == 2) {
                $return_money = 0;
            } elseif ($val['返点状态'] == 1) {
                $return_money = $val['金额'] * $return_point / 100;
            } else {
                $return_money = $val['金额'] * $return_point / 100;
            }
            //总的返点金额。
            $total_return_money = $val['金额'] * $zd_return_point / 100;


            //通过玩法名称获取玩法ID
            $play_rows_name = $val['玩法'];
            $play_rows['id'] = $this->lottery_play_way[$play_rows_name];
            if (empty($play_rows)) {
                $play_way_model = M('lottery_play_way');
                $play_rows = $play_way_model->field('id')->where('name="' . $val['玩法'] . '"')->find();
            }


            //+++++++++++++++++++++++++++++++++++
            //组合插入购买表的数组
            //+++++++++++++++++++++++++++++++++++
            $buy_data = array(
                'id' => NULL,
                'lottery_id' => $this->lottery_id,
                'lottery_number_id' => $this->lottery_num_id,
                'buy_type_id' => $play_rows['id'],
                'userid' => $this->userid,
                'parent_id' => $this->parent_id,
                'parent_path' => $this->parent_path,
                'monetary' => $val['金额'],
                'return_money' => $return_money,
                'total_return_money' => $total_return_money,
                'buy_number' => $val['号码'],
                'multiple' => $val['倍数'],
                'zhushu' => $val['注数'],
                'ip' => $this->ip,
                'buy_time' => $this->date_now,
                'yuan' => $val['模式'],
                'status' => 0,
                'bonusType' => $val["奖金类别"],
                "bonusStatus" => $val["返点状态"],
                "position" => $val["位置"],
                "key" => $this->randkey,
                "youke" => 0
            );

            //开始启用innodb事务功能。
            $buy_model->startTrans();
            $auto_key_id = $buy_model->add($buy_data);
            if ($auto_key_id) {
                $buy_data['id'] = $auto_key_id;
                $buy_model_rep->add($buy_data);
                import("Class.XDeode");
                $_xDe = new \XDeode(9, 3456.783465231283);
                $buy_series_number = strtoupper($_xDe->encode($auto_key_id));

                $update_serial = $buy_model->where('id=' . $auto_key_id)->save(array('serial_number' => "$buy_series_number"));
                $buy_model_rep->where('id=' . $auto_key_id)->save(array('serial_number' => "$buy_series_number"));

                //购买前的余额
                $_before_money = $userModel->field('cur_account')->where('id=' . $this->userid)->find();
                //购买之后的余额
                $_after_money = $_before_money["cur_account"] - $val['金额'];
                //减去返点的金额
                $increase_cur_acc = $val['金额'] - $return_money;

                $update_cur_acc = $M->execute("UPDATE `user` SET cur_account=(cur_account-$increase_cur_acc) WHERE
                id=" . $this->userid);
                //购买后，减去购买金额 加上返点金额 之后的余额
                //$user_acc_rows = $userModel->field('cur_account')->where('id=' . $this->userid)->find();
                $after_fandian_acc = $_before_money["cur_account"]-$val['金额']+$return_money;
                $acc_data = array(
                    'id' => NULL,
                    'accounts_type' => 1,
                    'buy_record_id' => $auto_key_id,
                    'change_amount' => $val['金额'],
                    'userid' => $this->userid,
                    'username' => $this->username,
                    'parent_id' => $this->parent_id,
                    'parent_path' => $this->parent_path,
                    'cur_account' => $_after_money,
                    'serial_number' => $buy_series_number,
                    'runner_id' => $this->userid,
                    'runner_name' => $this->username,
                    'change_time' => $this->date_now,
                    'lottery_id' => $this->lottery_id,
                    'lottery_number_id' => $this->lottery_num_id,
                    'buy_type_id' => $play_rows['id'],
                    'yuan' => $val['模式'],
                    'remark' => '正常投注'
                );
                $auto_acc_id = $acc_model->add($acc_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($auto_acc_id));
                $acc_model->where(array("id" => $auto_acc_id))->save(array("achange_num" => $achange_num));
                unset($acc_data);
                $acc_data = array(
                    'id' => NULL,
                    'accounts_type' => 4,
                    'buy_record_id' => $auto_key_id,
                    'change_amount' => $return_money,
                    'userid' => $this->userid,
                    'username' => $this->username,
                    'parent_id' => $this->parent_id,
                    'parent_path' => $this->parent_path,
                    'cur_account' => $after_fandian_acc,
                    'serial_number' => $buy_series_number,
                    'runner_id' => $this->userid,
                    'runner_name' => $this->username,
                    'change_time' => $this->date_now,
                    'lottery_id' => $this->lottery_id,
                    'lottery_number_id' => $this->lottery_num_id,
                    'buy_type_id' => $play_rows['id'],
                    'yuan' => $val['模式'],
                    'remark' => "游戏返点"
                );
                $auto_acc_id_r = $acc_model->add($acc_data);
                //更新该条账变的账变编号
                $achange_num_r = strtoupper($_xDe->encode($auto_acc_id_r));
                $acc_model->where(array("id" => $auto_acc_id_r))->save(array("achange_num" => $achange_num_r));
                if ($update_serial && $update_cur_acc && $auto_acc_id && $auto_acc_id_r) {
                    \SeasLog::info($log."[投注成功]\r\n\r\n");

                    //将购买的数据放入mongDB中
                    $result = array();
                    $result["buy_id"] = intval($auto_key_id);
                    $result["userid"] = $this->userid;
                    $result["parent_path"] = $this->parent_path;
                    $result["parent_id"] = $this->parent_id;
                    $result["buy_add_id"] = 0;
                    $result["lottery_number_id"] = intval($this->lottery_num_id);
                    $result["lottery_id"] = intval($this->lottery_id);
                    $result["buy_type_id"] = $play_rows['id'];
                    $result["mul"] = $val['倍数'];
                    $result["buy_money"] = $val['金额'];
                    $result["buy_time"] = $this->date_now;
                    $result["return_money"] = $return_money;
                    $result["buy_number"] = $val['号码'];
                    $result["stop_add"] = 0;
                    $result["yuan"] = $val['模式'];
                    $result["serial_number"] = $buy_series_number;
                    $result["bonusType"] = $val["奖金类别"];
                    $result["bonusStatus"] = $val["返点状态"];
                    $result["position"] = $val["位置"];
                    $result["plan"] = 0;
                    $this->orderToMongo->add($result);
                    $buy_model->commit();
                    
                    // 增加资金变化
                    $FoundChangeData = array();
                    $FoundChangeData['userid'] = session("SESSION_ID");
                    $FoundChangeData['username'] = session("SESSION_NAME");
                    $FoundChangeData['parent_id'] = $this->parent_id;
                    $FoundChangeData['parent_path'] = $this->parent_path;
                    $FoundChangeData['beforeMoney'] = intval($_before_money["cur_account"]);
                    $FoundChangeData['money'] = intval($increase_cur_acc);
                    $FoundChangeData['afterMoney'] = intval(($_before_money["cur_account"]-$increase_cur_acc));
                    $FoundChangeData['time'] = time();
                    $FoundChangeData['remark'] = $this->lotteryName.$buy_series_number."常规购买";
                    $this->foundchange->add($FoundChangeData);
                } else {
                    \SeasLog::info($log."[投注失败]\r\n\r\n");
                    $buy_model->rollback();
                    exit();
                }
            } else {
                exit();
            }
        }
    }

    /*
     * 追号购买
     */

    private function addBuy() {

        $data = trim($_POST['data']);
        if (strpos($data, "*") === false) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }

        /* 先以*来分数组。
         * data_arr的格式是data_arr[0]=五星直选复式:6,678,89,9,89:12:1:24:0
         * data_arr[1]=290601:1:24||290602:1:24||290603:1:24||290604:1:24||290605:1:24
         */
        $data_arr = explode("*", $data);
        if (count($data_arr) != 2) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }
        //对数组第一个单元进行拆分处理。$data_base[0]是玩法名称，[1]购买的号码，[2]注数，[3]倍数，[4]金额, [5]模式 , [6]奖金转返点, [7]位置
        $data_base = explode(":", trim($data_arr[0]));
        $wanfa = trim($data_base[0]);

        //+++++++++++++++++++++++++++++++++++
        //快三默认奖金模式为低奖金
        //+++++++++++++++++++++++++++++++++++
        if (empty($data_base[6]) && in_array($this->lottery_id, array(15, 16))) {
            $jjstatus = 1;
        }
        //登陆用户的返点
        $bonus_content = M("userBonus")->where(array("userid" => $this->userid))->getField("bonus_content");
        if ($bonus_content != "") {
            $bonus = (array) json_decode($bonus_content);
            if (!empty($bonus)) {
                foreach ($bonus as $k => $v) {
                    $userFandian ["bonus"] [$k] = (array) $v;
                }
            }
        }
        //+++++++++++++++++++++++++++++++++++
        //快三不验证奖金返点
        //+++++++++++++++++++++++++++++++++++
        if (!in_array($this->lottery_id, array(15, 16))) {
            if (!empty($data_base[6])) {
                $bonusTypeArray = explode("|", trim($data_base[6]));
                $jj = $bonusTypeArray[0];
                $fd = $bonusTypeArray[1];
                $jjstatus = $bonusTypeArray[2];
            } else {
                exit();
            }
            if ($jjstatus == 1) {
                //检查提交的奖金返点转换数据[奖金]
                if (strpos($wanfa, "混合组选") !== false || strpos($wanfa, "任三组选和值") !== false) {
                    $jjArray = explode("-", $jj);
                    if ($jjArray[0] != $this->bonusArray["前三组三"] || $jjArray[1] != $this->bonusArray["前三组六"]) {
                        exit();
                    }
                } else {
                    if ($jj != $this->bonusArray[$wanfa]) {
                        exit();
                    }
                }
                //检查提交的奖金返点转换数据[返点]

                if (strpos($wanfa, "不定位") !== false) {
                    $_a_1 = $userFandian["bonus"][$this->lotteryid]["bdw_ret_point"];
                    if (bccomp($fd, $_a_1, 3) != 0) {
                        exit();
                    }
                } else {
                    $_a_2 = $userFandian["bonus"][$this->lotteryid]["common_ret_point"];
                    if (bccomp($fd, $_a_2, 3) != 0) {
                        exit();
                    }
                }
            }
            if ($jjstatus == 2) {
                if (in_array($this->lottery_id, array(1, 2, 3, 4, 17, 18, 19,30,31,32,33,34)))
                    $changeBonus = C("changeBonus");
                else if (in_array($this->lottery_id, array(11, 12)))
                    $changeBonus = C("p3dchangeBonus");
                //检查提交的奖金返点转换数据[返点]
                if (strpos($wanfa, "不定位") !== false) {
                    $_a_max = $userFandian["bonus"][$this->lotteryid]["bdw_ret_point"];
                    $A = $_a_max - $fd;
                    foreach ($changeBonus[$wanfa] as $k => $v) {
                        if (bccomp($k, $A, 3) == 0)
                            $kv = $v;
                    }

                    if (bccomp($jj, $kv, 3) != 0) {
                        exit();
                    }
                } else {
                    $_a_max = $userFandian["bonus"][$this->lotteryid]["common_ret_point"];
                    $A = $_a_max - $fd;
                    if (strpos($wanfa, "混合组选") !== false || strpos($wanfa, "任三组选和值") !== false) {
                        $jjArray = explode("-", $jj);
                        foreach ($changeBonus["前三组三"] as $k => $v) {
                            if (bccomp($k, $A, 3) == 0)
                                $kvzs = $v;
                        }
                        if ($jjArray[0] != $kvzs) {
                            exit();
                        }
                        foreach ($changeBonus["前三组六"] as $k => $v) {
                            if (bccomp($k, $A, 3) == 0)
                                $kvzl = $v;
                        }

                        if (bccomp($jjArray[1], $kvzl, 3) != 0) {
                            exit();
                        }
                    } else {
                        foreach ($changeBonus[$wanfa] as $k => $v) {
                            if (bccomp($k, $A, 3) == 0)
                                $kv = $v;
                        }
                        if (bccomp($jj, $kv, 3) != 0) {
                            exit();
                        }
                    }
                }
            }
        }

        $haoma = trim($data_base[1]);
        //模式
        $moshi = trim($data_base[5]);

        //判断是否为厘模式
        $jjzfdm = trim($data_base[6]);

        if (!in_array($moshi, array(0, 1, 2, 3))) {
            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }

        if (!checkNumber($data_base[2], '', '', 'int') || $data_base[2] < 0) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }
        if (!checkNumber($data_base[3], '', '', 'int') || $data_base[3] < 0) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }
        if (!isNumber($data_base[4]) || $data_base[4] < 0) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }

        //+++++++++++++++++++++++++++++++++++
        //玩法注数验证
        //+++++++++++++++++++++++++++++++++++
        import("Class.Ssc");
        $Ssc = new \Ssc();
        $Ssc->checknumformat($haoma, $data_base[2], $this->lottery_id, $wanfa, $data_base[7], $this->lottery_num_id, $this->randkey);


        //查出玩法ID
        $play_rows['id'] = $this->lottery_play_way[$wanfa];
        if (empty($play_rows)) {
            $play_way_model = M('lottery_play_way');
            $play_rows = $play_way_model->field('id')->where('name=' . "'" . $wanfa . "'")->find();
        }

        //对追号字符串进行拆分，也就是对data_arr[1]进行拆分。
        $data_add = explode("||", trim($data_arr[1]));

        //根据提交的追号倍数，金额叠加的总金额。
        $temp_amount = 0;
        $i = 0;
        //临时组装一个期号ID数组。
        $number_arr = array();
        $new_arr = array();
        foreach ($data_add as $val) {
            if (empty($val)) continue;

            //$temp_arr[0]期号ID，$temp_arr[1]倍数，$temp_arr[2]每一期追号的金额
            $temp_arr = explode(":", $val);

            if (!getCurrentNumID($temp_arr[0], 1)) {

                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }
            if ($i == 0) {
                $number_arr[$i] = $temp_arr[0];
            } else {
                $number_arr[$i] = $temp_arr[0];
                if ($number_arr[$i - 1] >= $temp_arr[0]) {
                    echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                    exit();
                }
            }

            if (!checkNumber($temp_arr[1], '', '', 'int') || $temp_arr[1] < 0) {

                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }

            if (!isNumber($temp_arr[2]) || $temp_arr[2] < 0) {

                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }


            if (!in_array($data_base[5], array(0, 1, 2, 3))) {
                echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                exit();
            }

            // 如果是玩法龙虎的时候不判断
            if(strpos($wanfa,"龙虎和")===false)
            {
                if ($data_base[5] == 0) {
                    $dzje = 2;
                    if ((int) ($data_base[2] * $temp_arr[1] * $dzje) != (int) $temp_arr[2]) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                } else if ($data_base[5] == 1) {
                    $fen = $temp_arr[2];
                    $cmp = $data_base[2] * $temp_arr[1] * 0.2;
                    if (bccomp($fen, $cmp, 4) != 0) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                } else if ($data_base[5] == 2) {
                    $fen = $temp_arr[2];
                    $cmp = $data_base[2] * $temp_arr[1] * 0.02;
                    if (bccomp($fen, $cmp, 4) != 0) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                } else if ($data_base[5] == 3) {
                    $fen = $temp_arr[2];
                    $cmp = $data_base[2] * $temp_arr[1] * 0.002;
                    if (bccomp($fen, $cmp, 4) != 0) {
                        echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
                        exit();
                    }
                }
            }

            $new_arr[$temp_arr[0]] = array(trim($temp_arr[1]), trim($temp_arr[2]) * 100000);
            $temp_amount += $temp_arr[2];
            $this->currentamount += $temp_arr[2];
            $i++;
        }
        //判断传上来的总金额

        if (bccomp($this->amount, $temp_amount, 4) != 0) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }
        //判断传上来的期号ID是否与追号第一期一致
        if ($number_arr[0] != $this->lottery_num_id) {

            echo json_encode(array("status" => 0, "info" => "购买失败", "key" => $this->randkey));
            exit();
        }

        $this->currentamount = $this->currentamount * 100000;


        $userModel = M("user");
        $user_acc_rows = $userModel->field('cur_account')->where('id=' . $this->userid)->find();
        if (empty($user_acc_rows)) {
            echo json_encode(array("status" => 0, "info" => "该用户不存在!", "key" => $this->randkey));
            exit();
        }
        if ($user_acc_rows['cur_account'] < $this->currentamount) {

            echo json_encode(array("status" => 0, "info" => "余额不足!", "key" => $this->randkey));
            exit();
        }
        $buy_add_model = M('buy_add_record');
        $buy_add_model_rep = M('buy_add_record_rep');
        $buy_model = M('buy_record');
        $M = M();
        $buy_model_rep = M('buy_record_rep');
        $acc_model = M('accounts_change');
        $parent_path_arr = explode(",", $this->parent_path);
        if (count($parent_path_arr) > 1) {
            $zd_userid = $parent_path_arr[1];
        } else {
            $zd_userid = $this->userid;
        }
        //总代理的奖金返点信息
        $bonus_content = M("userBonus")->where(array("userid" => $zd_userid))->getField("bonus_content");
        if ($bonus_content != "") {
            $bonus = (array) json_decode($bonus_content);
            if (!empty($bonus)) {
                foreach ($bonus as $k => $v) {
                    $zd_bonus ["bonus"] [$k] = (array) $v;
                }
            }
        }
        echo json_encode(array( "replaykey" => "", "key" => $this->randkey, "status" => 1, "info" => "购买成功!"));

        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++使用php fastcgi函数开始
        fastcgi_finish_request();


        //购买人的奖金返点信息
        $buyer_bonus = $userFandian;
        //开始事务功能。
        $buy_model->startTrans();
        $buy_data = array(
            'id' => NULL,
            'lottery_id' => $this->lottery_id,
            'lottery_number_id' => $this->lottery_num_id,
            'buy_type_id' => $play_rows['id'],
            'userid' => $this->userid,
            'parent_id' => $this->parent_id,
            'parent_path' => $this->parent_path,
            'monetary' => $this->currentamount,
            'buy_number' => $haoma,
            'zhushu'     => $data_base[2],
            'ip' => $this->ip,
            'buy_time' => $this->date_now,
            'add_number' => count($new_arr),
            'stop_add' => $this->isStop,
            'yuan' => $data_base[5],
            'status' => 0,
            'bonusType' => $jjzfdm,
            "bonusStatus" => $jjstatus,
            "position" => $data_base[7],
            "key" => $this->randkey,
            "youke" => 0
        );
        $auto_buyid = $buy_model->add($buy_data);
        $buy_data["id"] = $auto_buyid;
        $buy_model_rep->add($buy_data);


        import("Class.XDeode");
        $_xDe = new \XDeode(9, 3456.783465231283);
        $buy_series_number = strtoupper($_xDe->encode($auto_buyid));

        $buy_model->where('id=' . $auto_buyid)->save(array('serial_number' => $buy_series_number));
        $buy_model_rep->where('id=' . $auto_buyid)->save(array('serial_number' => $buy_series_number));
        //用户余额要减去购买总金额。
        $balance = $user_acc_rows['cur_account'] - ($this->amount * 100000);

        //插入帐变表一条冻结记录
        $freeze_acc_arr = array(
            'id' => NULL,
            'accounts_type' => 20,
            'buy_record_id' => $auto_buyid,
            'change_amount' => $this->currentamount,
            'userid' => $this->userid,
            'username' => $this->username,
            'parent_id' => $this->parent_id,
            'parent_path' => $this->parent_path,
            'cur_account' => $balance,
            'serial_number' => $buy_series_number,
            'runner_id' => $this->userid,
            'runner_name' => $this->username,
            'change_time' => $this->date_now,
            'lottery_id' => $this->lottery_id,
            'lottery_number_id' => $this->lottery_num_id,
            'buy_type_id' => $play_rows['id'],
            'yuan' => $data_base[5],
            'remark' => '追号冻结'
        );
        $auto_accid = $acc_model->add($freeze_acc_arr);
        //更新该条账变的账变编号
        $achange_num = strtoupper($_xDe->encode($auto_accid));
        $acc_model->where(array("id" => $auto_accid))->save(array("achange_num" => $achange_num));
        $M->execute("UPDATE `user` SET cur_account=(cur_account-$this->currentamount) WHERE id=" . $this->userid);

        // 增加资金变化
        $FoundChangeData = array();
        $FoundChangeData['userid'] = session("SESSION_ID");
        $FoundChangeData['username'] = session("SESSION_NAME");
        $FoundChangeData['parent_id'] = $this->parent_id;
        $FoundChangeData['parent_path'] = $this->parent_path;
        $FoundChangeData['beforeMoney'] = intval($user_acc_rows['cur_account']);
        $FoundChangeData['money'] = intval($this->currentamount);
        $FoundChangeData['afterMoney'] = intval($balance);
        $FoundChangeData['time'] = time();
        $FoundChangeData['remark'] = $this->lotteryName.$buy_series_number."追号冻结金额";
        $this->foundchange->add($FoundChangeData);

        //循环插入追号期号记录
        $i = 0;
        $_total_money = 0;
        foreach ($new_arr AS $key => $val) {
            $_total_money = $_total_money + $val[1];
            if ($i == 0) {
                if ($jjstatus == 2) {

                    if (strpos($wanfa, '不定位') !== false) {
                        $return_point = 0;
                        $zd_return_point = $zd_bonus['bonus'][$this->lotteryid]['bdw_ret_point'];
                    } else {
                        $return_point = 0;
                        $zd_return_point = $zd_bonus['bonus'][$this->lotteryid]['common_ret_point'];
                    }
                } else if ($jjstatus == 1) {
                    if (strpos($wanfa, '不定位') !== false) {

                        $return_point = $buyer_bonus['bonus'][$this->lotteryid]['bdw_ret_point'];
                        $zd_return_point = $zd_bonus['bonus'][$this->lotteryid]['bdw_ret_point'];
                    } else {
                        $return_point = $buyer_bonus['bonus'][$this->lotteryid]['common_ret_point'];
                        $zd_return_point = $zd_bonus['bonus'][$this->lotteryid]['common_ret_point'];
                    }
                }
                //购买获得返点金额
                $return_money = $val[1] * $return_point / 100;
                $total_return_money = $val[1] * $zd_return_point / 100;
            } else {
                //购买获得返点金额
                $return_money = 0;
                $total_return_money = 0;
            }
            $add_data_buy_arr = array(
                'id' => NULL,
                'lottery_id' => $this->lottery_id,
                'buy_id' => $auto_buyid,
                'lottery_number_id' => $key,
                'buy_type_id' => $play_rows['id'],
                'userid' => $this->userid,
                'parent_id' => $this->parent_id,
                'parent_path' => $this->parent_path,
                'monetary' => $val[1],
                'return_money' => $return_money,
                'total_return_money' => $total_return_money,
                'multiple' => $val[0],
                'zhushu'   => $data_base[2],
                'buy_time' => $this->date_now,
                'status' => 0,
                'buy_number' => $haoma,
                'yuan' => $data_base[5],
                'bonusType' => $jjzfdm,
                "bonusStatus" => $jjstatus,
                "position" => $data_base[7],
                "key" => $this->randkey,
                "youke" => 0
            );
            //+++++++++++++++++++++++++++++++++++
            //SeasLog记录用户提交的数据
            //+++++++++++++++++++++++++++++++++++
            \SeasLog::setLogger("GameCathectic");
            $lotteryPlayWay = C("LOTTERY_PLAY_WAY");
            $log = $this->username."追号投注".$this->lotteryName."起始期号:".$this->series_number."期,追号".count($new_arr)."期".array_search($play_rows['id'],$lotteryPlayWay)."号码:\r\n".$haoma."\r\n注数:".$data_base[2]."倍数:".$val[0]."模式:".$data_base[5]."奖金返点:".$jjzfdm."本期金额".sprintf("%.4f",$val[1]/100000)."总金额:".sprintf("%.4f",$this->currentamount/100000);


            $auto_add_buyid = $buy_add_model->add($add_data_buy_arr);
            $add_data_buy_arr["id"] = $auto_add_buyid;
            $buy_add_model_rep->add($add_data_buy_arr);

            $buyadd_series_number = strtoupper($_xDe->encode($auto_add_buyid));

            $buy_add_model->where('id=' . $auto_add_buyid)->save(array('add_serial_number' => $buyadd_series_number));
            $buy_add_model_rep->where('id=' . $auto_add_buyid)->save(array('add_serial_number' => $buyadd_series_number));

            if ($return_money > 0) {
                $M->execute("UPDATE `user` SET cur_account=(cur_account+$return_money) WHERE id=" . $this->userid);
                // 增加资金变化
                $FoundChangeData = array();
                $FoundChangeData['userid'] = session("SESSION_ID");
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = $this->parent_id;
                $FoundChangeData['parent_path'] = $this->parent_path;
                $FoundChangeData['beforeMoney'] = intval($balance);
                $FoundChangeData['money'] = intval($return_money);
                $FoundChangeData['afterMoney'] = intval(($balance+$return_money));
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = $this->lotteryName.$buy_series_number."追号返点金额";
                $this->foundchange->add($FoundChangeData);
            }

            if ($i == 0) {
                if ($return_money != 0) {
                    //解冻的金额
                    $_jiedong = $balance + $val[1];
                    //购买的金额
                    $_goumai = $_jiedong - $val[1];
                    //获得返点的时候金额
                    $_fandian = $_goumai + $return_money;
                } else {
                    //解冻的金额
                    $_jiedong = $balance + $val[1];
                    //购买的金额
                    $_goumai = $_jiedong - $val[1];
                }
                //解冻的账变
                $add_acc_fir_arr = array(
                    'id' => NULL,
                    'accounts_type' => 22,
                    'buy_record_id' => $auto_add_buyid,
                    'change_amount' => $val[1],
                    'userid' => $this->userid,
                    'username' => $this->username,
                    'parent_id' => $this->parent_id,
                    'parent_path' => $this->parent_path,
                    'cur_account' => $_jiedong,
                    'serial_number' => $buy_series_number,
                    'runner_id' => $this->userid,
                    'runner_name' => $this->username,
                    'change_time' => $this->date_now,
                    'is_addto' => 1,
                    'lottery_id' => $this->lottery_id,
                    'lottery_number_id' => $key,
                    'buy_type_id' => $play_rows['id'],
                    'yuan' => $data_base[5],
                    'remark' => '追号解冻'
                );
                $auto_acc_first = $acc_model->add($add_acc_fir_arr);
                //更新该条账变的账变编号
                $achange_num_first = strtoupper($_xDe->encode($auto_acc_first));
                $acc_model->where(array("id" => $auto_acc_first))->save(array("achange_num" => $achange_num_first));
                unset($add_acc_fir_arr);
                $add_acc_fir_arr = array(
                    'id' => NULL,
                    'accounts_type' => 21,
                    'buy_record_id' => $auto_add_buyid,
                    'change_amount' => $val[1],
                    'userid' => $this->userid,
                    'username' => $this->username,
                    'parent_id' => $this->parent_id,
                    'parent_path' => $this->parent_path,
                    'cur_account' => $_goumai,
                    'serial_number' => $buy_series_number,
                    'runner_id' => $this->userid,
                    'runner_name' => $this->username,
                    'change_time' => $this->date_now,
                    'is_addto' => 1,
                    'lottery_id' => $this->lottery_id,
                    'lottery_number_id' => $key,
                    'buy_type_id' => $play_rows['id'],
                    'yuan' => $data_base[5],
                    'remark' => '追号投注'
                );
                $auto_acc_second = $acc_model->add($add_acc_fir_arr);
                //更新该条账变的账变编号
                $achange_num_second = strtoupper($_xDe->encode($auto_acc_second));
                $acc_model->where(array("id" => $auto_acc_second))->save(array("achange_num" => $achange_num_second));
                unset($add_acc_fir_arr);
                if ($return_money != 0) {
                    $add_acc_fir_arr = array(
                        'id' => NULL,
                        'accounts_type' => 4,
                        'buy_record_id' => $auto_add_buyid,
                        'change_amount' => $return_money,
                        'userid' => $this->userid,
                        'username' => $this->username,
                        'parent_id' => $this->parent_id,
                        'parent_path' => $this->parent_path,
                        'cur_account' => $_fandian,
                        'serial_number' => $buy_series_number,
                        'runner_id' => $this->userid,
                        'runner_name' => $this->username,
                        'change_time' => $this->date_now,
                        'is_addto' => 1,
                        'lottery_id' => $this->lottery_id,
                        'lottery_number_id' => $key,
                        'buy_type_id' => $play_rows['id'],
                        'yuan' => $data_base[5],
                        'remark' => '追号返点'
                    );
                    $auto_acc_three = $acc_model->add($add_acc_fir_arr);
                    //更新该条账变的账变编号
                    $achange_num_three = strtoupper($_xDe->encode($auto_acc_three));
                    $acc_model->where(array("id" => $auto_acc_three))->save(array("achange_num" => $achange_num_three));
                }
            }
            if ($auto_add_buyid) {
                $buy_model->commit();

                //将购买的数据放入mongDB中
                $result = array();
                $result["buy_id"] = intval($auto_buyid);
                $result["userid"] = $this->userid;
                $result["parent_path"] = $this->parent_path;
                $result["parent_id"] = $this->parent_id;
                $result["buy_add_id"] = $auto_add_buyid;
                $result["lottery_number_id"] = intval($key);
                $result["lottery_id"] = intval($this->lottery_id);
                $result["buy_type_id"] = $play_rows['id'];
                $result["mul"] = $val[0];
                $result["buy_money"] = $val[1];
                $result["buy_time"] = $this->date_now;
                $result["return_money"] = $return_money;
                $result["buy_number"] = $haoma;
                $result["stop_add"] = $this->isStop;
                $result["yuan"] = $data_base[5];
                $result["serial_number"] = $buyadd_series_number;
                $result["bonusType"] = $jjzfdm;
                $result["bonusStatus"] = $jjstatus;
                $result["position"] = $data_base[7];
                $result["plan"] = 0;
                $this->orderToMongo->add($result);

                \SeasLog::info($log."[投注成功]\r\n\r\n");
            } else {
                $buy_model->rollback();
                \SeasLog::info($log."[投注失败]\r\n\r\n");
                exit();
            }
            $i++;
        }
    }

}

?>
