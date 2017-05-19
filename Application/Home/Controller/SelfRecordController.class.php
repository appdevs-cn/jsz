<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 上午11:37
 */

namespace Home\Controller;

use Home\Model\BuyRecordModel as BuyRecord;
class SelfRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "selfRecord";
        $this->managermenu = 'report';
        $this->reportmanager = 'selfrecord';
        $this->lottery = C("LOTTERY");
        $this->display();
    }

    public function selectSelfRecord()
    {
        $orderNumber = I("post.orderNumber");
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

        $where['userid'] = $uid;
        $where['serial_number'] = (!empty($orderNumber)) ? $orderNumber : "";
        $where['lottery_id'] = (!empty($lotteryName)) ? $lotteryName : "";
        if(!empty($status))
        {
            switch($status)
            {
                case 1:$where['status']=1;break;
                case 2:
                case 3:$where['status']=2;break;
                case 4:$where['status']=3;break;
            }
        }
        $where['buy_time'] = array(array('gt',$stime),array('lt',$etime));
        foreach($where as $k=>$v)
        {
            if(!$v)
            {
                unset($where[$k]);
            }
        }
        if(isset($where['status']))
        {
            $where['status'] = $where['status']-1;
        }
        $where["add_number"] = 0;
        $BuyRecord = new BuyRecord();
        $BuyRecord->data = array("where"=>$where,"st"=>$status);
        echo $BuyRecord->selfRecord();
    }

    /**
     * 订单详情
     */
    public function selfRecordDetail()
    {
        $buyid = I("post.id");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $id = $_xDe->decode($buyid);
        $BuyRecord = new BuyRecord();
        $BuyRecord->data = array("buyid"=>$id);
        echo $BuyRecord->detailRecord();
    }

    /**
     * 购买记录撤单
     */
    public function recordCancel()
    {
        $buyid = I("post.id");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $id = $_xDe->decode($buyid);
        $BuyRecord = new BuyRecord();
        $BuyRecord->data = array("buyid"=>$id);
        echo $BuyRecord->cancelOrder();
    }
}