<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyAgReportController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "agreport";
        $this->menu = "proxy";
        $this->display();
    }

    // 根据用户名查询ag订单信息
    public static function findAgOrderByUsername($username, $stime, $etime)
    {
        $parameters['playerName'] = strtolower($username);
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;
        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "ag";
        $tag = "ag_s54_jsz";
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
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        $result = json_decode($xReturn['result'], true);
        $list = $result['betHistories'];
        return $list;
    }

    // 查询AG盈亏报表
    public function ProxySearchAgReport()
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

        $AgUser = M("ag_user");
        $uid = session("SESSION_ID");
        $xResult = array();

        if(!empty($username))
        {
            $name = "jsz".$username;
            $name = strtolower($name);
            $isHave = $AgUser->where("FIND_IN_SET(".$uid.",parent_path) and ag_username='".$name."'")->find();
            if(!empty($isHave))
            {
                $result = self::findAgOrderByUsername($name,$stime,$etime);
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
        $Result[] = array("totalBet"=>$totalBet,"totalGain"=>$totalGain);
        echo json_encode($Result);
    }
    
     // 生成签名函数
    public static function sign($plaintext)
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
}



?>