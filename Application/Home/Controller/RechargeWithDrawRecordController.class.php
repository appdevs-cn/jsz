<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午9:55
 */

namespace Home\Controller;

use Home\Model\UserDrawModel as UserDraw;
use Home\Model\OnlinePayModel as OnlinePay;
class RechargeWithDrawRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "proxy";
        $this->blanceDetail = 'blanceDetail';
        $this->proxymanager = 'rechargewithdrawrecord';
        $this->display();
    }

    public function getRechargeWithdrawRecord()
    {
        $username = I("post.username");
        $orderNumber = I("post.orderNumber");
        $type = I("post.type");
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
        $where['_string'] = "FIND_IN_SET(".$uid.",parent_path)";
        if($type==1)
        {
            $where['userName'] = (!empty($username)) ? $username : "";
            $where['billno'] = (!empty($orderNumber)) ? $orderNumber : "";
            $where['dateline'] = array(array('gt',$stime),array('lt',$etime));
            foreach($where as $k=>$v)
            {
                if(!$v)
                {
                    unset($where[$k]);
                }
            }
            $OnlinePay = new OnlinePay();
            $OnlinePay->data = array("where"=>$where,'type'=>$type);
            echo $OnlinePay->searchOnlinePay();
        }
        else if($type==2)
        {
            import("Class.XDeode");
            $_xDe=new \XDeode();
            $User = M("user");
            $where['id'] = (!empty($orderNumber)) ? $_xDe->decode($orderNumber) : "";
            $where['userid'] = (!empty($username)) ? $User->where(array("username"=>$username))->getField("id") : "";
            $where['dateline'] = array(array('gt',$stime),array('lt',$etime));
            if($status==1)
            {
                $where['state']=1;
                $where['checkState']=1;
            }
            if($status==2)
            {
                $where['state']=2;
            }
            if($status==3)
            {
                $where['state']=1;
                $where['checkState']=3;
            }
            if($status==4)
            {
                $where['state']=3;
            }
            foreach($where as $k=>$v)
            {
                if(!$v)
                {
                    unset($where[$k]);
                }
            }
            if(isset($where['state']))
            {
                $where['state'] = $where['state']-1;
            }
            if(isset($where['checkState']))
            {
                $where['checkState'] = $where['checkState']-1;
            }
            $UserDraw = new UserDraw();
            $UserDraw->data = array("where"=>$where,'type'=>$type);
            echo $UserDraw->searchWithDraw();
        }
    }
}