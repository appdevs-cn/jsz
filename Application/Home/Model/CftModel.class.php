<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/9
 * Time: 上午9:47
 */

namespace Home\Model;


class CftModel
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
            self::$_instance = new CftModel($money);
        }
        return self::$_instance;
    }

    /**
     * 支付宝订单详情
     *
     * @return null
     */
    public function showOrder()
    {
        $data = null;
        $real_money = $this->_money;
        if(empty($real_money))
            return $data;
        if((int)$real_money< self::ONLINELOADMIN || (int)$real_money>self::ONLINELOADMAX)
            return $data;
        $real_money = iconv("GB2312", "UTF-8", trim($real_money));
        if (is_numeric($real_money))
        {
            if (strpos($real_money, ".") !== false)
            {
                $temp_p3_amt = explode(".", $real_money);
                if (count($temp_p3_amt) > 2)
                {
                    return $data;
                }
                else
                {
                    $temp_str = $temp_p3_amt[1] . "00";
                    $subtemp = substr($temp_str, 0, 2);
                    $real_money = $temp_p3_amt[0] . "." . $subtemp;
                }
            }
            else
            {
                $real_money = $real_money . ".00";
            }
        }
        else
        {
            return $data;
        }
        $alipay_config_res = M("alipay_config")->where(array("status"=>1))->find();
        $arr = explode("-", $alipay_config_res["account"]);
        $fuyan = time();
        $datainfo["userid"] = session("SESSION_ID");
        $datainfo["parent_path"] = session("SESSION_PATH");
        $datainfo["username"] = session("SESSION_NAME");
        $datainfo["money"] = $real_money*100000;
        $datainfo["submit_time"] = time();
        $datainfo["alipay_account"] = $arr[0];
        $datainfo["ip"] = sprintf("%u", ip2long(get_client_ip()));
        $datainfo["dateline"] = time();
        $datainfo["receive"] = 2;
        $datainfo["fuyan"] = $fuyan;
        $is = M("recharge_alipay")->add($datainfo);
        if($is){
            $data->account = $arr[0];
            $data->accountname = $arr[1];
            $data->real_money = $real_money;
            $data->fy = $fuyan;
            return $data;
        }
        else
        {
            return $data;
        }
    }
}