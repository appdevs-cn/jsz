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
class SelfRechargeWithDrawRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "selfRecord";
        $this->managermenu = 'proxy';
        $this->reportmanager = 'selfrechargewithdrawrecord';
        $this->display();
    }

    public function getRechargeWithdrawRecord()
    {
        $username = session("SESSION_NAME");
        $orderNumber = I("post.orderNumber");
        $type = I("post.type");
        $status = I("post.status");
        $rechargeType = I("post.rechargeType");
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
        if($type==1)
        {
            if($rechargeType==1)
            {
                $where['userName'] = (!empty($username)) ? $username : "";
                $where['billno'] = (!empty($orderNumber)) ? $orderNumber : "";
                $where['dateline'] = array(array('gt',$stime),array('lt',$etime));
                $where['suc'] = $status;
                foreach($where as $k=>$v)
                {
                    if($v=="")
                    {
                        unset($where[$k]);
                    }
                }
                $OnlinePay = new OnlinePay();
                $OnlinePay->data = array("where"=>$where,'type'=>$type);
                echo $OnlinePay->searchOnlinePay();
            }
            else if($rechargeType==2)
            {
                import("Class.XDeode");
                $_xDe=new \XDeode();
                if(!empty($username))
                {
                    $map["username"] = $username;
                }

                if(!empty($orderNumber))
                {
                    $map["id"] = $_xDe->decode($orderNumber);
                }

                $fuyan = I("post.fuyan");
                if(!empty($fuyan))
                {
                    $map["fuyan"] = $fuyan;
                }

                if($status!="")
                {
                    $map['receive'] = $status;
                }

                $map['submit_time'] = array(array("egt",$stime),array("lt",$etime),'and');

                $_count = M("recharge_bank")->where($map)->count();
                import("Class.Page");
                //分页循环变量
                $listvar = 'list';
                //每页显示的数据量
                $listRows = C("LISTROWS");
                $roolPage = C("ROOLPAGE");
                $url = "";
                //获取数据总数
                $totalRows = $_count;
                $p=new \Page($totalRows, $listRows, http_build_query($map),$url);

                //分页栏每页显示的页数
                $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
                $pages = C('PAGE');
                //可以使用该方法前用C临时改变配置
                foreach ($pages as $key => $value) {
                    $p->setConfig($key, $value);
                }
                //分页显示
                $page = $p->show();
                $list = M("recharge_bank")->where($map)->limit($p->firstRow,$p->listRows)->order("id desc")->select();
                $xReturn = array();
                foreach($list as $item)
                {
                    $temp = array();
                    $temp['ordernum'] = $_xDe->encode($item['id']);
                    $temp['fuyan'] = $item['fuyan'];
                    $temp['username'] = $item['username'];
                    $temp['money'] = sprintf("%.4f",$item['money']/100000);
                    $temp['submit_time'] = date("Y-m-d H:i:s",$item['submit_time']);
                    $temp['bank_name'] = $item['bank_name'];
                    if($item['receive']==2)
                    {
                        $temp['fukuanname'] = '<i class="icon-spinner icon-spin"></i>';
                        $temp['dateline'] = '<i class="icon-spinner icon-spin"></i>';
                        $temp['status'] = '待支付';
                    }
                    else if($item['receive']==1)
                    {
                        $temp['fukuanname'] = $item['fukuanname'];
                        $temp['dateline'] = date("Y-m-d H:i:s",$item['dateline']);
                        $temp['status'] = '已到账';
                    }
                    else if($item['receive']==0)
                    {
                        $temp['fukuanname'] = $item['fukuanname'];
                        $temp['dateline'] = date("Y-m-d H:i:s",$item['dateline']);
                        $temp['status'] = '支付失败';
                    }
                    $temp['page'] = $page;
                    $xReturn[] = $temp;
                }
                echo json_encode($xReturn);
            }
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
                if($v=="")
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