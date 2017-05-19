<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午7:35
 */

namespace Home\Controller;

use Home\Model\AccountsChangeModel as AccountsChange;
class SelfChangeRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "selfRecord";
        $this->managermenu = 'report';
        $this->reportmanager = 'selfchangerecord';
        $this->accountType = C("ACCOUNTTYPE");
        $this->display();
    }

    public function getSelfChangeRecord()
    {
        $ordertype = I("post.ordertype");
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

        $where['username'] = session("SESSION_NAME");
        $where['accounts_type'] = (!empty($ordertype)) ? $ordertype : "";
        $where['change_time'] = array(array('gt',$stime),array('lt',$etime));
        foreach($where as $k=>$v)
        {
            if(!$v)
            {
                unset($where[$k]);
            }
        }
        $AccountsChange = new AccountsChange();
        $AccountsChange->data = array("where"=>$where);
        echo $AccountsChange->searchChangeAccount();
    }
}