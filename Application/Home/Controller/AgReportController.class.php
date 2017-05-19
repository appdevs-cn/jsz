<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class AgReportController extends CommonController
{
    public function index()
    {
        $this->game = "thirdgame";
        $this->menu = "selfRecord";
        $this->reportmanager = "agreport";
        $this->display();
    }

    // 查询AG盈亏报表
    public function SearchAgReport()
    {
        $username = "jsz".session("SESSION_NAME");
        $username = strtolower($username);
        $accordStartTime = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d")-1 , date("Y")));
        $accordEndTime = date("Y-m-d H:i:s", mktime(59, 59, 59, date("m") , date("d"), date("Y")));

        $starttime = I("post.starttime","");
        $startTimeStr = (!empty($starttime)) ? $starttime." 0:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $endTimeStr = (!empty($endtime)) ? $endtime." 0:0:0" : $accordEndTime;

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
                ,"parameters" => array(
                    "startDate" => $startTimeStr
                    ,"endDate" => $endTimeStr
                    ,"playerName" => $username
                )
            )
            ,"live" => 1
            ,"timestamp" => $timestamp
            ,"signature" => $sign
        );
        $mReturn = self::sendToAg($SendData);
        $xReturn = json_decode($mReturn, true);
        $result = array();
        $rSponse = array();
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'], true);
            $list = $result['betHistories'];
            foreach($list as $item)
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
                    $transTime = $startTimeStr."至".$endTimeStr;
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
            foreach($rSponse as $item)
            {
                $Result[] = $item;
            }
            echo json_encode($Result);
        }
        else
        {
            echo json_encode($rSponse);
        }
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