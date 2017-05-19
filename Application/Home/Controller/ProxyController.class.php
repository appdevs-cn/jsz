<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/9
 * Time: 下午8:17
 */

namespace Home\Controller;

use Home\Model\UserModel as User;
use Home\Model\AccordingTimeModel as AccordingTime;
use Home\Model\AccordingTimeKlcModel as AccordingTimeKlc;
class ProxyController extends CommonController
{
    public function index()
    {
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxymanager = 'team';
        $User = new User();
        $this->teamInfo = (array)json_decode($User->getTeamInfo());
        $this->display();
    }

    /**
     * 获取最近7天的团队报表
     */
    public function getUserChartInfo() {
        $uid = session("SESSION_ID");
        $according_time = M("according_time");
        $resultArray = array();
        for($i=7; $i>=0; $i--) {
            $str="";
            $time1 = mktime(3,0,0,date("m"),date("d")-$i,date("Y"));
            $time2 = mktime(3,0,0,date("m"),date("d")-$i+1,date("Y"));
            $rechargeAmount = $according_time->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('rechargeAmount');
            $tixianAmount = $according_time->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('tixianAmount');
            $touzhuAmount = $according_time->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('touzhuAmount');
            $fandianAmount = $according_time->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('fandianAmount');
            $bonusAmount = $according_time->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('bonusAmount');
            $j = 0-$i;
            $nowtime = date("m-d",strtotime("{$j} day"));
            $str .= $nowtime."&".sprintf("%.4f",$rechargeAmount/100000)."&".sprintf("%.4f",$tixianAmount/100000)."&".sprintf("%.4f",$touzhuAmount/100000)."&".sprintf("%.4f",$fandianAmount/100000)."&".sprintf("%.4f",$bonusAmount/100000);
            array_push($resultArray,$str);
        }
        echo json_encode($resultArray);
    }

    /**
     * 获取最近7天的幸运28团队报表
     */
    public function getUserXy28ChartInfo() {
        $uid = session("SESSION_ID");
        $according_time_klc = M("according_time_klc");
        $resultArray = array();
        for($i=7; $i>=0; $i--) {
            $str="";
            $time1 = mktime(3,0,0,date("m"),date("d")-$i,date("Y"));
            $time2 = mktime(3,0,0,date("m"),date("d")-$i+1,date("Y"));
            $touzhuAmount = $according_time_klc->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('touzhuAmount');
            $fandianAmount = $according_time_klc->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('fandianAmount');
            $bonusAmount = $according_time_klc->where("(FIND_IN_SET({$uid},parent_path) or userid=".$uid.") and accordTime>=".$time1." and accordTime<=".$time2)->sum('bonusAmount');
            $j = 0-$i;
            $nowtime = date("m-d",strtotime("{$j} day"));
            $str .= $nowtime."&".sprintf("%.4f",$touzhuAmount/100000)."&".sprintf("%.4f",$fandianAmount/100000)."&".sprintf("%.4f",$bonusAmount/100000);
            array_push($resultArray,$str);
        }
        echo json_encode($resultArray);
    }

    /**
     * 根据day查询报表
     */
    public function reportByDay()
    {
        $day = I("post.day");
        if($day=="") exit();
        if($day=='today')
            $starttime = mktime(3,0,0,date('m'),date('d'),date('Y'));
        else if($day=="yestoday")
            $starttime = mktime(3,0,0,date('m'),date('d')-1,date('Y'));
        else if($day=="sevenday")
            $starttime = mktime(3,0,0,date('m'),date('d')-7,date('Y'));

        $AccordingTime = new AccordingTime('','','');
        $report = $AccordingTime->getTeamReport($starttime);
        $User = new User();
        $User->data = array("day"=>$day);
        $newadduser = $User->newAddUser();
        echo json_encode(array('report'=>$report,'newuser'=>$newadduser));
    }

    /**
     * 根据day查询幸运28报表
     */
    public function reportXy28ByDay()
    {
        $day = I("post.day");
        if($day=="") exit();
        if($day=='today')
            $starttime = mktime(3,0,0,date('m'),date('d'),date('Y'));
        else if($day=="yestoday")
            $starttime = mktime(3,0,0,date('m'),date('d')-1,date('Y'));
        else if($day=="sevenday")
            $starttime = mktime(3,0,0,date('m'),date('d')-7,date('Y'));

        $AccordingTimeKlc = new AccordingTimeKlc('','','');
        $report = $AccordingTimeKlc->getTeamReport($starttime);
        $User = new User();
        $User->data = array("day"=>$day);
        $newadduser = $User->newAddUser();
        echo json_encode(array('report'=>$report,'newuser'=>$newadduser));
    }

