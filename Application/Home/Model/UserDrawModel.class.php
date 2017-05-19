<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午11:00
 */

namespace Home\Model;


use Think\Model;

class UserDrawModel extends Model
{
    protected $trueTableName = 'user_draw';

    public function searchWithDraw()
    {
        $data = $this->data;
        $map = $data["data"]["where"];
        $type = $data['data']['type'];
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_count = $this->where($map)->field("id,userid,appMoney,factMoney,charge,dateline,state,checkState,otherState")->count();
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
        $list = $this->where($map)->field("id,userid,appMoney,factMoney,charge,dateline,state,checkState,otherState")->limit($p->firstRow,$p->listRows)->order("id desc")->select();
        $result = array();
        $User = M("user");
        $user_recharge_count = M("user_recharge_count");
        foreach($list as $item)
        {
            $item['username'] = $User->where(array("id"=>$item['userid']))->getField("username");
            $drawInfo = $user_recharge_count->where(array("user_draw_id"=>$item['id']))->field("beforeMoney,afterMoney")->find();
            $item['appMoney'] = sprintf("%.4f",$item['appMoney']/100000);
            $item['charge'] = (!empty($item['charge'])) ? sprintf("%.4f",$item['charge']/100000) : '<i class="icon-spinner icon-spin"></i>';
            $item['factMoney'] = (!empty($item['factMoney'])) ? sprintf("%.4f",$item['factMoney']/100000) : '<i class="icon-spinner icon-spin"></i>';
            $item['dateline'] = date("m/d H:i:s",$item['dateline']);
            $item['typename'] = "提款";
            $item['type'] = $type;
            $item['page'] = $page;
            $item['beforeMoney'] = sprintf("%.4f",$drawInfo['beforeMoney']/100000);
            $item['afterMoney'] = sprintf("%.4f",$drawInfo['afterMoney']/100000);
            if($item['state']==0 && $item['checkState']==0)
            {
                $item['status'] = "已提交";
            }
            else if($item['state']==1)
            {
                $item['status'] = "已完成";
            }
            else if($item['state']==0 && $item['checkState']==1)
            {
                $item['status'] = "处理中";
            }
            else if($item['state']==2)
            {
                $item['status'] = $item['otherState'];
            }
            $item['ordernumber'] = $_xDe->encode($item['id']);
            $result[] = $item;
        }
        return json_encode($result);
    }
}