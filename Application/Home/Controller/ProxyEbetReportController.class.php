<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyEbetReportController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "ebetreport";
        $this->menu = "proxy";
        $this->display();
    }

    // 根据用户名查询EBET订单信息
    public static function findEbetOrderByUsername($username, $stime, $etime)
    {
        $timestamp = time();
        // 生成签名
        $channelId = 273;
        $plaintext = $username.$timestamp;
        $sign = self::sign($plaintext);

        // 组合参数
        $SendData = array(
            "channelId" => $channelId
            ,"username" => strtolower($username)
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

    // 查询MG盈亏报表
    public function ProxySearchEbetReport()
    {
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(3, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordStartTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y")));
            $accordEndTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y")));
        } else {
            $accordStartTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y")));
            $accordEndTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y")));
        }
        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? $starttime." 3:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? $endtime." 3:0:0" : $accordEndTime;

        $username = I("post.username");
        $username = strtolower($username);

        $EbetUser = M("ebet_user");
        $uid = session("SESSION_ID");

        $xResult = array();
        if(!empty($username))
        {
            $name = "ebet_".$username;
            $name = strtolower($name);
            $isHave = $EbetUser->where("FIND_IN_SET(".$uid.",parent_path) and ebet_username='".$name."'")->find();
            if(!empty($isHave))
            {
                $result = self::findEbetOrderByUsername($name,$stime,$etime);
                foreach($result as $item)
                {
                    $xResult[] = $item;
                }
            }
            else
            {
                echo json_encode(array());
                exit();
            }
        }
        else
        {
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
        $Result[] = array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain);
        echo json_encode($Result);
    }

    // 生成签名函数
    public static function sign($plaintext)
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
}



?>