    // 获取AG7天的报表
    public function getReportAgChart()
    {
        $AgUser = M("ag_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $AgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ag_username")->select();
        $resultArray = array();
        for($i=7; $i>=0; $i--) 
        {
            $str="";
            foreach($list as $item)
            {
                $stime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i,date("Y")));
                $etime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i+1,date("Y")));
                $username = $item['ag_username'];
                $result = self::findAgOrderByUsername($username,$stime,$etime);
                foreach($result as $item)
                {
                    $xResult[] = $item;
                }
            }
            $result = array();
            $rSponse = array();
            foreach($xResult as $item)
            {
                $item = json_decode($item, true);
                if(!isset($rSponse[$item['playerName']]))
                {
                    $namearr = str_replace("jsz","",$item['playerName']);
                    $betMoney = $item['betAmount'];
                    if(bccomp($item['netAmount'],0,2)==1)
                    {
                        $gain = $item['netAmount'];
                    }
                    else
                    {
                        $gain = 0-abs($item['netAmount']);
                    }
                    $transTime = $stime."至".$etime;
                    $rSponse[$item['playerName']] = array("username"=>$namearr,"bet"=>$betMoney,"win"=>$payout,"gain"=>$gain,"time"=>$transTime);
                }
                else
                {
                    $rSponse[$item['playerName']]['bet'] = $rSponse[$item['playerName']]['bet']+$item['betAmount'];
                    if(bccomp($item['netAmount'],0,2)==1)
                    {
                        $rSponse[$item['playerName']]['gain'] = $rSponse[$item['playerName']]['gain']+$item['netAmount'];
                    }
                    else
                    {
                        $rSponse[$item['playerName']]['gain'] = $rSponse[$item['playerName']]['gain']-abs($item['netAmount']);
                    }
                }
            }
            $totalBet = $totalGain = 0;
            foreach($rSponse as $item)
            {
                $totalBet = $totalBet + $item['bet'];
                $totalGain = $totalGain + $item['gain'];
                $Result[] = $item;
            }
            $j = 0-$i;
            $nowtime = date("m-d",strtotime("{$j} day"));
            $str .= $nowtime."&".$totalBet."&".$totalGain;
            array_push($resultArray,$str);
        }
        echo json_encode($resultArray);
    }

    /**
     * 根据day查询AG报表
     */
    public function reportAgByDay()
    {
        $day = I("post.day");
        if($day=="") exit();
        if($day=='today')
            $starttime = mktime(3,0,0,date('m'),date('d'),date('Y'));
        else if($day=="yestoday")
            $starttime = mktime(3,0,0,date('m'),date('d')-1,date('Y'));
        else if($day=="sevenday")
            $starttime = mktime(3,0,0,date('m'),date('d')-7,date('Y'));
        $starttime = date("Y-m-d H:i:s",$starttime);
        $endtime = date("Y-m-d H:i:s",time());
        $AgUser = M("ag_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $AgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ag_username")->select();
        foreach($list as $item)
        {
            $username = $item['ag_username'];
            $result = self::findAgOrderByUsername($username,$starttime,$endtime);
            foreach($result as $item)
            {
                $xResult[] = $item;
            }
        }
        $result = array();
        $rSponse = array();
        foreach($xResult as $item)
        {
            $item = json_decode($item, true);
            if(!isset($rSponse[$item['playerName']]))
            {
                $namearr = str_replace("jsz","",$item['playerName']);
                $betMoney = $item['betAmount'];
                if(bccomp($item['netAmount'],0,2)==1)
                {
                    $gain = $item['netAmount'];
                }
                else
                {
                    $gain = 0-abs($item['netAmount']);
                }
                $transTime = $stime."至".$etime;
                $rSponse[$item['playerName']] = array("username"=>$namearr,"bet"=>$betMoney,"win"=>$payout,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $rSponse[$item['playerName']]['bet'] = $rSponse[$item['playerName']]['bet']+$item['betAmount'];
                if(bccomp($item['netAmount'],0,2)==1)
                {
                    $rSponse[$item['playerName']]['gain'] = $rSponse[$item['playerName']]['gain']+$item['netAmount'];
                }
                else
                {
                    $rSponse[$item['playerName']]['gain'] = $rSponse[$item['playerName']]['gain']-abs($item['netAmount']);
                }
            }
        }
        $totalBet = $totalGain = 0;
        foreach($rSponse as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalGain = $totalGain + $item['gain'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalGain"=>$totalGain)));
    }

    /**
     * 根据时间范围获取AG报表已经用户增量
     */
    public function reportAgByTime()
    {
        $starttime = I("post.stime");
        $endtime = I("post.etime");
        if($starttime=="" || $endtime=="") exit();
        $stime = $starttime." "."0:00:00";
        $etime = $endtime." "."0:00:00";
        $AgUser = M("ag_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $AgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ag_username")->select();
        foreach($list as $item)
        {
            $username = $item['ag_username'];
            $result = self::findAgOrderByUsername($username,$stime,$etime);
            foreach($result as $item)
            {
                $xResult[] = $item;
            }
        }
        $result = array();
        $rSponse = array();
        foreach($xResult as $item)
        {
            $item = json_decode($item, true);
            if(!isset($rSponse[$item['playerName']]))
            {
                $namearr = str_replace("jsz","",$item['playerName']);
                $betMoney = $item['betAmount'];
                if(bccomp($item['netAmount'],0,2)==1)
                {
                    $gain = $item['netAmount'];
                }
                else
                {
                    $gain = 0-abs($item['netAmount']);
                }
                $transTime = $stime."至".$etime;
                $rSponse[$item['playerName']] = array("username"=>$namearr,"bet"=>$betMoney,"win"=>$payout,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $rSponse[$item['playerName']]['bet'] = $rSponse[$item['playerName']]['bet']+$item['betAmount'];
                if(bccomp($item['netAmount'],0,2)==1)
                {
                    $rSponse[$item['playerName']]['gain'] = $rSponse[$item['playerName']]['gain']+$item['netAmount'];
                }
                else
                {
                    $rSponse[$item['playerName']]['gain'] = $rSponse[$item['playerName']]['gain']-abs($item['netAmount']);
                }
            }
        }
        $totalBet = $totalGain = 0;
        foreach($rSponse as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalGain = $totalGain + $item['gain'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalGain"=>$totalGain)));
    }

    // 根据用户名查询ag订单信息
    public static function findAgOrderByUsername($username, $stime, $etime)
    {
        $parameters['playerName'] = $username;
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::signAg($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => $parameters
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        $result = json_decode($xReturn['result'], true);
        $list = $result['betHistories'];
        return $list;
    }

     // 生成签名函数
    public static function signAg($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('AG_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToAg($SentData)
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


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // MG报表
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // 根据用户名查询MG订单数据
    public static function findMgOrderByUsername($username, $stime, $etime)
    {
        $parameters['username'] = $username;
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;

        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::signMg($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => $parameters
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToMg($SendData);
        $templist = json_decode($mReturn, true);
        $tlist = json_decode($templist['result'],true);
        $list = $tlist['betHistories'];
        return $list;
    }

    // 获取MG7天的报表
    public function getReportMgChart()
    {
        $MgUser = M("mg_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $MgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("mg_username")->select();
        $resultArray = array();
        for($i=7; $i>=0; $i--) 
        {
            $str="";
            foreach($list as $item)
            {
                $stime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i,date("Y")));
                $etime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i+1,date("Y")));
                $username = $item['mg_username'];
                $result = self::findMgOrderByUsername($username,$stime,$etime);
                foreach($result as $item)
                {
                    $xResult[] = $item;
                }
            }
            $xReturn = array();
            $totalbet = $totalwin = $totalrefund = $totalgain = 0;
            foreach($xResult as $item)
            {
                if(!isset($xReturn[$item['mbrCode']]))
                {
                    $namearr = explode("_",$item['mbrCode']);
                    $bet = ($item['transType']=="bet") ? $item['amnt'] : 0;
                    $win = ($item['transType']=="win") ? $item['amnt'] : 0;
                    $refund = ($item['transType']=="refund") ? $item['amnt'] : 0;
                    $gain = $win-$bet;
                    $transTime = $stime."至".$etime;
                    $xReturn[$item['mbrCode']] = array("username"=>$namearr[1],"bet"=>$bet,"win"=>$win,"refund"=>$refund,"gain"=>$gain,"time"=>$transTime);
                }
                else
                {
                    $xReturn[$item['mbrCode']]['bet'] = ($item['transType']=="bet") ? ($xReturn[$item['mbrCode']]['bet']+$item['amnt']) : $xReturn[$item['mbrCode']]['bet'];
                    $xReturn[$item['mbrCode']]['win'] = ($item['transType']=="win") ? ($xReturn[$item['mbrCode']]['win']+$item['amnt']) : $xReturn[$item['mbrCode']]['win'];
                    $xReturn[$item['mbrCode']]['refund'] = ($item['transType']=="refund") ? ($xReturn[$item['mbrCode']]['refund']+$item['amnt']) : $xReturn[$item['mbrCode']]['refund'];
                    $xReturn[$item['mbrCode']]['gain'] = $xReturn[$item['mbrCode']]['win']-$xReturn[$item['mbrCode']]['bet'];
                }
            }
            $Result = array();
            $totalBet = $totalBonus = $totalRefund = $totalGain = 0;
            foreach($xReturn as $item)
            {
                $totalBet = $totalBet + $item['bet'];
                $totalBonus = $totalBonus + $item['win'];
                $totalGain = $totalGain + $item['gain'];
                $totalRefund = $totalRefund + $item['refund'];
                $Result[] = $item;
            }
            $j = 0-$i;
            $nowtime = date("m-d",strtotime("{$j} day"));
            $str .= $nowtime."&".$totalBet."&".$totalBonus."&".$totalGain;
            array_push($resultArray,$str);
        }
        echo json_encode($resultArray);
    }

    /**
     * 根据day查询MG报表
     */
    public function reportMgByDay()
    {
        $day = I("post.day");
        if($day=="") exit();
        if($day=='today')
            $starttime = mktime(3,0,0,date('m'),date('d'),date('Y'));
        else if($day=="yestoday")
            $starttime = mktime(3,0,0,date('m'),date('d')-1,date('Y'));
        else if($day=="sevenday")
            $starttime = mktime(3,0,0,date('m'),date('d')-7,date('Y'));
        $starttime = date("Y-m-d H:i:s",$starttime);
        $endtime = date("Y-m-d H:i:s",time());
        $MgUser = M("mg_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $MgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("mg_username")->select();
        foreach($list as $item)
        {
            $username = $item['mg_username'];
            $result = self::findMgOrderByUsername($username,$starttime,$endtime);
            foreach($result as $item)
            {
                $xResult[] = $item;
            }
        }
        $xReturn = array();
        $totalbet = $totalwin = $totalrefund = $totalgain = 0;
        foreach($xResult as $item)
        {
            if(!isset($xReturn[$item['mbrCode']]))
            {
                $namearr = explode("_",$item['mbrCode']);
                $bet = ($item['transType']=="bet") ? $item['amnt'] : 0;
                $win = ($item['transType']=="win") ? $item['amnt'] : 0;
                $refund = ($item['transType']=="refund") ? $item['amnt'] : 0;
                $gain = $win-$bet;
                $transTime = $stime."至".$etime;
                $xReturn[$item['mbrCode']] = array("username"=>$namearr[1],"bet"=>$bet,"win"=>$win,"refund"=>$refund,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $xReturn[$item['mbrCode']]['bet'] = ($item['transType']=="bet") ? ($xReturn[$item['mbrCode']]['bet']+$item['amnt']) : $xReturn[$item['mbrCode']]['bet'];
                $xReturn[$item['mbrCode']]['win'] = ($item['transType']=="win") ? ($xReturn[$item['mbrCode']]['win']+$item['amnt']) : $xReturn[$item['mbrCode']]['win'];
                $xReturn[$item['mbrCode']]['refund'] = ($item['transType']=="refund") ? ($xReturn[$item['mbrCode']]['refund']+$item['amnt']) : $xReturn[$item['mbrCode']]['refund'];
                $xReturn[$item['mbrCode']]['gain'] = $xReturn[$item['mbrCode']]['win']-$xReturn[$item['mbrCode']]['bet'];
            }
        }
        $Result = array();
        $totalBet = $totalBonus = $totalRefund = $totalGain = 0;
        foreach($xReturn as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalBonus = $totalBonus + $item['win'];
            $totalGain = $totalGain + $item['gain'];
            $totalRefund = $totalRefund + $item['refund'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain)));
    }

    /**
     * 根据时间范围获取MG报表已经用户增量
     */
    public function reportMgByTime()
    {
        $starttime = I("post.stime");
        $endtime = I("post.etime");
        if($starttime=="" || $endtime=="") exit();
        $stime = $starttime." "."0:00:00";
        $etime = $endtime." "."0:00:00";
        $MgUser = M("mg_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $MgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("mg_username")->select();
        foreach($list as $item)
        {
            $username = $item['mg_username'];
            $result = self::findMgOrderByUsername($username,$stime,$etime);
            foreach($result as $item)
            {
                $xResult[] = $item;
            }
        }
        $xReturn = array();
        $totalbet = $totalwin = $totalrefund = $totalgain = 0;
        foreach($xResult as $item)
        {
            if(!isset($xReturn[$item['mbrCode']]))
            {
                $namearr = explode("_",$item['mbrCode']);
                $bet = ($item['transType']=="bet") ? $item['amnt'] : 0;
                $win = ($item['transType']=="win") ? $item['amnt'] : 0;
                $refund = ($item['transType']=="refund") ? $item['amnt'] : 0;
                $gain = $win-$bet;
                $transTime = $stime."至".$etime;
                $xReturn[$item['mbrCode']] = array("username"=>$namearr[1],"bet"=>$bet,"win"=>$win,"refund"=>$refund,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $xReturn[$item['mbrCode']]['bet'] = ($item['transType']=="bet") ? ($xReturn[$item['mbrCode']]['bet']+$item['amnt']) : $xReturn[$item['mbrCode']]['bet'];
                $xReturn[$item['mbrCode']]['win'] = ($item['transType']=="win") ? ($xReturn[$item['mbrCode']]['win']+$item['amnt']) : $xReturn[$item['mbrCode']]['win'];
                $xReturn[$item['mbrCode']]['refund'] = ($item['transType']=="refund") ? ($xReturn[$item['mbrCode']]['refund']+$item['amnt']) : $xReturn[$item['mbrCode']]['refund'];
                $xReturn[$item['mbrCode']]['gain'] = $xReturn[$item['mbrCode']]['win']-$xReturn[$item['mbrCode']]['bet'];
            }
        }
        $Result = array();
        $totalBet = $totalBonus = $totalRefund = $totalGain = 0;
        foreach($xReturn as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalBonus = $totalBonus + $item['win'];
            $totalGain = $totalGain + $item['gain'];
            $totalRefund = $totalRefund + $item['refund'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain)));
    }

    // 生成签名函数
    public static function signMg($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('MG_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToMg($SentData)
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



    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Pt报表
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // 获取Pt7天的报表
    public function getReportPtChart()
    {
        $PtUser = M("pt_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $PtUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("EBJSZ_username")->select();
        $resultArray = array();
        for($i=7; $i>=0; $i--) 
        {
            $str="";
            foreach($list as $item)
            {
                $stime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i,date("Y")));
                $etime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i+1,date("Y")));
                $arr = explode("_",$item['EBJSZ_username']);
                $username = "EBJSZ_".strtoupper($arr[1]);
                $result = self::findPtOrderByUsername($username,$stime,$etime);
                foreach($result as $item)
                {
                    $xReult[] = $item;
                }
            }
            $xReturn = array();
            foreach($xReult as $item)
            {
                if(!isset($xReturn[$item['playerName']]))
                {
                    $namearr = explode("_",$item['playerName']);
                    $bet = $item['bet'];
                    $win = $item['win'];
                    $gain = $win-$bet;
                    $transTime = $stime."至".$etime;
                    $xReturn[$item['playerName']] = array("username"=>$namearr[1],"bet"=>$bet,"win"=>$win,"gain"=>$gain,"time"=>$transTime);
                }
                else
                {
                    $xReturn[$item['playerName']]['bet'] = $xReturn[$item['playerName']]['bet']+$item['bet'];
                    $xReturn[$item['playerName']]['win'] = $xReturn[$item['playerName']]['win']+$item['win'];
                    $xReturn[$item['playerName']]['gain'] = $xReturn[$item['playerName']]['win']-$xReturn[$item['playerName']]['bet'];
                }
            }
            $Result = array();
            $totalBet = $totalBonus = $totalGain = 0;
            foreach($xReturn as $item)
            {
                $totalBet = $totalBet + $item['bet'];
                $totalBonus = $totalBonus + $item['win'];
                $totalGain = $totalGain + $item['gain'];
                $Result[] = $item;
            }
            $j = 0-$i;
            $nowtime = date("m-d",strtotime("{$j} day"));
            $str .= $nowtime."&".$totalBet."&".$totalBonus."&".$totalGain;
            array_push($resultArray,$str);
        }
        echo json_encode($resultArray);
    }

    /**
     * 根据day查询PT报表
     */
    public function reportPtByDay()
    {
        $day = I("post.day");
        if($day=="") exit();
        if($day=='today')
            $starttime = mktime(3,0,0,date('m'),date('d'),date('Y'));
        else if($day=="yestoday")
            $starttime = mktime(3,0,0,date('m'),date('d')-1,date('Y'));
        else if($day=="sevenday")
            $starttime = mktime(3,0,0,date('m'),date('d')-7,date('Y'));
        $starttime = date("Y-m-d H:i:s",$starttime);
        $endtime = date("Y-m-d H:i:s",time());
        $PtUser = M("pt_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $PtUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("EBJSZ_username")->select();
        foreach($list as $item)
        {
            $arr = explode("_",$item['EBJSZ_username']);
            $username = "EBJSZ_".strtoupper($arr[1]);
            $result = self::findPtOrderByUsername($username,$starttime,$endtime);
            foreach($result as $item)
            {
                $xReult[] = $item;
            }
        }
        $xReturn = array();
        foreach($xReult as $item)
        {
            if(!isset($xReturn[$item['playerName']]))
            {
                $namearr = explode("_",$item['playerName']);
                $bet = $item['bet'];
                $win = $item['win'];
                $gain = $win-$bet;
                $transTime = $stime."至".$etime;
                $xReturn[$item['playerName']] = array("username"=>$namearr[1],"bet"=>$bet,"win"=>$win,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $xReturn[$item['playerName']]['bet'] = $xReturn[$item['playerName']]['bet']+$item['bet'];
                $xReturn[$item['playerName']]['win'] = $xReturn[$item['playerName']]['win']+$item['win'];
                $xReturn[$item['playerName']]['gain'] = $xReturn[$item['playerName']]['win']-$xReturn[$item['playerName']]['bet'];
            }
        }
        $Result = array();
        $totalBet = $totalBonus = $totalGain = 0;
        foreach($xReturn as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalBonus = $totalBonus + $item['win'];
            $totalGain = $totalGain + $item['gain'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain)));
    }

    /**
     * 根据时间范围获取PT报表已经用户增量
     */
    public function reportPtByTime()
    {
        $starttime = I("post.stime");
        $endtime = I("post.etime");
        if($starttime=="" || $endtime=="") exit();
        $stime = $starttime." "."0:00:00";
        $etime = $endtime." "."0:00:00";
        $PtUser = M("pt_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $PtUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("EBJSZ_username")->select();
        foreach($list as $item)
        {
            $arr = explode("_",$item['EBJSZ_username']);
            $username = "EBJSZ_".strtoupper($arr[1]);
            $result = self::findPtOrderByUsername($username,$stime,$etime);
            foreach($result as $item)
            {
                $xReult[] = $item;
            }
        }
        $xReturn = array();
        foreach($xReult as $item)
        {
            if(!isset($xReturn[$item['playerName']]))
            {
                $namearr = explode("_",$item['playerName']);
                $bet = $item['bet'];
                $win = $item['win'];
                $gain = $win-$bet;
                $transTime = $stime."至".$etime;
                $xReturn[$item['playerName']] = array("username"=>$namearr[1],"bet"=>$bet,"win"=>$win,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $xReturn[$item['playerName']]['bet'] = $xReturn[$item['playerName']]['bet']+$item['bet'];
                $xReturn[$item['playerName']]['win'] = $xReturn[$item['playerName']]['win']+$item['win'];
                $xReturn[$item['playerName']]['gain'] = $xReturn[$item['playerName']]['win']-$xReturn[$item['playerName']]['bet'];
            }
        }
        $Result = array();
        $totalBet = $totalBonus = $totalGain = 0;
        foreach($xReturn as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalBonus = $totalBonus + $item['win'];
            $totalGain = $totalGain + $item['gain'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain)));
    }

    // 根据用户名查询PT订单信息
    public static function findPtOrderByUsername($username, $stime, $etime)
    {
        $parameters['username'] = $username;
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;

        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::signPt($plaintext);

        $ip = get_client_ip();
        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"thirdParty" => $thirdParty
            ,"tag" => $tag
            ,"action" => array(
                "command" => "getrawbethistory"
                ,"parameters" => $parameters
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToPt($SendData);
        $xReturn = json_decode($mReturn, true);
        $result = array();
        $list = json_decode($xReturn['result'],true);
        $list = $list['betHistories'];
        return $list;
    }
    // 生成签名函数
    public static function signPt($plaintext)
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


    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    // Ebet报表
    // +++++++++++++++++++++++++++++++++++++++++++++++++++++++

    // 获取Ebet7天的报表
    public function getReportEbetChart()
    {
        $EbetUser = M("ebet_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $EbetUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ebet_username")->select();
        $resultArray = array();
        for($i=7; $i>=0; $i--) 
        {
            $str="";
            foreach($list as $item)
            {
                $stime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i,date("Y")));
                $etime = date("Y-m-d H:i:s",mktime(3,0,0,date("m"),date("d")-$i+1,date("Y")));
                $username = $item['ebet_username'];
                $result = self::findEbetOrderByUsername($username,$stime,$etime);
                foreach($result as $item)
                {
                    $xResult[] = $item;
                }
            }
            $xReturn = array();

            foreach($xResult as $item)
            {
                if(!isset($xReturn[$item['username']]))
                {
                    $namearr = explode("_",$item['username']);
                    $betMap = $item['betMap'];
                    $betMoney = 0;
                    foreach($betMap as $bet)
                    {
                        $betMoney = $betMoney + $bet['betMoney'];
                    }
                    $payout = $item['payout'];
                    $gain = $payout-$betMoney;
                    $transTime = $stime."至".$etime;
                    $xReturn[$item['username']] = array("username"=>$namearr[1],"bet"=>$betMoney,"win"=>$payout,"gain"=>$gain,"time"=>$transTime);
                }
                else
                {
                    $betMap = $item['betMap'];
                    $betMoney = 0;
                    foreach($betMap as $bet)
                    {
                        $betMoney = $betMoney + $bet['betMoney'];
                    }
                    $payout = $item['payout'];
                    $xReturn[$item['username']]['bet'] = $xReturn[$item['username']]['bet']+$betMoney;
                    $xReturn[$item['username']]['win'] = $xReturn[$item['username']]['win']+$payout;
                    $xReturn[$item['username']]['gain'] = $xReturn[$item['username']]['win']-$xReturn[$item['username']]['bet'];
                }
            }
            $totalBet = $totalBonus = $totalGain = 0;
            foreach($xReturn as $item)
            {
                $totalBet = $totalBet + $item['bet'];
                $totalBonus = $totalBonus + $item['win'];
                $totalGain = $totalGain + $item['gain'];
                $Result[] = $item;
            }
            $j = 0-$i;
            $nowtime = date("m-d",strtotime("{$j} day"));
            $str .= $nowtime."&".$totalBet."&".$totalBonus."&".$totalGain;
            array_push($resultArray,$str);
        }
        echo json_encode($resultArray);
    }

    /**
     * 根据day查询EBET报表
     */
    public function reportEbetByDay()
    {
        $day = I("post.day");
        if($day=="") exit();
        if($day=='today')
            $starttime = mktime(3,0,0,date('m'),date('d'),date('Y'));
        else if($day=="yestoday")
            $starttime = mktime(3,0,0,date('m'),date('d')-1,date('Y'));
        else if($day=="sevenday")
            $starttime = mktime(3,0,0,date('m'),date('d')-7,date('Y'));
        $starttime = date("Y-m-d H:i:s",$starttime);
        $endtime = date("Y-m-d H:i:s",time());
        $EbetUser = M("ebet_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $EbetUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ebet_username")->select();
        foreach($list as $item)
        {
            $username = $item['ebet_username'];
            $result = self::findEbetOrderByUsername($username,$starttime,$endtime);
            foreach($result as $item)
            {
                $xResult[] = $item;
            }
        }
        $xReturn = array();

        foreach($xResult as $item)
        {
            if(!isset($xReturn[$item['username']]))
            {
                $namearr = explode("_",$item['username']);
                $betMap = $item['betMap'];
                $betMoney = 0;
                foreach($betMap as $bet)
                {
                    $betMoney = $betMoney + $bet['betMoney'];
                }
                $payout = $item['payout'];
                $gain = $payout-$betMoney;
                $transTime = $stime."至".$etime;
                $xReturn[$item['username']] = array("username"=>$namearr[1],"bet"=>$betMoney,"win"=>$payout,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $betMap = $item['betMap'];
                $betMoney = 0;
                foreach($betMap as $bet)
                {
                    $betMoney = $betMoney + $bet['betMoney'];
                }
                $payout = $item['payout'];
                $xReturn[$item['username']]['bet'] = $xReturn[$item['username']]['bet']+$betMoney;
                $xReturn[$item['username']]['win'] = $xReturn[$item['username']]['win']+$payout;
                $xReturn[$item['username']]['gain'] = $xReturn[$item['username']]['win']-$xReturn[$item['username']]['bet'];
            }
        }
        $totalBet = $totalBonus = $totalGain = 0;
        foreach($xReturn as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalBonus = $totalBonus + $item['win'];
            $totalGain = $totalGain + $item['gain'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain)));
    }

    /**
     * 根据时间范围获取Ebet报表已经用户增量
     */
    public function reportEbetByTime()
    {
        $starttime = I("post.stime");
        $endtime = I("post.etime");
        if($starttime=="" || $endtime=="") exit();
        $stime = $starttime." "."0:00:00";
        $etime = $endtime." "."0:00:00";
        $EbetUser = M("ebet_user");
        $uid = session("SESSION_ID");
        $xResult = array();
        $list = $EbetUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ebet_username")->select();
        foreach($list as $item)
        {
            $username = $item['ebet_username'];
            $result = self::findEbetOrderByUsername($username,$stime,$etime);
            foreach($result as $item)
            {
                $xResult[] = $item;
            }
        }
        $xReturn = array();

        foreach($xResult as $item)
        {
            if(!isset($xReturn[$item['username']]))
            {
                $namearr = explode("_",$item['username']);
                $betMap = $item['betMap'];
                $betMoney = 0;
                foreach($betMap as $bet)
                {
                    $betMoney = $betMoney + $bet['betMoney'];
                }
                $payout = $item['payout'];
                $gain = $payout-$betMoney;
                $transTime = $stime."至".$etime;
                $xReturn[$item['username']] = array("username"=>$namearr[1],"bet"=>$betMoney,"win"=>$payout,"gain"=>$gain,"time"=>$transTime);
            }
            else
            {
                $betMap = $item['betMap'];
                $betMoney = 0;
                foreach($betMap as $bet)
                {
                    $betMoney = $betMoney + $bet['betMoney'];
                }
                $payout = $item['payout'];
                $xReturn[$item['username']]['bet'] = $xReturn[$item['username']]['bet']+$betMoney;
                $xReturn[$item['username']]['win'] = $xReturn[$item['username']]['win']+$payout;
                $xReturn[$item['username']]['gain'] = $xReturn[$item['username']]['win']-$xReturn[$item['username']]['bet'];
            }
        }
        $totalBet = $totalBonus = $totalGain = 0;
        foreach($xReturn as $item)
        {
            $totalBet = $totalBet + $item['bet'];
            $totalBonus = $totalBonus + $item['win'];
            $totalGain = $totalGain + $item['gain'];
            $Result[] = $item;
        }
        echo json_encode(array('report'=>array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain)));
    }

    // 根据用户名查询EBET订单信息
    public static function findEbetOrderByUsername($username, $stime, $etime)
    {
        $timestamp = time();
        // 生成签名
        $channelId = 273;
        $plaintext = $username.$timestamp;
        $sign = self::signEbet($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => $username
            ,"timestamp" => $timestamp
            ,"startTimeStr" => $stime
            ,"endTimeStr" => $etime
            ,"signature" => $sign
        );
        $url = "http://jsz.ebet.im:8888/api/userbethistory";
        $mReturn = self::sendToEbet($SendData,$url);
        $xReturn = json_decode($mReturn, true);
        $list = $xReturn['betHistories'];
        return $list;
    }

    // 生成签名函数
    public static function signEbet($plaintext)
    {
        Vendor('CryptRSA.Crypt.RSA');
        $rsa = new \Crypt_RSA();
        $rsa->loadKey(C('EBET_RSA'));
        $rsa->setSignatureMode(CRYPT_RSA_SIGNATURE_PKCS1); 
        $rsa->setHash("md5");
        $signature = $rsa->sign($plaintext);
        $sign = base64_encode($signature);
        return $sign;
    }

    public static function sendToEbet($SentData,$url)
    {
        $options = array(
            'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($SentData)));
        $context = stream_context_create($options);
        return file_get_contents($url, false, $context);
    }

    /**
     * 根据时间范围获取报表已经用户增量
     */
    public function reportByTime()
    {
        $starttime = I("post.stime");
        $endtime = I("post.etime");
        if($starttime=="" || $endtime=="") exit();
        $stime = strtotime($starttime." "."03:00:00");
        $etime = strtotime($endtime." "."03:00:00");
        $AccordingTime = new AccordingTime('','','');
        $report = $AccordingTime->getTeamReport($stime,$etime);
        $User = new User();
        $User->data = array("stime"=>$stime,'etime'=>$etime);
        $newadduser = $User->newAddUserByTimeRange();
        echo json_encode(array('report'=>$report,'newuser'=>$newadduser));
    }

    /**
     * 根据时间范围获取幸运28报表已经用户增量
     */
    public function reportXy28ByTime()
    {
        $starttime = I("post.stime");
        $endtime = I("post.etime");
        if($starttime=="" || $endtime=="") exit();
        $stime = strtotime($starttime." "."03:00:00");
        $etime = strtotime($endtime." "."03:00:00");
        $AccordingTimeKlc = new AccordingTimeKlc('','','');
        $report = $AccordingTimeKlc->getTeamReport($stime,$etime);
        $User = new User();
        $User->data = array("stime"=>$stime,'etime'=>$etime);
        $newadduser = $User->newAddUserByTimeRange();
        echo json_encode(array('report'=>$report,'newuser'=>$newadduser));
    }
}