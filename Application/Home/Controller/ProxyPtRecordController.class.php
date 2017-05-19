<?php

namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyPtRecordController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "ptrecord";
        $this->menu = "proxy";
        $this->display();
    }

    // 根据用户名查询PT订单信息
    public static function findPtOrderByUsername($username, $stime, $etime)
    {
        $parameters['username'] = strtoupper($username);
        $parameters['startDate'] = $stime;
        $parameters['endDate'] = $etime;

        $timestamp = time();
        // 生成签名
        $channelId = 10;
        $thirdParty = "ipm";
        $tag = "ipm_ebet8prod_jsz";
        $plaintext = $channelId.$thirdParty.$tag.$timestamp;
        $sign = self::sign($plaintext);

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

    // 查询EBET下注订单记录
    public function SearchProxyPtRecord()
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
        $username = strtoupper($username);

        $PtUser = M("pt_user");
        $uid = session("SESSION_ID");
        $xReturn = array();
        if(!empty($username))
        {
            $name = "EBJSZ_".strtoupper($username);
            $isHave = $PtUser->where("FIND_IN_SET(".$uid.",parent_path) and EBJSZ_username='".$name."'")->find();
            if(!empty($isHave))
            {
                $result = self::findPtOrderByUsername($name,$stime,$etime);
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
            $list = $PtUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("EBJSZ_username")->select();
            foreach($list as $item)
            {
                $arr = explode("_",$item['EBJSZ_username']);
                $username = "EBJSZ_".strtoupper($arr[1]);
                $result = self::findPtOrderByUsername($username,$stime,$etime);
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
        $ptgame = M("pt_game");
        foreach($listpage as $item)
        {
            $temp = array();
            $temp['id'] = $item['id'];
            $usernamearr = explode("_",$item['playerName']);
            $temp['playerName'] = $usernamearr[1];
            $temp['vipLevel'] = $item['vipLevel']."级";
            $gameCode = $item['gameCode'];
            $in = array(); 
            preg_match_all("/(?:\()(.*)(?:\))/i",$item['gameType'], $in);
            $gamecode = $in[1][0];
            $temp['gameType'] = M("pt_game")->where(array("FL"=>$gamecode))->getField("ChinesGameName");
            $temp['gameDate'] = date('m/d H:i:s',$item['gameDate']/1000);
            $temp['startAmount'] = $item['startAmount'];
            $temp['bet'] = $item['bet'];
            $temp['win'] = $item['win'];
            $temp['endAmount'] = $item['endAmount'];
            $temp['page'] = $page;
            if($temp['bet']==0 && $temp['win']==0) continue;
            $xReturn[] = $temp;
        }

        echo json_encode($xReturn);
    }

    // 生成签名函数
    public static function sign($plaintext)
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
}



?>