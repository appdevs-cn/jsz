<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午8:02
 */

namespace Home\Model;


use Think\Model;

class AccountsChangeModel extends Model
{
    protected $trueTableName = 'accounts_change';

    public function searchChangeAccount()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $_count = $this->where($map)->field("userid,achange_num,username,change_time,accounts_type,change_amount,cur_account,remark")->count();
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
        $list = $this->where($map)->field("userid,achange_num,username,change_time,accounts_type,change_amount,cur_account,wallet_account,remark")->limit($p->firstRow,$p->listRows)->order("id desc")->select();
        $result = array();
        $user_draw = M("user_draw");
        $changeAmount = 0;
        $ACCOUNTTYPE = C("ACCOUNTTYPE");
        foreach($list as $val)
        {
            //如果提款帐变 改变描述
            if($val["accounts_type"]==2){
                $status = $user_draw->where(array("userid"=>$val["userid"],"dateline"=>$val["change_time"]))->getField("state");
                if($status==0){
                    $val["remark"] = '<a href="javascript:;" data-toggle="tooltip" title="提款处理中" data-placement="right">提款处理中</a>';
                } else if($status==1){
                    $val["remark"] = '<a href="javascript:;" data-toggle="tooltip" title="提款已处理" data-placement="right">提款已处理</a>';
                } else if($status==2){
                    $val["remark"] = '<a href="javascript:;" data-toggle="tooltip" title="提款账户有误" data-placement="right">提款账户有误</a>';
                }
            }
            else
            {
                $val["remark"] = $val["remark"];
            }
            $changeAmount = $changeAmount+$val["change_amount"];
            if(in_array($val["accounts_type"],array(19,3,4,5,13,8,24,16,22,28,10,25,26,29,31,32,11,35,37,39,41,43,45,46,48,50))) {
                //收入
                if($val["change_amount"]!=0) {
                    if(!in_array($val["accounts_type"],array(35,48,50)))
                    {
                        $val["dec"] = "--";
                        $val["add"] = "<font color='green'>+" . sprintf("%.4f", $val["change_amount"] / 100000).'</font>';
                        $val["beforemoney"] = sprintf("%.4f",($val["cur_account"]-$val["change_amount"])/100000);
                        $val["walletbeforemoney"] = sprintf("%.4f",$val["wallet_account"]/100000);
                    }
                    else
                    {
                        $val["dec"] = "--";
                        $val["add"] = "<font color='green'>+" . sprintf("%.4f", $val["change_amount"] / 100000).'</font>';
                        $val["walletbeforemoney"] = sprintf("%.4f",($val["wallet_account"]-$val["change_amount"])/100000);
                        $val["beforemoney"] = sprintf("%.4f",$val["cur_account"]/100000);
                    }
                    
                } else {
                    $val["add"] = "--";
                }
            }
            if(in_array($val["accounts_type"],array(1,2,9,23,12,14,15,20,21,6,30,34,36,38,40,42,44,47,49))) {
                //支出
                if($val["change_amount"]!=0) {
                    if(!in_array($val["accounts_type"],array(36,47,49)))
                    {
                        $val["add"] = "--";
                        $val["dec"] = "<font color='red'>-" . sprintf("%.4f", $val["change_amount"] / 100000).'</font>';
                        $val["beforemoney"] = sprintf("%.4f",($val["cur_account"]+$val["change_amount"])/100000);
                        $val["walletbeforemoney"] = sprintf("%.4f",$val["wallet_account"]/100000);
                    }
                    else
                    {
                        $val["add"] = "--";
                        $val["dec"] = "<font color='red'>-" . sprintf("%.4f", $val["change_amount"] / 100000).'</font>';
                        $val["walletbeforemoney"] = sprintf("%.4f",($val["wallet_account"]+$val["change_amount"])/100000);
                        $val["beforemoney"] = sprintf("%.4f",$val["cur_account"]/100000);
                    }
                } else {
                    $val["dec"] = "--";
                }
            }
            $val["accounts_type"] = $ACCOUNTTYPE[$val["accounts_type"]];

            $val["cur_account"] = sprintf("%.4f",$val["cur_account"]/100000);

            $val["wallet_account"] = sprintf("%.4f",$val["wallet_account"]/100000);

            $val['change_time'] = date("m/d H:i:s",$val['change_time']);
            $val['page'] = $page;
            $result[] = $val;
        }
        return json_encode($result);
    }
}