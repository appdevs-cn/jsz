<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class TransferController extends CommonController
{
    public function index()
    {
        $this->reportmanager = "transfer";
        echo "111";
        $this->display();
    }

    // 查询转账明细
    public function searchTransfer()
    {
        $map['username'] = session("SESSION_NAME");

        $isThird = I("post.isThird");
        if(!empty($isThird))
        {
            $map['isThird'] = $isThird;
        }

        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(3, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordStartTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y")));
            $accordEndTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y")));
        } else {
            $accordStartTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y")));
            $accordEndTime = date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y")));
        }
        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? $starttime." 3:0:0" : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? $endtime." 3:0:0" : $accordEndTime;

        $map['time'] = array(array("egt",$stime),array('lt',$etime),'and');

        $transferRecord = M("transfer_record");

        $_count = $transferRecord->where($map)->count();
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
        $lottery = C("LOTTERY");
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $list = $transferRecord->where($map)->limit($p->firstRow,$p->listRows)->order("id desc")->select();

        $xReturn = array();
        $isThird = array(0=>"平台内转账",1=>"MG",2=>"AG",3=>"PT",4=>"分红钱包",5=>"系统转账",6=>"EBET");
        foreach($list as $item)
        {
            $temp = array();
            $temp['username'] = $item['username'];
            $temp['beforeAmount'] = sprintf("%.4f",$item['beforeAmount']/100000);
            $temp['amount'] = sprintf("%.4f",$item['amount']/100000);
            $temp['afterAmount'] = sprintf("%.4f",$item['afterAmount']/100000);
            $temp['type'] = $item['type'];
            $temp['remark'] = $item['remark'];
            $temp['time'] = date('m/d H:i:s',$item['time']);
            $temp['status'] = ($item['status']==1) ? "成功" : "失败";
            $temp['isThird'] = $isThird[$item['isThird']];
            $temp['thirdOrder'] = (!empty($item['thirdOrder'])) ? $item['thirdOrder'] : "";
            $temp['page'] = $page;
            $xReturn[] = $temp;
        }
        echo json_encode($xReturn);
    }
}


?>