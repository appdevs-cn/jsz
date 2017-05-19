<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午11:13
 */

namespace Home\Model;


use Think\Model;

class OnlinePayModel extends Model
{
    protected $trueTableName = 'onlinepay';

    public function searchOnlinePay()
    {
        $data = $this->data;
        $map = $data["data"]["where"];
        $type = $data['data']['type'];

        $_count = $this->where($map)->field("userName,billno,amount,dateline,suc")->count();
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
        $list = $this->where($map)->field("id,userName,billno,amount,dateline,suc")->limit($p->firstRow,$p->listRows)->order("id desc")->select();
        $result = array();
        $user_recharge_count = M("user_recharge_count");
        foreach($list as $item)
        {
            $drawInfo = $user_recharge_count->where(array("online_id"=>$item['id']))->field("beforeMoney,afterMoney")->find();
            if(empty($drawInfo))
            {
                $item['beforeMoney'] = '<i class="icon-spinner icon-spin"></i>';
                $item['afterMoney'] = '<i class="icon-spinner icon-spin"></i>';
            }
            else
            {
                $item['beforeMoney'] = sprintf("%.4f",$drawInfo['beforeMoney']/100000);
                $item['afterMoney'] = sprintf("%.4f",$drawInfo['afterMoney']/100000);
            }
            $item['amount'] = sprintf("%.4f",$item['amount']/100000);
            $item['dateline'] = date("m/d H:i:s",$item['dateline']);
            $item['suc'] = ($item['suc']==1) ? "充值成功" : "待支付";
            $item['page'] = $page;
            $item['typename'] = "充值";
            $item['type'] = $type;
            $result[] = $item;
        }
        return json_encode($result);
    }
}