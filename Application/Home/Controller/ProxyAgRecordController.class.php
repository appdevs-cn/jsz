<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyAgRecordController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "agrecord";
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

    public function ProxySearchAgRecord()
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
        $xReturn = array();

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
                    $xReturn[] = $item;
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
                    $xReturn[] = $item;
                }
            }
        }
        import("Class.Page");
        $count = count($xReturn);
        $p = new \Page($count,C("LISTROWS"),$parameter);
        $pages = C('PAGE');
        //可以使用该方法前用C临时改变配置
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        $page = $p->show();
        $listpage=array_slice($xReturn,$p->firstRow,$p->listRows);
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