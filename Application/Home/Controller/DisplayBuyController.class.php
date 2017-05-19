<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/12
 * Time: 下午11:13
 */

namespace Home\Controller;

use \Home\Model\OrderToMongoWfsscModel;
use \Home\Model\OrderToMongoEfsscModel;
use \Home\Model\OrderToMongoYfsscModel;

class DisplayBuyController extends CommonController
{
    public function index () {

        //标准页面模板参数，不符合的get方式访问被禁止。
        $template_array=array('Cqssc','Jxssc','Xjssc','Tjssc','Jsxw','Xlssc','Jxxw','Gdxw','Sdxw','Gdklsf','Cqklsf','3d','p3','Bjk8','Ssq','Jsk3','Jlk3','Xy5ssc','Xy2ssc','Xy1ssc','Pk10','Hgssc','lucky28','Sxxw','Sxxxw','Sxklsf','Sxxklsf','Hglucky28','Jndlucky28','Bj5f','Jnd3f','Txssc','Qqssc','Twssc','Twk8','lf');
        $template = array('Ssc', '11x5', 'Klsf', 'Welfare', 'K8', 'Ssq', 'K3','Bjpk10','Lucky','LF');
        //获取模板
        $template_para=I("get.tem"."","strval");
        $template_tara = I("get.t"."","strval");

        if(in_array($template_para,$template_array)==false || in_array($template_tara, $template)==false){

            $this->error("页面异常！");

        }
        $this->menu = "lottery";
        $this->display("{$template_tara}:{$template_para}");

    }

    // 设置声音
    public function setSound()
    {
        $type = I("post.type","");
        $status = I('post.status',"");
        $key = "sound".session('SESSION_NAME').$type;
        session($key,$status);
        echo "ok";
    }

