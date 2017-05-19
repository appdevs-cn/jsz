<?php
/**
 * 微信支付
 * User: yifanfengshun
 * Date: 2016/11/8
 * Time: 下午9:06
 */

namespace Home\Model;

use Home\Model\UserModel as User;
class QqChartModel
{
    static private $_instance = null;

    const ONLINELOADMIN = 50;

    const ONLINELOADMAX = 5000;

    private $_money = 0;

    public function __construct($money)
    {
        $this->_money = $money;
    }

    static function getInstance($money=0)
    {
        if(self::$_instance == null )
        {
            self::$_instance = new QqChartModel($money);
        }
        return self::$_instance;
    }

    /**
     * 显示订单详情
     *
     * @return null
     */
    public function showOrder()
    {
        $data = null;
        $amount = $this->_money;

        if(empty($amount))
            return $data;
        
        if((int)$amount< self::ONLINELOADMIN || (int)$amount>self::ONLINELOADMAX)
            return $data;
        
        $p3_amt = iconv("GB2312", "UTF-8", trim($amount));
        $datestr = date("Ymd", time());
        if (is_numeric($p3_amt))
        {
            if (strpos($p3_amt, ".") !== false)
            {
                $temp_p3_amt = explode(".", $p3_amt);
                if (count($temp_p3_amt) > 2)
                {
                    return $data;
                }
                else
                {
                    $temp_str = $temp_p3_amt[1] . "00";
                    $subtemp = substr($temp_str, 0, 2);
                    $amount = $temp_p3_amt[0] . "." . $subtemp;
                }
            }
            else
            {
                $amount = $p3_amt . ".00";
            }
        }
        else
        {
            return $data;
        }


        $User = new User();
        $uid = session("SESSION_ID");
        $rows = $User->where(array("id"=>$uid))->field("username,parent_path")->find();
        $username = $rows['username'];
        if (empty($rows['parent_path']))
        {
            return $data;
        }
        $parentpath_arr = explode(",", $rows['parent_path']);
        if (count($parentpath_arr) >= 2)
        {
            $superdali = $parentpath_arr[1];
        }
        else
        {
            if (empty($parentpath_arr[0]))
            {
                return $data;
            }
            else
            {
                $superdali = $uid;
            }
        }

        $onlinepayObj = M('onlinepay');
        $row = $onlinepayObj->where('submitdate=' . $datestr)->field('MAX(id) AS maxid')->find();
        $billfootstr = $row['maxid'] + 1;
        $billstr = date('His', time());
        $billnostr = str_pad($billfootstr, 5, '0', STR_PAD_LEFT) . $billstr . rand(1000,9999);
        $amount = number_format($amount, 2, '.', '');

        $data->order_no = $billnostr;
        $data->order_time = date("Y-m-d H:i:s",time());
        $data->order_amount = $amount;

        $data->extend_param = "";

        $onlinetool_rows=M('detail_onlinetool')->where(array("toolid"=>14,"status"=>1))->find();
        $data->merchant_code = $onlinetool_rows['clientID'];
        $data->service_type = "tenpay_scan";
        $data->notify_url = "http://".$onlinetool_rows['url']."/wechart_server.php";
        $data->interface_version = "V3.1";
        $data->client_ip = get_client_ip();
        $data->sign_type = "RSA-S";

        $data->product_name = "一个Apple笔记本电脑配件";
        $data->product_code = rand(100000,999999);
        $data->product_num = 10;
        $data->product_desc = "一个Apple笔记本电脑配件";

        if(empty($data->order_no) || empty($data->order_amount)) exit();

        $uid = session("SESSION_ID");
        $User = new User();
        $userinfos = $User->where(array("id"=>$uid))->field("username,parent_id,parent_path")->find();
        $onlinepayObj = M('onlinepay');
        $userpay['id'] = NULL;
        $userpay['submitdate'] = time();
        $userpay['userName'] = addslashes($userinfos["username"]);
        $userpay['parentid'] = (int)$userinfos['parent_id'];
        $userpay['parent_path'] = $userinfos['parent_path'];
        $userpay['billno'] = addslashes($billnostr);
        $userpay['amount'] = ($amount * 100000);
        $userpay['mercode'] = addslashes($onlinetool_rows['clientID']);
        $userpay['paytype'] = 14;
        $userpay['dateline'] = time();
        $insertid = $onlinepayObj->add($userpay);
        $returnParams = session("SESSION_ID")."|".  urlencode(session("SESSION_NAME"))."|".$insertid;
        $data->extra_return_param = $returnParams;


        $signStr = "";
	
        $signStr = $signStr."client_ip=".$data->client_ip."&";

        if($data->extend_param != ""){
            $signStr = $signStr."extend_param=".$data->extend_param."&";
        }
        
        if($data->extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$data->extra_return_param."&";
        }
        
        $signStr = $signStr."interface_version=".$data->interface_version."&";	
        
        $signStr = $signStr."merchant_code=".$data->merchant_code."&";	
        
        $signStr = $signStr."notify_url=".$data->notify_url."&";		
        
        $signStr = $signStr."order_amount=".$data->order_amount."&";		
        
        $signStr = $signStr."order_no=".$data->order_no."&";		
        
        $signStr = $signStr."order_time=".$data->order_time."&";	

        if($data->product_code != ""){
            $signStr = $signStr."product_code=".$data->product_code."&";
        }	
        
        if($data->product_desc != ""){
            $signStr = $signStr."product_desc=".$data->product_desc."&";
        }
        
        $signStr = $signStr."product_name=".$data->product_name."&";

        if($data->product_num != ""){
            $signStr = $signStr."product_num=".$data->product_num."&";
        }	
        
        $signStr = $signStr."service_type=".$data->service_type;

        /////////////////////////////   RSA-S签名  /////////////////////////////////
        $merchant_private_key = C("ZHFPROVATE");
        $merchant_private_key= openssl_get_privatekey($merchant_private_key);
        openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
        $sign = base64_encode($sign_info);
        $data->sign = $sign;

        /////////////////////////  提交参数到智汇付网关  ////////////////////////
        $postdata=array('extend_param'=>$data->extend_param,
                        'extra_return_param'=>$data->extra_return_param,
                        'product_code'=>$data->product_code,
                        'product_desc'=>$data->product_desc,
                        'product_num'=>$data->product_num,
                        'merchant_code'=>$data->merchant_code,
                        'service_type'=>$data->service_type,
                        'notify_url'=>$data->notify_url,
                        'interface_version'=>$data->interface_version,
                        'sign_type'=>$data->sign_type,
                        'order_no'=>$data->order_no,
                        'client_ip'=>$data->client_ip,
                        'sign'=>$data->sign,
                        'order_time'=>$data->order_time,
                        'order_amount'=>$data->order_amount,
                        'product_name'=>$data->product_name);
         $ch = curl_init();	
        curl_setopt($ch,CURLOPT_URL,"https://api.zhihpay.com/gateway/api/scanpay");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response=curl_exec($ch);
        $res=simplexml_load_string($response);
        curl_close($ch);

        /////////////////////////////   获取qrcode，并生成二维码  /////////////////////
        Vendor('Rcode.phpqrcode');
        $resp_code=$res->response->resp_code;
        if($resp_code=="SUCCESS"){
            $time = time();
            $qrcode=$res->response->qrcode;
            if(file_exists('/home/www/www.jsz.com/Jsz/Resourse/Home/images/zhf/'.$time.session("SESSION_ID").'.png')){							
                unlink('/home/www/www.jsz.com/Jsz/Resourse/Home/images/zhf/'.$time.session("SESSION_ID").'.png');
            }
            $pic="/home/www/www.jsz.com/Jsz/Resourse/Home/images/zhf/".$time.session("SESSION_ID").".png";
            $errorCorrectionLevel = 'L';
            $matrixPointSize = 10;
            \QRcode::png ( $qrcode, $pic, $errorCorrectionLevel, $matrixPointSize, 2 );
            return "/Resourse/Home/images/zhf/".$time.session("SESSION_ID").".png";
        }
    }
}
