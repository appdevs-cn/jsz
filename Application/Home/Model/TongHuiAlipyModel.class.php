<?php
/**
 * 通汇支付
 * User: yifanfengshun
 * Date: 2016/11/8
 * Time: 下午9:06
 */

namespace Home\Model;

use Home\Model\UserModel as User;
class TongHuiAlipyModel
{
    static private $_instance = null;

    const ONLINELOADMIN = 50;

    const ONLINELOADMAX = 500;

    private $_money = 0;

    public function __construct($money, $bank)
    {
        $this->_money = $money;
    }

    static function getInstance($money=0)
    {
        if(self::$_instance == null )
        {
            self::$_instance = new TongHuiAlipyModel($money);
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
        $P_Bank = "ZHIFUBAO";
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
        $onlinetool_rows=M('detail_onlinetool')->where(array("toolid"=>13,"status"=>1))->find();
        $data->MER_NO = $onlinetool_rows['clientID'];
        $data->CHARSET = "UTF-8";
        $data->BACK_NOTIFY_URL = "http://".$onlinetool_rows['url']."/th_alipy_server.php";
        $data->PAGE_NOTIFY_URL = "http://".$onlinetool_rows['url']."/th_alipy_respondret.php";
        $data->PAY_TYPE = 1;
        $data->REQ_REFERER = $onlinetool_rows['url'];
        $data->DATE_TIME_FORMAT = "Y-m-d H:i:s";
        $data->REQ_CUSTOMER_ID = NULL;
        $data->SUB_URL = $onlinetool_rows['url'];

        $onlinepayObj = M('onlinepay');
        $row = $onlinepayObj->where('submitdate=' . $datestr)->field('MAX(id) AS maxid')->find();
        $billfootstr = $row['maxid'] + 1;
        $billstr = date('His', time());
        $billnostr = str_pad($billfootstr, 5, '0', STR_PAD_LEFT) . $billstr . rand(1000,9999);
        $amount = number_format($amount, 2, '.', '');

        $data->bankCode = $P_Bank;
        $data->bankName = C("BANKTOCODE")[$P_Bank];
        $data->orderNo = $billnostr;
        $data->orderAmount = $amount;
        $data->productName = "一个Apple笔记本电脑配件";
        $data->productNum = 10;
        $data->referer = $data->REQ_REFERER;
        $data->customerIp = get_client_ip();
        $data->customerPhone = "18909091212";
        $data->receiveAddr = "上海市浦东新区浦东南路200号";
        $data->currentDate = date("Y-m-d H:i:s",time());
        $data->username = $username;
        $data->bank = $_POST["bank"];
        return $data;
    }

    /**
     * 跳转到第三方平台进行充值
     *
     * @return null
     */
    public function payOrder($obj)
    {
        $bankCode = $obj[AppConstants::$BANK_CODE];
        $orderNo = $obj[AppConstants::$ORDER_NO];
        $orderAmount = $obj[AppConstants::$ORDER_AMOUNT];
        $productName = $obj[AppConstants::$PRODUCT_NAME];
        $productNum = $obj[AppConstants::$PRODUCT_NUM];
        $referer = $obj[AppConstants::$REQ_REFERER];;
        $customerIp = get_client_ip();
        $customerPhone = $obj[AppConstants::$CUSTOMER_PHONE];
        $receiveAddr = $obj[AppConstants::$RECEIVE_ADDRESS];
        $returnParams = $obj[AppConstants::$RETURN_PARAMS];
        $currentDate = $obj[AppConstants::$ORDER_TIME];
        $CHARSET = $obj[AppConstants::$INPUT_CHARSET];
        $BACK_NOTIFY_URL = $obj[AppConstants::$NOTIFY_URL];
        $PAGE_NOTIFY_URL = $obj[AppConstants::$RETURN_URL];
        $PAY_TYPE = $obj[AppConstants::$PAY_TYPE];
        $MER_NO = $obj[AppConstants::$MERCHANT_CODE];
        $sub_url = $obj["sub_url"];

        if(empty($orderNo) || empty($MER_NO) || is_numeric($MER_NO) == false || empty($sub_url) || empty($orderAmount) || (int)$orderAmount< self::ONLINELOADMIN || empty($bankCode) || empty($orderAmount))
            exit();
        $datestr = date("Ymd");
        $username = session("SESSION_NAME");
        $User = new User();
        $uid = session("SESSION_ID");
        $userinfos = $User->where(array("id"=>$uid))->field("username,parent_id,parent_path")->find();
        $onlinepayObj = M('onlinepay');
        $userpay['id'] = NULL;
        $userpay['submitdate'] = strtotime($currentDate);
        $userpay['userName'] = addslashes($userinfos["username"]);
        $userpay['parentid'] = (int)$userinfos['parent_id'];
        $userpay['parent_path'] = $userinfos['parent_path'];
        $userpay['billno'] = addslashes($orderNo);
        $userpay['amount'] = ($orderAmount * 100000);
        $userpay['mercode'] = addslashes($MER_NO);
        $userpay['paytype'] = 13;
        $userpay['dateline'] = time();
        $insertid = $onlinepayObj->add($userpay);
        $returnParams = session("SESSION_ID")."|".  urlencode(session("SESSION_NAME"))."|".$insertid;

        $kvs = new KeyValues();
        $kvs->add(AppConstants::$INPUT_CHARSET, $CHARSET);
        $kvs->add(AppConstants::$NOTIFY_URL, $BACK_NOTIFY_URL);
        $kvs->add(AppConstants::$RETURN_URL, $PAGE_NOTIFY_URL);
        $kvs->add(AppConstants::$PAY_TYPE, $PAY_TYPE);
        $kvs->add(AppConstants::$BANK_CODE, $bankCode);
        $kvs->add(AppConstants::$MERCHANT_CODE, $MER_NO);
        $kvs->add(AppConstants::$ORDER_NO, $orderNo);
        $kvs->add(AppConstants::$ORDER_AMOUNT, $orderAmount);
        $kvs->add(AppConstants::$ORDER_TIME, $currentDate);
        $kvs->add(AppConstants::$PRODUCT_NAME, $productName);
        $kvs->add(AppConstants::$PRODUCT_NUM, $productNum);
        $kvs->add(AppConstants::$REQ_REFERER, $referer);
        $kvs->add(AppConstants::$CUSTOMER_IP, $customerIp);
        $kvs->add(AppConstants::$CUSTOMER_PHONE, $customerPhone);
        $kvs->add(AppConstants::$RECEIVE_ADDRESS, $receiveAddr);
        $kvs->add(AppConstants::$RETURN_PARAMS, $returnParams);
        $sign = $kvs->sign();
        $data = null;
        $data->CHARSET = $CHARSET;
        $data->BACK_NOTIFY_URL = $BACK_NOTIFY_URL;
        $data->PAGE_NOTIFY_URL = $PAGE_NOTIFY_URL;
        $data->PAY_TYPE = $PAY_TYPE;
        $data->bankCode = $bankCode;
        $data->MER_NO = $MER_NO;
        $data->orderNo = $orderNo;
        $data->orderAmount = $orderAmount;
        $data->currentDate = $currentDate;
        $data->productName = $productName;
        $data->productNum = $productNum;
        $data->referer = $referer;
        $data->customerIp = $customerIp;
        $data->customerPhone = $customerPhone;
        $data->receiveAddr = $receiveAddr;
        $data->returnParams = $returnParams;
        $data->sign = $sign;
        $data->sub_url = $sub_url;
        return $data;
    }
}

class AppConstants
{
    public static $INPUT_CHARSET = "input_charset";
    public static $NOTIFY_URL = "notify_url";
    public static $RETURN_URL = "return_url";
    public static $PAY_TYPE = "pay_type";
    public static $BANK_CODE = "bank_code";
    public static $MERCHANT_CODE = "merchant_code";
    public static $ORDER_NO = "order_no";
    public static $ORDER_AMOUNT = "order_amount";
    public static $ORDER_TIME = "order_time";
    public static $PRODUCT_NAME = "product_name";
    public static $PRODUCT_NUM = "product_num";
    public static $REQ_REFERER = "req_referer";
    public static $CUSTOMER_IP = "customer_ip";
    public static $CUSTOMER_PHONE = "customer_phone";
    public static $RECEIVE_ADDRESS = "receive_address";
    public static $RETURN_PARAMS = "return_params";

