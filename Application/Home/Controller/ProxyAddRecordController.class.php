<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 上午11:37
 */

namespace Home\Controller;

use Home\Model\BuyAddRecordModel as BuyAddRecord;
class ProxyAddRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxy = "record";
        $this->proxymanager = 'proxyaddrecord';
        $this->lottery = C("LOTTERY");
        $this->display();
    }

    public function selectProxyAddRecord()
    {
        $username = I("post.username");
        $orderNumber = I("post.orderNumber");
        $serialNumber = I("post.serialNumber");
        $lotteryName = I("post.lotteryName");
        $status = I("post.status");
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        //如果是 那么统计时间是前一天的03：00：00 到 今天的 02：59：59
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            //统计开始时间  时间戳
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            //统计结束时间  时间戳
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            //如果不是 那么统计时间是今天的03：00：00 到 明天的 02：59：59
            //统计开始时间  时间戳

            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            //统计结束时间  时间戳
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }

        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? strtotime($starttime." 3:0:0") : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? strtotime($endtime." 3:0:0") : $accordEndTime;
        $uid = session("SESSION_ID");

        $lotteryNumberMid = M("lottery_number_mid");
        if(!empty($serialNumber) && !empty($lotteryName))
        {
            $where['lottery_number_id'] = $lotteryNumberMid->where(array("series_number"=>$serialNumber,"lottery_id"=>$lotteryName))->getField("id");
        }
        $User = M("user");
        $where['userid'] = (!empty($username)) ? $User->where(array("username"=>$username))->getField("id") : "";
        $where['serial_number'] = (!empty($orderNumber)) ? $orderNumber : "";
        $where['lottery_id'] = (!empty($lotteryName)) ? $lotteryName : "";
        $where['buy_time'] = array(array('gt',$stime),array('lt',$etime));
        $where['_string'] = "FIND_IN_SET(".$uid.",parent_path)";
        foreach($where as $k=>$v)
        {
            if(!$v)
            {
                unset($where[$k]);
            }
        }
        $where["add_number"] = array("gt",0);
        $BuyAddRecord = new BuyAddRecord();
        $BuyAddRecord->data = array("where"=>$where,"st"=>$status);
        echo $BuyAddRecord->proxyRecord();
    }

    /**
     * 订单详情
     */
    public function addRecordDetail()
    {
        $buyid = I("post.id");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $id = $_xDe->decode($buyid);
        $BuyAddRecord = new BuyAddRecord();
        $BuyAddRecord->data = array("buyid"=>$id);
        echo $BuyAddRecord->detailRecord();
    }
}