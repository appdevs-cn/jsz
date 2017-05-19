<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class ProxyEbetRecordController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "ebetrecord";
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

    // 查询EBET下注订单记录
    public function SearchProxyEbetRecord()
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

        $xReturn = array();
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
            $list = $EbetUser->where("FIND_IN_SET(".$uid.",parent_path) or userid=".$uid)->field("ebet_username")->select();
            foreach($list as $item)
            {
                $username = $item['ebet_username'];
                $result = self::findEbetOrderByUsername($username,$stime,$etime);
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
        $gameType = C("gameType");
        $judgeResult = C("judgeResult");
        foreach($listpage as $item)
        {
            $temp = array();
            $temp['betHistoryId'] = $item['roundNo'];
            $namearr = explode("_",$item['username']);
            $temp['username'] = $namearr[1];
            $temp['betMoney'] = $item['betMoney'];
            $temp['validBet'] = $item['validBet'];
            $temp['createTime'] = date('m/d H:i:s',$item['createTime']);
            $temp['payoutTime'] = date('m/d H:i:s',$item['payoutTime']);
            $temp['payout'] = $item['payout'];
            $temp['gameType'] = $gameType[$item['gameType']];
            $betMap = $item['betMap'];
            $betType = "";
            $betMoney = 0;
            foreach($betMap as $bet)
            {
                $betType .= $judgeResult[$item['gameType']][$bet['betType']].",";
                $betMoney = $betMoney + $bet['betMoney'];
            }
            $temp['betType'] = substr($betType,0,strlen($betType)-1);
            $temp['betMoney'] = $betMoney;
            $temp['judgeResult'] = $judgeResult[$item['gameType']][$item['judgeResult'][0]];
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