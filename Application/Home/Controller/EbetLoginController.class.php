<?php
namespace Home\Controller;

use Think\Controller;

class EbetLoginController extends Controller
{
    public function login()
    {
        if(!IS_POST)
        {
            echo "此连接已过期";exit();
        }
        $post = file_get_contents('php://input');
        $obj = json_decode($post);
        $cmd = $obj->{'cmd'};
        if($cmd == "RegisterOrLoginReq")
        {
            $eventType = $obj->{'eventType'};
            $channelId = $obj->{'channelId'};
            $usernametemp = $obj->{'username'};

            $usernametemp = strtolower($usernametemp);
            $pwd = $obj->{'password'};
            $signature = $obj->{'signature'};
            $timestamp = $obj->{'timestamp'};
            if ($eventType == "1"){
                // 验证签名
                $plaintext = $usernametemp.$timestamp;
                $sign = self::sign($plaintext);
                if($sign!=$signature)
                {
                    header('Content-type: application/json');
                    echo json_encode(array("status"=>4026));
                    exit();
                }
                // 验证用户名和密码是否正确
                $userInfo = M("ebet_user")->where(array("ebet_username"=>$usernametemp))->find();
                if(empty($userInfo))
                {
                    header('Content-type: application/json');
                    echo json_encode(array("status"=>401));
                    exit();
                }
                $password = md5(md5($pwd . C("PASSWORD_HALT")) . $userInfo['safe_key']);
                if($userInfo["ebet_password"] != $password)
                {
                    header('Content-type: application/json');
                    echo json_encode(array("status"=>401));
                    exit();
                }
                else
                {
                    // 生成一个用户token
                    $basetoken = md5($userInfo['ebet_username'].$userInfo["ebet_password"].time());
                    $expireTime = time()+86400*7;
                    $token = md5($basetoken.$userInfo['id'].$expireTime);
                    $token = base64_encode($token.'|'.$userInfo['id'].'|'.$expireTime);
                    $tokenArray = array('accessToken'=>$token,'uid'=>$userInfo['id'],'expireTime'=>$expireTime);
                    $res = M("ebet_user")->where(array("ebet_username"=>$usernametemp))->find();
                    if(!empty($res))
                    {
                        M("ebet_user")->where(array("ebet_username"=>$usernametemp))->save(array("ebet_token"=>json_encode($tokenArray)));
                        $response = array('status'=>200, 'subChannelId' => 0, "accessToken" => $tokenArray['accessToken'],'username'=> $usernametemp);
                        header('Content-type: application/json');
                        echo json_encode($response);
                    }
                    else
                    {
                        header('Content-type: application/json');
                        echo json_encode(array("status"=>410));
                        exit();
                    }
                }
            }
            else if($eventType == "4")
            {
                $res = M("ebet_user")->where(array("ebet_username"=>$usernametemp))->find();
                if(empty($res['ebet_token']))
                {
                    header('Content-type: application/json');
                    echo json_encode(array("status"=>4026));
                    exit();
                }
                $tokenres = json_decode($res['ebet_token'], true);
                $token = $tokenres['accessToken'];


                // 验证签名
                $plaintext = $timestamp.$token;
                $sign = self::sign($plaintext);
                if($sign!=$signature)
                {
                    header('Content-type: application/json');
                    echo json_encode(array("status"=>505));
                    exit();
                }

                $accessToken = $obj->{'accessToken'};

                if(rtrim($accessToken)!=rtrim($token))
                {
                    header('Content-type: application/json');
                    echo json_encode(array("status"=>410));
                    exit();
                }
                else
                {
                    // 生成一个新的用户token
                    $basetoken = md5($userInfo['ebet_username'].$userInfo["ebet_password"].time());
                    $expireTime = time()+86400*7;
                    $token = md5($basetoken.$userInfo['id'].$expireTime);
                    $token = base64_encode($token.'|'.$userInfo['id'].'|'.$expireTime);
                    $tokenArray = array('accessToken'=>$token,'uid'=>$userInfo['id'],'expireTime'=>$expireTime);
                    $res = M("ebet_user")->where(array("ebet_username"=>$usernametemp))->find();
                    if(!empty($res))
                    {
                        M("ebet_user")->where(array("ebet_username"=>$usernametemp))->save(array("ebet_token"=>json_encode($tokenArray)));
                        $response = array('status'=>200, 'subChannelId' => 0, "accessToken" => $tokenArray['accessToken'],'username'=> $usernametemp);
                        header('Content-type: application/json');
                        echo json_encode($response);
                    }
                    else
                    {
                        header('Content-type: application/json');
                        echo json_encode(array("status"=>410));
                        exit();
                    }
                }
            }
        }
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