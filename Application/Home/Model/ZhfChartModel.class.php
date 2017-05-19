<?php
/**
 * 微信支付
 * User: yifanfengshun
 * Date: 2016/11/8
 * Time: 下午9:06
 */

namespace Home\Model;

use Home\Model\UserModel as User;
class ZhfChartModel
{
    static private $_instance = null;

    const ONLINELOADMIN = 100;

    private $_money = 0;
    private $_bank = '';

    public function __construct($money, $bank)
    {
        $this->_money = $money;
        $this->_bank = $bank;
    }

    static function getInstance($money=0, $bank='')
    {
        if(self::$_instance == null )
        {
            self::$_instance = new ZhfChartModel($money, $bank);
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
        $P_Bank = (empty($this->_bank)) ? "ABCD" : $this->_bank;
        $amount = $this->_money;
        if(empty($amount))
            return $data;
        if((int)$amount< self::ONLINELOADMIN)
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

        $data->bankName = C("ZHFBANKTOCODE")[$P_Bank];
        $data->orderNo = $billnostr;
        $data->orderAmount = $amount;
        $data->bank = $P_Bank;
        return $data;
    }

    /**
     * 跳转到第三方平台进行充值
     *
     * @return null
     */
    public function payOrder($obj)
    {
        $data = null;
        $amount = I("post.order_amount");

        if(empty($amount))
            return $data;
        if((int)$amount< self::ONLINELOADMIN)
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


        $data->order_no = I("post.order_no");
        $data->order_time = date("Y-m-d H:i:s",time());
        $data->order_amount = $amount;
        $data->bank_code = I("post.bank");

        $data->extend_param = "";

        $onlinetool_rows=M('detail_onlinetool')->where(array("toolid"=>12,"status"=>1))->find();
        $data->sub_url = $onlinetool_rows['url'];
        $data->merchant_code = $onlinetool_rows['clientID'];
        $data->service_type = "direct_pay";
        $data->notify_url = "http://".$onlinetool_rows['url']."/wechart_server.php";
        $data->interface_version = "V3.0";
        $data->client_ip = get_client_ip();
        $data->sign_type = "RSA-S";
        $data->input_charset = "UTF-8";

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
        $userpay['billno'] = addslashes($data->order_no);
        $userpay['amount'] = ($amount * 100000);
        $userpay['mercode'] = addslashes($onlinetool_rows['clientID']);
        $userpay['paytype'] = 12;
        $userpay['dateline'] = time();
        $insertid = $onlinepayObj->add($userpay);
        $returnParams = session("SESSION_ID")."|".  urlencode(session("SESSION_NAME"))."|".$insertid;
        $data->extra_return_param = $returnParams;
        $data->pay_type = "b2c";
        $data->redo_flag = 1;
        $data->return_url="";
        $data->show_url="";

        $signStr= "";
	
        if($data->bank_code != ""){
            $signStr = $signStr."bank_code=".$data->bank_code."&";
        }
        if($data->client_ip != ""){
            $signStr = $signStr."client_ip=".$data->client_ip."&";
        }
        if($data->extend_param != ""){
            $signStr = $signStr."extend_param=".$data->extend_param."&";
        }
        if($data->extra_return_param != ""){
            $signStr = $signStr."extra_return_param=".$data->extra_return_param."&";
        }
        
        $signStr = $signStr."input_charset=".$data->input_charset."&";	
        $signStr = $signStr."interface_version=".$data->interface_version."&";	
        $signStr = $signStr."merchant_code=".$data->merchant_code."&";	
        $signStr = $signStr."notify_url=".$data->notify_url."&";		
        $signStr = $signStr."order_amount=".$data->order_amount."&";		
        $signStr = $signStr."order_no=".$data->order_no."&";		
        $signStr = $signStr."order_time=".$data->order_time."&";	

        if($data->pay_type != ""){
            $signStr = $signStr."pay_type=".$data->pay_type."&";
        }

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
        if($data->redo_flag != ""){
            $signStr = $signStr."redo_flag=".$data->redo_flag."&";
        }
        if($data->return_url != ""){
            $signStr = $signStr."return_url=".$data->return_url."&";
        }		
        
        $signStr = $signStr."service_type=".$data->service_type;

        if($data->show_url != ""){	
            
            $signStr = $signStr."&show_url=".$data->show_url;
        }

        /////////////////////////////   获取sign值（RSA-S加密）  /////////////////////////////////
        $merchant_private_key = C("ZHFPROVATE");
        $merchant_private_key= openssl_get_privatekey($merchant_private_key);
	
        openssl_sign($signStr,$sign_info,$merchant_private_key,OPENSSL_ALGO_MD5);
        $data->sign = base64_encode($sign_info);
        return $data;
    }
}