    public static $NOTIFY_TYPE = "notify_type";
    public static $TRADE_NO = "trade_no";
    public static $TRADE_TIME = "trade_time";
    public static $TRADE_STATUS = "trade_status";

    public static $KEY = "key";
    public static $SIGN = "sign";

}

class InputCharset
{
    public static $UTF8 = "UTF-8";
    public static $GBK = "GBK";
}

class URLUtils
{
    static function appendParam(& $sb, $name, $val, $and = true, $charset = null)
    {
        if ($and)
        {
            $sb .= "&";
        }
        else
        {
            $sb .= "?";
        }
        $sb .= $name;
        $sb .= "=";
        if (is_null($val))
        {
            $val = "";
        }
        if (is_null($charset))
        {
            $sb .= $val;
        }
        else
        {
            $sb .= urlencode($val);
        }
    }
}

class KeyValues
{
    private $kvs = array();

    function items()
    {
        return $this->kvs;
    }
    function add($k, $v)
    {
        if (!is_null($v))
            $this->kvs[$k] = $v;
    }
    function sign()
    {
        return md5($this->link());
    }
    function link()
    {
        $strb = "";
        ksort($this->kvs);
        foreach ($this->kvs as $key => $val)
        {
            URLUtils::appendParam($strb, $key, $val);
        }
        URLUtils::appendParam($strb, AppConstants::$KEY, C("THALIPYPPKEY"));
        $strb = substr($strb, 1, strlen($strb) - 1);
        return $strb;
    }
}