    public function timeDown()
    {
        $lotteryId = I("post.lid","");
        $lottery_id = remove_xss($lotteryId);
        if(!IS_AJAX || !check_lottery($lottery_id)) exit();

        $nowTime = time();

        $lotteryNumberMemory = M("lottery_number_memory");
        $lotteryNumber = M("lottery_number");
        $_current = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("elt", $nowTime), "endtime" => array("gt", $nowTime)))->find();
        if(empty($_current)) {
            //判断lottery_number_memory中是否存在该彩票的数据，如果存在，就删除
            $_exist = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("gt", $nowTime)))->find();
            if(!empty($_exist)) 
            {
                $_current = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("egt", $nowTime)))->order("id ASC")->find();
            } 
            else 
            {
                $lotteryNumberMemory->where(array("lottery_id" => $lottery_id))->delete();
                $_exist_lottery_number_1 = $lotteryNumber->where(array("lottery_id" => $lottery_id, "starttime" => array("egt", $nowTime)))->find();
                $_exist_lottery_number_2 = $lotteryNumber->where(array("lottery_id" => $lottery_id, "starttime" => array("elt", $nowTime), "endtime" => array("gt", $nowTime)))->find();
                if(!empty($_exist_lottery_number_1)) 
                {
                    $_query = "INSERT INTO lottery_number_memory (`id`,`lottery_id`, `series_number`, `starttime`, `endtime`) SELECT `id`,`lottery_id`, `series_number`, `starttime`, `endtime` FROM lottery_number WHERE lottery_id=".$lottery_id;
                    M()->execute($_query);
                    $_current = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("elt", $nowTime), "endtime" => array("gt", $nowTime)))->find();
                    if(empty($_current)) {
                        $_current = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("gt", $nowTime)))->order("id ASC")->find();
                    }
                } 
                else if(!empty($_exist_lottery_number_2)) 
                {
                    $_query = "INSERT INTO lottery_number_memory (`id`,`lottery_id`, `series_number`, `starttime`, `endtime`) SELECT `id`,`lottery_id`, `series_number`, `starttime`, `endtime` FROM lottery_number WHERE lottery_id=".$lottery_id;
                    M()->execute($_query);
                    $_current = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("elt", $nowTime), "endtime" => array("gt", $nowTime)))->find();
                    if(empty($_current)) {
                        $_current = $lotteryNumberMemory->where(array("lottery_id" => $lottery_id, "starttime" => array("gt", $nowTime)))->order("id ASC")->find();
                    }
                }
            }
        }
        $issueId = $_current['id'];
        $endtime = $_current['endtime'];
        $series_number = $_current['series_number'];
        $timestamp = $_current['endtime']-$nowTime;

        $month = date('m',$nowTime);$day = date('d',$nowTime);$year = date('Y',$nowTime);
        $timestamp = ($timestamp < 0) ? (mktime(10,0,0,$month,$day,$year)-$nowTime) : $timestamp;
        //获取上一期的期号
        $lotteryNumberMidModel = M("lottery_number_mid");
        $recentBet = $lotteryNumberMidModel->where(array("lottery_id"=>$lottery_id,"id"=>array("lt",$issueId),"lottery_number" => array("neq",'0')))->order("id DESC")->limit(7)->field("series_number,lottery_number")->select();
        if(!empty($recentBet))
        {
            $last_series_number = $recentBet[0]['series_number'];
            $last_number = $recentBet[0]['lottery_number'];
        }
        $title = C("LOTTERY")[$lotteryId]['name'];

        //获取用户的奖金级别
        if(in_array($lotteryId,array(1,2,3,4,17,18,19,22,30,31,32,33,34)))
            $lid = 1;
        else if(in_array($lotteryId,array(5,6,7,8,24,25)))
            $lid = 5;
        else if(in_array($lotteryId,array(9,10,26,27)))
            $lid = 9;
        else if(in_array($lotteryId,array(11,12)))
            $lid = 11;
        else if(in_array($lotteryId,array(15,16)))
            $lid = 15;
        else if(in_array($lotteryId,array(13,35)))
            $lid = 13;
        else if(in_array($lotteryId,array(23,28,29)))
            $lid = 23;
        else
            $lid = $lotteryId;
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $key = md5("userbonus".session("SESSION_ID"));
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
        //todo 如果没有高低奖的时候要进行判断
        $rewards = C("REWARDS");
        $rewardsArray = json_decode($rewards["{$bonusLevel}"]);
        foreach((array)$rewardsArray as $l=>$val)
        {
            if($l==$lid)
            {
                $common_ret_point = $val->common_ret_point;
                $bdw_ret_point = $val->bdw_ret_point;
            }
        }
        //todo 如果为其他的彩票的时候要选择另外的奖金列表
        if($lid==1)
        {
            $changeBonus = C("changeBonus");
            $commonBonus = C("ssc_bonus")[70];
            $commonBdwBonus = C("ssc_bonus_bdw")[2];
        }
        else if($lid==5)
        {
            $changeBonus = C("xwchangeBonus");
            $commonBonus = C("xw_bonus")[70];
            $commonBdwBonus = C("xw_bonus_bdw")[2];
        }
        else if($lid==9)
        {
            $changeBonus = C("klsfchangeBonus");
            $commonBonus = C("shf_bonus")[70];
            $commonBdwBonus = null;
        }
        else if($lid==13)
        {
            $changeBonus = C("changeBonusConfig_k8");
            $commonBonus = C("k8_bonus")[80];
            $commonBdwBonus = null;
        }
        else if($lid==15)
        {
            $changeBonus = C("changeBonusConfig_k3");
            $commonBonus = C("k3_bonus")[80];
            $commonBdwBonus = null;
        }
        else if($lid==11)
        {
            $changeBonus = C("p3dchangeBonus");
            $commonBonus = C("psds_bonus")[70];
            $commonBdwBonus = C("psds_bonus_bdw")[2];
        }
        else if($lid==23)
        {
            $changeBonus = null;
            $commonBonus = C("xy28_bonus")[70];
            $commonBdwBonus = null;
        }
        else if($lid==21)
        {
            $changeBonus = C("changeBonusPk10");
            $commonBonus = C("pk10_bonus")[70];
            $commonBdwBonus = null;
        }

        $bonus = array();
        if($lid!=13 && $lid!=15 && $lid!=23)
        {
            foreach($changeBonus as $key=>$item)
            {
                if((strpos($key,"不定位")!=false))
                {
                    if(!isset($bonus['change'][$key]))
                        $bonus['change'][$key] = $item["{$bdw_ret_point}"];
                    if(!isset($bonus['common'][$key]))
                        $bonus['common'][$key] = $commonBdwBonus["{$key}"];
                }
                else
                {
                    if(!isset($bonus['change'][$key]))
                        $bonus['change'][$key] = $item["{$common_ret_point}"];
                    if(!isset($bonus['common'][$key]))
                        $bonus['common'][$key] = $commonBonus["{$key}"];
                }
            }
        }
        else if($lid==13)
        {
            foreach($commonBonus as $key=>$item)
            {
                if(is_array($item))
                {
                    $str="";
                    foreach($item as $k=>$value)
                    {
                        $str .= $k.":".$value."/";
                    }
                    $str = substr($str,0,strlen($str)-1);
                    $bonus['change'][$key] = $str;
                    $bonus['common'][$key] = $str;
                }
                else
                {
                    $bonus['change'][$key] = $key.":".$item;
                    $bonus['common'][$key] = $key.":".$item;
                }
            }
        }
        else if($lid==15)
        {
            foreach($commonBonus as $key=>$item)
            {
                if(is_array($item))
                {
                    foreach($item as $k=>$value)
                    {
                        if($key!="和值" && $key!="不同号")
                        {
                            $bonus['change'][$key.$k] = $value;
                            $bonus['common'][$key.$k] = $value;
                        }
                        else
                        {
                            $bonus['change'][$k] = $value;
                            $bonus['common'][$k] = $value;
                        }
                    }
                }
                else
                {
                    $bonus['change'][$key] = $item;
                    $bonus['common'][$key] = $item;
                }
            }
        }
        else if($lid==23)
        {
            foreach($commonBonus as $key=>$item)
            {
                $bonus['change'][$key] = $key.":".$item;
                $bonus['common'][$key] = $key.":".$item;
            }
        }
        echo json_encode(array("lid"=>$lotteryId, "bonuslevel"=>$bonusLevel,"bdw_ret_point"=>$bdw_ret_point, "bonus"=>$bonus, "id" => $issueId,"recentBit"=>$recentBet,"title"=>$title, "series_number" => $series_number, "last_series_number"=>$last_series_number, "last_number"=>$last_number, "timestamp" => $timestamp, "endtime" => date("Y-m-d H:i:s", $endtime),"servertime" => date("Y-m-d H:i:s",time())));

        fastcgi_finish_request();
        if(in_array($lotteryId, array(17,18,19)))
        {
            $result = array();
            $result['lottery_id'] = intval($lotteryId);
            $result['lottery_number_id'] = intval($issueId);
            switch($lotteryId)
            {
                case 17:
                    $LotteryName = "金手指五分彩";
                    $pTemplate = "金手指五分彩计划";
                    $Expect = $series_number;
                    $ViewName = "金手指五分彩";
                    $orderToMongo = new OrderToMongoWfsscModel();
                    break;
                case 18:
                    $LotteryName = "金手指两分彩";
                    $pTemplate = "金手指两分彩计划";
                    $Expect = $series_number;
                    $ViewName = "金手指两分彩";
                    $orderToMongo = new OrderToMongoEfsscModel();
                    break;
                case 19:
                    $LotteryName = "金手指分分彩";
                    $pTemplate = "金手指分分彩计划";
                    $Expect = $series_number;
                    $ViewName = "金手指分分彩";
                    $orderToMongo = new OrderToMongoYfsscModel();
                    break;

            }

            $options = array("where"=>array('lottery_id'=>intval($lotteryId),'lottery_number_id'=>intval($issueId)));
            $res = $orderToMongo->find($options);
            if(empty($res))
            {
                $number = self::createNumber($LotteryName, $pTemplate, $Expect, $ViewName);
                $result['number'] = $number;
                $orderToMongo->add($result);
            }
        }
    }

    /*todo 最大遗漏*/
    public function yilou($list)
    {
        $DataTotalCount = array();  //出选总的次数

        $DataTmpLost = array();  //当前累计遗漏

        $DataSumLost = array();  //总的遗漏

        $DataMaxLost = array();  //最大遗漏

        $list = array_reverse($list);
        $xReturn = array();
        for($k=0; $k<5; $k++)
        {
            foreach($list as $item)
            {
                $code = $item["lottery_number"];
                $tmpCode = array($code[$k]);
                foreach($tmpCode as $i=>$val)
                {
                    if($DataTotalCount[$i] == null) {
                        $DataTotalCount[$i] = array();
                    }
                    if($DataTmpLost[$i] == null) {
                        $DataTmpLost[$i] = array();
                    }
                    if($DataSumLost[$i] == null) {
                        $DataSumLost[$i] = array();
                    }
                    if($DataMaxLost[$i] == null) {
                        $DataMaxLost[$i] = array();
                    }

                    for($j=0; $j<10; $j++)
                    {
                        if($DataTotalCount[$i][$j] == null) {
                            $DataTotalCount[$i][$j] = 0;
                        }
                        if($DataTmpLost[$i][$j] == null) {
                            $DataTmpLost[$i][$j] = 0;
                        }
                        if($DataSumLost[$i][$j] == null) {
                            $DataSumLost[$i][$j] = 0;
                        }
                        if($DataMaxLost[$i][$j] == null) {
                            $DataMaxLost[$i][$j] = 0;
                        }
                        if($val==$j)
                        {
                            $DataTotalCount[$i][$j] += 1;
                            $DataTmpLost[$i][$j] = 0;
                        }
                        else
                        {
                            $DataTmpLost[$i][$j] += 1;
                            $DataSumLost[$i][$j] += $DataTmpLost[$i][$j];
                            if($DataTmpLost[$i][$j] > $DataMaxLost[$i][$j]) {
                                $DataMaxLost[$i][$j] = $DataTmpLost[$i][$j];
                            }
                        }
                    }
                }
            }
            $xReturn[$k] = array("big"=>$DataMaxLost[0],"now"=>$DataTmpLost[0]);
        }
        return $xReturn;
    }

    /*todo 冷热温号*/
    public static function CoolAndHot($lid,$lotteryNumberId)
    {
        $LotteryNumber = M("lottery_number");
        $res = $LotteryNumber->where("lottery_id=".$lid." and id<".$lotteryNumberId." and lottery_number!=0")->limit(7)->order("id desc")->select();
        $xRet = array();
        foreach($res as $k=>$it)
        {
            $number = $it['lottery_number'];
            $narr = array($number[0],$number[1],$number[2],$number[3],$number[4]);
            $auni = array_unique($narr);
            $number = implode("",$auni);
            if(strpos($number,'0')!==false)
                (isset($xRet[0])) ? $xRet[0] = $xRet[0] +1 : $xRet[0] = 1;
            else
                if(!isset($xRet[0])) $xRet[0] = 0;
            if(strpos($number,'1')!==false)
                (isset($xRet[1])) ? $xRet[1] = $xRet[1] +1 : $xRet[1] = 1;
            else
                if(!isset($xRet[1])) $xRet[1] = 0;
            if(strpos($number,'2')!==false)
                (isset($xRet[2])) ? $xRet[2] = $xRet[2] +1 : $xRet[2] = 1;
            else
                if(!isset($xRet[2])) $xRet[2] = 0;
            if(strpos($number,'3')!==false)
                (isset($xRet[3])) ? $xRet[3] = $xRet[3] +1 : $xRet[3] = 1;
            else
                if(!isset($xRet[3])) $xRet[3] = 0;
            if(strpos($number,'4')!==false)
                (isset($xRet[4])) ? $xRet[4] = $xRet[4] +1 : $xRet[4] = 1;
            else
                if(!isset($xRet[4])) $xRet[4] = 0;
            if(strpos($number,'5')!==false)
                (isset($xRet[5])) ? $xRet[5] = $xRet[5] +1 : $xRet[5] = 1;
            else
                if(!isset($xRet[5])) $xRet[5] = 0;
            if(strpos($number,'6')!==false)
                (isset($xRet[6])) ? $xRet[6] = $xRet[6] +1 : $xRet[6] = 1;
            else
                if(!isset($xRet[6])) $xRet[6] = 0;
            if(strpos($number,'7')!==false)
                (isset($xRet[7])) ? $xRet[7] = $xRet[7] +1 : $xRet[7] = 1;
            else
                if(!isset($xRet[7])) $xRet[7] = 0;
            if(strpos($number,'8')!==false)
                (isset($xRet[8])) ? $xRet[8] = $xRet[8] +1 : $xRet[8] = 1;
            else
                if(!isset($xRet[8])) $xRet[8] = 0;
            if(strpos($number,'9')!==false)
                (isset($xRet[9])) ? $xRet[9] = $xRet[9] +1 : $xRet[9] = 1;
            else
                if(!isset($xRet[9])) $xRet[9] = 0;
        }
        $cool = $wen = $hot = array();
        foreach($xRet as $key=>$value)
        {
            if(intval($value)<2)
            {
                array_push($cool,$key);
            }
            else if(intval($value)==2)
            {
                array_push($wen,$key);
            }
            else if(intval($value)>2)
            {
                array_push($hot,$key);
            }
        }
        return json_encode(array("cool"=>$cool,"wen"=>$wen,"hot"=>$hot));
    }

    public function futureLottery()
    {
        $lotteryId = I("post.id","");
        if(!check_lottery($lotteryId)) exit();
        $ten_timestamp = mktime(22, 0, 0, date('m'), date('d'), date('Y'));
        $zero_timestamp = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        $zero_timestamp1 = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $four_timestamp = mktime(4, 0, 0, date('m'), date('d'), date('Y'));
        $nowtime = time();
        $terminal = mktime(0, 0, 20, date("m"), date("d") + 1, date("Y"));

        if ($nowtime >= $ten_timestamp AND $nowtime <= $zero_timestamp) {
            $time_sql = "endtime> {$nowtime} AND endtime< {$zero_timestamp}";
        }if ($nowtime >= $zero_timestamp1 AND $nowtime <= $four_timestamp) {
            $time_sql = "endtime> {$nowtime} AND endtime< {$four_timestamp}";
        } else {
            $time_sql = "endtime > {$nowtime}  AND endtime < {$terminal}";
        }

        $lotteryNumberMid = M("lottery_number_mid");
        $result = $lotteryNumberMid->where("lottery_id=".$lotteryId." and ".$time_sql)->order('endtime asc')->select();
        $count = count($result);
        $issuearr = array();
        foreach ($result as $row) {
            $issuearr[] = array('id'=>$row['id'],'time' => date('Y-m-d H:i:s', $row['endtime']), 'issue' => $row['series_number'],'count'=>$count);
        }
        echo json_encode($issuearr);
    }

    public static function createNumber($LotteryName, $pTemplate, $Expect, $ViewName)
    {
        $DataBaseRes = array();
        for($i=1; $i<=80; $i++)
        {
            array_push($DataBaseRes, $i);
        }
        $xReturn = array_values($DataBaseRes);
        $numberArray = array();
        for($k=1; $k<=100; $k++)
        {
            $result = self::GetRandomNum($LotteryName,$xReturn,$pTemplate,"公式{$k}",$Expect,$ViewName,20);
            $number20 = $result;
            //组合开奖号码
            $a1 = intval(str_replace('/^0*/', "",$number20[0]))+intval(str_replace('/^0*/', "",$number20[1]))+intval(str_replace('/^0*/', "",$number20[2]))+intval(str_replace('/^0*/', "",$number20[3]));
            $a1_str = $a1%10;

            $a2 = intval(str_replace('/^0*/', "",$number20[4]))+intval(str_replace('/^0*/', "",$number20[5]))+intval(str_replace('/^0*/', "",$number20[6]))+intval(str_replace('/^0*/', "",$number20[7]));
            $a2_str = $a2%10;

            $a3 = intval(str_replace('/^0*/', "",$number20[8]))+intval(str_replace('/^0*/', "",$number20[9]))+intval(str_replace('/^0*/', "",$number20[10]))+intval(str_replace('/^0*/', "",$number20[11]));
            $a3_str = $a3%10;

            $a4 = intval(str_replace('/^0*/', "",$number20[12]))+intval(str_replace('/^0*/', "",$number20[13]))+intval(str_replace('/^0*/', "",$number20[14]))+intval(str_replace('/^0*/', "",$number20[15]));
            $a4_str = $a4%10;

            $a5 = intval(str_replace('/^0*/', "",$number20[16]))+intval(str_replace('/^0*/', "",$number20[17]))+intval(str_replace('/^0*/', "",$number20[18]))+intval(str_replace('/^0*/', "",$number20[19]));
            $a5_str = $a5%10;
            $number = $a1_str.$a2_str.$a3_str.$a4_str.$a5_str;
            array_push($numberArray,$number);
        }
        return json_encode($numberArray);
    }

    /**
    * 根据计划名称和期号和位置获得计划号码个数索引
    *
    * @param $lotteryName <彩种名称>
    * @param $pTemplate <计划名称>
    * @param $pFormula <公式名称>
    * @param $pExpect <期号>
    * @param $playViewName <计划别名>
    * @param $pNumber <计划号码个数>
    * @return int
    */
    public static function GetNumberIndex($lotteryName, $pTemplate, $pFormula, $pExpect, $playViewName, $pNumber)
    {
        if ($pNumber == 1) return 0;
        $sources =  $lotteryName . $pTemplate . $pFormula . $pExpect . $playViewName;
        $value = self::SelfMD5($sources);
        if(ord($value[0])!=0)
            $xReturn = ord($value[0]) % $pNumber;
        else if(ord($value[0])==0)
            $xReturn = ord($value[0]) % 9;
        return $xReturn;
    }

    /**
     * 根据计划名称和期号和位置获得随机数<获取单式的随机号码>
     *
     * @param $lotteryName <彩票名称>
     * @param $pTemplate <计划名称>
     * @param $pFormula <公式名称>
     * @param $pExpect <期号>
     * @param $playName <计划别名>
     * @param $pNumber <基础数组循环之后的数组个数>
     * @return int
     */
    public static function GetRandomNumRand($lotteryName, $pTemplate, $pFormula, $pExpect, $playName, $pNumber)
    {
        $sources = $lotteryName . $pFormula . $pTemplate . $pExpect . $playName .$pNumber;
        $value = self::SelfMD5($sources);
        $outString = "";
        for ($i = 0; $i < strlen($pNumber); $i++)
        {
            $outValue = ord($value[$i]) % 10;

            $outString .= $outValue;
        }
        $outValue = intval($outString) % $pNumber;
        return $outValue;
    }

    /**
     * 获得指定数组内的随机数
     *
     * @param $numberList <基础数组>
     * @param $pTemplate <计划名称>
     * @param $pFormula <公式名称>
     * @param $pExpect <期号>
     * @param $playViewName <计划别名>
     * @param $pNumber <计划号码个数>
     * @return array
     */
    public static function GetRandomNum($lotteryName,$numberList, $pTemplate, $pFormula, $pExpect, $playViewName, $pNumber)
    {
        $xReturn = array();
        $TempList = $numberList;
        while (count($xReturn) < $pNumber)
        {
            $index = self::GetRandomNumRand($lotteryName,$pTemplate, $pFormula, $pExpect, $playViewName, count($TempList));
            array_push($xReturn,$TempList[$index]);
            array_splice($TempList, $index, 1);
        }
        return $xReturn;
    }

    /**
     * md5加密函数
     *
     * @param $sources
     * @return string
     */
    public static function SelfMD5($sources)
    {
        $Sources = $sources."CSCRGJH";
        return strtoupper(hash('md5', $Sources));
    }

    /*todo 获取遗漏冷热数据*/
    public function getMissCold()
    {
        if(!IS_AJAX) exit();
        //统计最大遗漏
        $lotteryId = I("post.lid");
        $type = I("post.type");
        $nowTime = time();
        $lotteryNumberMemory = M("lottery_number_memory");
        $_current = $lotteryNumberMemory->where(array("lottery_id" => $lotteryId, "starttime" => array("elt", $nowTime), "endtime" => array("gt", $nowTime)))->find();
        if(empty($_current)) {
            $_current = $lotteryNumberMemory->where(array("lottery_id" => $lotteryId, "starttime" => array("gt", $nowTime)))->order("id ASC")->find();
        }
        $issueId = $_current['id'];
        $Skey = "MISS_".$lotteryId.$issueId;
        if(!empty(session("$Skey")))
        {
           echo session("$Skey");
        }
        else
        {
            $lotteryNumberSwoop = M("lottery_number_swoop");
            $list = $lotteryNumberSwoop->field("lottery_number")->where("lottery_id=".$lotteryId)->limit(300)->order("id desc")->select();
            $yilou = $this->yilou($list);
            $cool = $this->CoolAndHot($lotteryId,$issueId);
            if($type=="wxfs" || $type=="dwd" || $type=="rx2fs" || $type=="rx3fs" || $type=="rx4fs")
            {
                $temp = $yilou;
            }
            else if($type=="q4fs")
            {
                array_pop($yilou);
                $temp = $yilou;
            }
            else if($type=="h4fs")
            {
                array_shift($yilou);
                $temp = $yilou;
            }
            else if($type=="q3fs")
            {
                array_pop($yilou);
                array_pop($yilou);
                $temp = $yilou;
            }
            else if($type=="q3z3" || $type=="q3z6" || $type=="bdwq31")
            {
                array_pop($yilou);
                array_pop($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="z3fs")
            {
                array_shift($yilou);
                array_pop($yilou);
                $temp = $yilou;
            }
            else if($type=="z3z3" || $type=="z3z6")
            {
                array_shift($yilou);
                array_pop($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="h3fs")
            {
                array_shift($yilou);
                array_shift($yilou);
                $temp = $yilou;
            }
            else if($type=="h3z3" || $type=="h3z6" || $type=='bdwh31')
            {
                array_shift($yilou);
                array_shift($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="q2fs")
            {
                array_pop($yilou);
                array_pop($yilou);
                array_pop($yilou);
                $temp = $yilou;
            }
            else if($type=="q2zxfs")
            {
                array_pop($yilou);
                array_pop($yilou);
                array_pop($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="h2fs")
            {
                array_shift($yilou);
                array_shift($yilou);
                array_shift($yilou);
                $temp = $yilou;
            }
            else if($type=="h2zxfs")
            {
                array_shift($yilou);
                array_shift($yilou);
                array_shift($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="bdwq41")
            {
                array_pop($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="bdwh41")
            {
                array_shift($yilou);
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            else if($type=="bdw5x1m")
            {
                $t0=$t1=$t2=$t3=$t4=$t5=$t6=$t7=$t8=$t9=array();
                $b0=$b1=$b2=$b3=$b4=$b5=$b6=$b7=$b8=$b9=array();
                foreach($yilou as $k=>$v)
                {
                    foreach($v['big'] as $kk=>$vv)
                    {
                        $n = "t".$kk;
                        array_push($$n,$vv);
                    }
                    foreach($v['now'] as $kk=>$vv)
                    {
                        $n = "b".$kk;
                        array_push($$n,$vv);
                    }
                }
                $big = array();
                $now = array();
                for($i=0; $i<10; $i++)
                {
                    $t = "t".$i;
                    $big[] = $$t;
                    $bi = "b".$i;
                    $now[] = $$bi;
                }
                $bigtemp = array();
                $nowtemp = array();
                foreach($big as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($bigtemp,$value[$minpos]);
                }
                foreach($now as $key=>$value)
                {
                    $minpos = array_search(min($value), $value);
                    array_push($nowtemp,$value[$minpos]);
                }
                $temp[] = array("big"=>$bigtemp,"now"=>$nowtemp);
            }
            $jsonString = json_encode(array(
                "yilou" => json_encode($temp),
                "cool" => $cool
            ));
            session("$Skey",$jsonString);
            echo $jsonString;
        }
    }

    public function buyRecordItem()
    {
        $uid = session("SESSION_ID");
        $lid = I("post.lid");

        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }

        $list = M("buy_record")->where(array("userid"=>$uid,"lottery_id"=>$lid, "add_number"=>0, "buy_time"=>array(array('egt',$accordStartTime),array('lt',$accordEndTime),'and')))->order("id desc")->limit(10)->select();
        $result = array();
        $lottery = C("LOTTERY");
        $lotteryNumberMid = M("lottery_number_mid");
        $lotteryPlayWay = M("lottery_play_way");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        foreach($list as $item)
        {
            $temp = array();
            $temp['id'] = $_xDe->encode($item['id']);
            $lotteryName = $lottery[$item['lottery_id']]['name'];
            $lottery_serial_number = $lotteryNumberMid->where(array("id"=>$item['lottery_number_id']))->getField("series_number");
            $playway = $lotteryPlayWay->where(array("id"=>$item["buy_type_id"]))->getField("name");
            $temp['lotteryName'] = $lotteryName;
            $temp['playway'] = $playway;
            $temp['serial'] = $lottery_serial_number;
            if($item['buy_type_id']==51)
            {
                $arr = array(0=>"0单5双",1=>"1单4双",2=>"2单3双",3=>"3单2双",4=>"4单1双",5=>"5单0双");
                $item['buy_number'] = rtrim($item['buy_number']);
                $buynumber = explode(" ",$item['buy_number']);
                $str = "";
                foreach($buynumber as $n)
                {
                    $str .= $arr[$n]." ";
                }
                $temp['buy_number'] = $str;
            }
            else
            {
                $temp['buy_number'] = $item['buy_number'];
            }
            switch($item['yuan'])
            {
                case 0:
                    $temp['mode'] = "元";break;
                case 1:
                    $temp['mode'] = "角";break;
                case 2:
                    $temp['mode'] = "分";break;
                case 3:
                    $temp['mode'] = "厘";break;
            }
            $temp['multiple'] = $item['multiple'];
            $temp['monetary'] = sprintf("%.4f",$item['monetary']/100000);
            $temp['bonus'] = sprintf("%.4f",$item['bonus']/100000);
            $endtime = $lotteryNumberMid->where(array("id"=>$item['lottery_number_id']))->getField("endtime");
            if($endtime>time() && $item['status']==0)
                $temp['operate'] = '<a href="javascript:;" style="display:inline;padding:0" data-field="'.$temp['id'].'" data-method="detail">详情</a><a href="javascript:;" style="display:inline;padding-left:10px" data-field="'.$temp['id'].'" data-method="cancel"">撤销</a>';
            else
                $temp['operate'] = '<a href="javascript:;" style="display:inline;padding:0" data-field="'.$temp['id'].'" data-method="detail">详情</a>';
            switch($item['status'])
            {
                case 0:
                    $temp['status'] = "未开奖";break;
                case 1:
                    if($item["bonus"]>0)
                        $temp['status'] = '<span style="color: green">已中奖</span>';
                    else
                        $temp['status'] = '<span style="color: #a02f2b">未中奖</span>';
                    break;
                case 2:
                    $temp['status'] = '<span style="color: red">已撤单</span>';
                    break;
            }
            $result[] =$temp;
        }
        echo json_encode($result);
    }

    public function buyAddRecordItem()
    {
        $lid = I("post.lid");

        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }

        $list = M("buy_record")->where("userid=".session("SESSION_ID")." and add_number>0 and lottery_id=".$lid." and buy_time>=".$accordStartTime." and buy_time<".$accordEndTime)->field("id,serial_number,userid,lottery_id,lottery_number_id,add_number,monetary,buy_time,bonus,status")->limit(10)->order("id desc")->select();
        $result = array();
        $User = M("user");
        $lotteryNumberMid = M("lottery_number_mid");
        $lottery = C("LOTTERY");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        foreach($list as $item)
        {
            $temp= array();
            $temp['id'] = $_xDe->encode($item['id']);
            $temp['userid'] = $item["userid"];
            $temp['add_number'] = $item['add_number'];
            $temp['lottery_number_id'] = $item['lottery_number_id'];
            $temp['serial_number'] = $item['serial_number'];
            $temp['lottery_name'] = $lottery[$item['lottery_id']]['name'];
            $temp['username'] = $User->where(array("id"=>$item['userid']))->getField("username");
            $temp['lottery_serial_number'] = $lotteryNumberMid->where(array("id"=>$item['lottery_number_id']))->getField("series_number");
            
            if($item['buy_type_id']==51)
            {
                $arr = array(0=>"0单5双",1=>"1单4双",2=>"2单3双",3=>"3单2双",4=>"4单1双",5=>"5单0双");
                $item['buy_number'] = rtrim($item['buy_number']);
                $buynumber = explode(" ",$item['buy_number']);
                $str = "";
                foreach($buynumber as $n)
                {
                    $str .= $arr[$n]." ";
                }
                $temp['buy_number'] = $str;
            }
            else
            {
                $temp['buy_number'] = $item['buy_number'];
            }
            
            $temp['buy_time'] = date("m/d H:i:s",$item['buy_time']);
            $temp['monetary'] = sprintf("%.4f",$item['monetary']/100000);
            $temp['bonus'] = sprintf("%.4f",$item['bonus']/100000);
            $temp['operate'] = '<a href="javascript:;"  style="display:inline;padding:0" data-field="'.$temp['id'].'" data-method="adddetail">详情</a>';
            switch($item['status'])
            {
                case 0:
                    $temp['status'] = "未开奖";break;
                case 1:
                    if($item["bonus"]>0)
                        $temp['status'] = "<span style='color: green'>已中奖</span>";
                    else
                        $temp['status'] = "<span style='color: #a02f2b'>未中奖</span>";
                    break;
                case 2:
                    $temp['status'] = "<span style='color: red'>已撤单</span>";break;

            }
            $result[] =$temp;
        }
        $_temp = array();
        if(!empty($result))
        {
            $buyAddRecord = M("buy_add_record");
            foreach($result as $add) {
                $_comp_count = $buyAddRecord->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->count();
                $add["compCount"] = $_comp_count;
                $_compAmount = $buyAddRecord->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->sum('monetary');
                $add["compAmount"] = sprintf("%.4f",$_compAmount/100000);
                $_comp_amount = $buyAddRecord->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->sum('bonus');
                $add["bonusAmount"] = sprintf("%.4f",$_comp_amount/100000);

                $add['lotteryName'] = $add['lottery_name'];
                $add['serial'] = $add['lottery_serial_number'];
                //状态
                if($_comp_count<$add['add_number'])
                {
                    $add['status'] = "<span class='green'>进行中</span>";
                }
                else if($_comp_count==$add['add_number'])
                {
                    $add['status'] = "已完成";
                }
                $add['operate'] = '<a href="javascript:;"  style="display:inline;padding:0" data-method="adddetail" data-field="'.$add['id'].'">详情</a><a href="javascript:;" data-field="'.$add['id'].'"  style="display:inline;padding-left:10px" data-method="list">追号列表</a>';
                $_temp[] = $add;
            }
        }
        echo  json_encode($_temp);
    }

    public function history()
    {
        $id = I("post.lid");
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }
        $lottery_number_swoop = M("lottery_number_swoop");
        $list = $lottery_number_swoop->where("lottery_id=".$id." and time>=".$accordStartTime." and time<=".$accordEndTime)->order("id desc")->select();
        $result = array();
        foreach($list as $item)
        {
            $temp = array();
            $temp['sear'] = $item["series_number"];
            $temp['number'] = $item["lottery_number"];
            $result[] = $temp;
        }
        echo json_encode($result);
    }

    public function LotteryCodeTrend(){
        $url = C("UPLOADIMG_DOMAIN");
        if(!IS_AJAX){
            echo "<script>window.location.href='".$url."'</script>";
            exit();
        }
        $lotteryId = I("post.lotteryId","");
        $command = I("post.command","");
        $command_array = explode("-",$command);
        if(is_numeric($command_array[1])) {
            $limit = $command_array[1];
            $stime = mktime(0,0,0,date("m"),date("d"),date("Y"));
            $etime = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        } else if($command_array[1]=="today"){
            $stime = mktime(0,0,0,date("m"),date("d"),date("Y"));
            $etime = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
        } else if($command_array[1]=="yesterday"){
            $stime = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
            $etime = mktime(0,0,0,date("m"),date("d"),date("Y"));
        } else if($command_array[1]=="before"){
            $stime = mktime(0,0,0,date("m"),date("d")-2,date("Y"));
            $etime = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
        }
        $lotteryNumber = M("lottery_number");
        $LOTTERY = C("LOTTERY");
        $LOTTERYARR = $LOTTERY[$lotteryId];
        $result = $lotteryNumber->where("lottery_id=".$lotteryId." and starttime>=".$stime." and endtime<=".$etime." and lottery_number!=0")->limit($limit)->order("id desc")->select();
        $temp["lottery"] = array("id"=>$lotteryId,"shortName"=>strtolower($LOTTERYARR["for_short"]),"showName"=>$LOTTERYARR["name"],"sort"=>0,"status"=>1,"times"=>120,"type"=>1);
        foreach($result as $item){
            $lotterynumber = $item["lottery_number"][0].",".$item["lottery_number"][1].",".$item["lottery_number"][2].",".$item["lottery_number"][3].",".$item["lottery_number"][4];
            if($lotteryId!=30 && $lotteryId!=31)
                $ser = substr($item["series_number"],-3);
            else
                $ser = $item["series_number"];
            $tep[] = array("code"=>$lotterynumber,"expect"=>$ser,"time"=>date("Y-m-d H:i:s",$item["endtime"]));
        }
        $temp["list"] = $tep;
        echo json_encode($temp);
    }
}