<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class AgRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "selfRecord";
        $this->game = "thirdgame";
        $this->reportmanager = "agrecord";
        $this->display();
    }

    public function SearchAgRecord()
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
        if($xReturn['status']==200)
        {
            $result = json_decode($xReturn['result'], true);
            $list = $result['betHistories'];
            import("Class.Page");
            $count = count($list);
            $p = new \Page($count,C("LISTROWS"),$parameter);
            $pages = C('PAGE');
            //可以使用该方法前用C临时改变配置
            foreach ($pages as $key => $value) {
                $p->setConfig($key, $value);
            }
            $page = $p->show();
            $listpage=array_slice($list,$p->firstRow,$p->listRows);
            $Round = array("DSP"=>"国际厅","AGQ"=>"旗舰厅","VIP"=>"包桌厅","LED"=>"竞咪厅","AGHH"=>"豪华厅","SLOT"=>"電子遊戲");
            $Game = array("BAC"=>"百家乐","CBAC"=>"包桌百家乐","LINK"=>"多台","DT"=>"龙虎","SHB"=>"骰宝",
            "ROU"=>"轮盘","FT"=>"番摊","LBAC"=>"竞咪百家乐","ULPK"=>"终极德州扑克","SBAC"=>"保險百家樂","NN"=>"牛牛","BJ"=>"21 點");
            $rSponse = array();
            foreach($listpage as $itemorder)
            {
                $order = json_decode($itemorder,true);
                $temp = array();
                $temp['billNo'] = $order['billNo'];
                if(array_key_exists($order['gameType'],$Game))
                {
                    $temp['gameType'] = $Game[$order['gameType']];
                }
                else
                {
                    $temp['gameType'] = M("ag_game")->where(array("FTP_gametype"=>$order['gameType']))->getField("ChinesGameName");
                }
                $temp['beforeCredit'] = ($order['beforeCredit']!="") ? $order['beforeCredit'] : "--";
                $temp['betAmount'] = $order['betAmount'];
                $temp['validBetAmount'] = $order['validBetAmount'];
                $temp['betTime'] = date('m/d H:i:s',$order['betTime']/1000);
                $temp['round'] = $Round[$order['round']];
                $temp['netAmount'] = $order['netAmount'];
                $temp['playerName'] = str_replace("jsz","",$order['playerName']);
                $temp['page'] = $page;
                $rSponse[] = $temp;
            }
            echo json_encode($rSponse);
        }
        else
        {
            echo json_encode($result);
        }
    }

    // 返回时间戳微妙
    public static function get_total_millisecond()  
    {  
        $time = explode (" ", microtime () );   
        $time = $time [1] . ($time [0] * 1000);   
        $time2 = explode ( ".", $time );
        $time = $time2 [0];  
        return $time;  
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