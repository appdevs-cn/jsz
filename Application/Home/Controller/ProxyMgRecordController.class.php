<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyMgRecordController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "mgrecord";
        $this->menu = "proxy";
        $this->display();
    }

    // 根据用户名查询MG订单
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

    // 查询Mg订单
    public function ProxySearchMgRecord()
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

        $xReturn = array();
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
            $list = $MgUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("mg_username")->select();
            foreach($list as $item)
            {
                $username = $item['mg_username'];
                $result = self::findMgOrderByUsername($username,$stime,$etime);
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

        $xReturn = array();
        $mgGame = M("mg_game");
        foreach($listpage as $item)
        {
            $temp = array();
            $temp['mgsActionId'] = $item['mgsActionId'];
            $namearr = explode("_",$item['mbrCode']);
            $temp['username'] = $namearr[1];
            $temp['gameId'] = $mgGame->where(array("FlashGameID"=>$item['gameId']))->getField('ChinesGameName');
            if($item['transType']=="bet")
                $temp['bet'] = $item['amnt'];
            else
                $temp['bet'] = "0.0";
            if($item['transType']=="win")
                $temp['win'] = $item['amnt'];
            else
                $temp['win'] = "0.0";
            if($item['transType']=="refund")
                $temp['refund'] = $item['amnt'];
            else
                $temp['refund'] = "0.0";
            $temp['balance'] = $item['balance'];
            $temp['transTime'] = date("Y/m/d H:i:s",$item['transTime']/1000);
            $temp['page'] = $page;
            $xReturn[] = $temp;
        }

        echo json_encode($xReturn);
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