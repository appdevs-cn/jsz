<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyMgReportController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "mgreport";
        $this->menu = "proxy";
        $this->display();
    }

    // 根据用户名查询MG订单数据
    public static function findMgOrderByUsername($username, $stime, $etime)
    {
        $parameters['username'] = strtolower($username);
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;

        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "mg";
        $tag = "mg_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

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

    // 查询MG盈亏报表
    public function ProxySearchMgReport()
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

        $MgUser = M("mg_user");
        $uid = session("SESSION_ID");

        $xResult = array();
        if(!empty($username))
        {
            $name = "jsz_".$username;
            $name = strtolower($name);
            $isHave = $MgUser->where("FIND_IN_SET(".$uid.",parent_path) and mg_username='".$name."'")->find();
            if(!empty($isHave))
            {
                $result = self::findMgOrderByUsername($name,$stime,$etime);
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
        $Result[] = array("totalBet"=>$totalBet,"totalBonus"=>$totalBonus,"totalGain"=>$totalGain,"totalRefund"=>$totalRefund);
        echo json_encode($Result);
    }

    // 生成签名函数
    public static function sign($plaintext)
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
}



?>