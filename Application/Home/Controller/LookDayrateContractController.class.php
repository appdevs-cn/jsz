<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class LookDayrateContractController extends CommonController
{
    public function index()
    {
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxymanager = "lookcontract";
        $this->contractmanager = "lookdayrate";
        $this->display();
    }

    // 查看日工资数据
    public function SearchUserDayrate()
    {
        $uid = session("SESSION_ID");
        $username = I("post.username");
        if(!empty($username))
        {
            $map['username'] = $username;
        }

        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            //统计开始时间  时间戳
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            //统计结束时间  时间戳
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }

        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? strtotime($starttime." 3:0:0") : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? strtotime($endtime." 3:0:0") : $accordEndTime;

        $map['date'] = array(array('egt',$stime),array('elt',$etime),'and');
        $map['_string'] = " FIND_IN_SET(".$uid.",parent_path) or userid=".$uid;

        $_count = M("dayrateinfo")->where($map)->count();
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
        $list = M("dayrateinfo")->where($map)->limit($p->firstRow,$p->listRows)->order("userid asc")->select();
        
        import("Class.XDeode");
        $_xDe=new \XDeode();

        $xReturn = array();
        foreach($list as $item)
        {
            $temp = array();
            $temp['username'] = $item['username'];
            $temp['touzhu'] = abs(sprintf("%.4f",$item['touzhu']/100000));
            $temp['bonus'] = abs(sprintf("%.4f",$item['bonus']/100000));
            $temp['date'] = date("Y/m/d",$item['date']);
            $temp['status'] = ($item['status']==1) ? "已派发" : "未派发";
            if($item['status']==0)
                $temp['operate'] = '<a style="cursor:pointer" class="c--f35c19 ml--10" pd="'.$_xDe->encode($item['id']).'" data-method="paifa_datarate">派发日工资</a>';
            else
                $temp['operate'] = '--';
            $temp['page'] = $page;
            $xReturn[] = $temp;
        }
        echo json_encode($xReturn);
    }

    // 派发分红
    public function PaifaUserDatarate()
    {
        $id = I("post.id");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $id = $_xDe->decode($id);

        $datarateinfo = M("dayrateinfo")->where(array("id"=>$id,"status"=>0))->find();

        $bonus = abs($datarateinfo['bonus']);

        // 查询该用户的分红钱包
        $_uid = session("SESSION_ID");
        $userModel = M("user");
        $accountChange = M("accounts_change");
        $userModel->startTrans();

        $res = $userModel->db(0)->lock(true)->where(array("id" => $_uid))->field('cur_account,wallet_account')->find();

        if(bccomp($res[wallet_account],$bonus,4)==-1)
        {
            echo "0-分红钱包余额不足，请先转账到分红钱包";
            exit();
        }

        $_a = $res['wallet_account']-$bonus;
        $_f_1 = $userModel->db(0)->where(array("id" => $_uid))->save(array("wallet_account"=>$_a));

        //账变
        $_change_data = array(
            "accounts_type" => 49,  
            "buy_record_id" => 0,
            "change_amount" => $bonus,
            "userid" => $_uid,
            "username" => session("SESSION_NAME"),
            "parent_id" => session("SESSION_PARENTID"),
            "parent_path" => session("SESSION_PATH"),
            "cur_account"   =>  $res['cur_account'],
            "wallet_account" => $_a,
            "serial_number" => 0,
            "runner_id" => $_uid,
            "runner_name" => session("SESSION_NAME"),
            "change_time" => time(),
            "is_addto" => 0,
            "remark"	=> "给下级派发日工资"
        );
        $_account_change_id = $accountChange->db(0)->add($_change_data);
        //更新该条账变的账变编号
        $achange_num = strtoupper($_xDe->encode($_account_change_id));
        $accountChange->db(0)->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));

        // 报表数据更新
        _team_report(array($_uid,0,0,0,0,0,0,-$bonus));

        $userinfo = $userModel->db(0)->lock(true)->where(array("id" => $userHongli['userid']))->field('username,parent_id,parent_path,cur_account,wallet_account')->find();
    
        $_b = $userinfo['wallet_account']+$bonus;
        $_f_2 = $userModel->db(0)->where(array("id" => $userHongli['userid']))->save(array("wallet_account"=>$_b));

        //账变
        $_change_data = array(
            "accounts_type" => 50,  
            "buy_record_id" => 0,
            "change_amount" => $bonus,
            "userid" => $userinfo['userid'],
            "username" => $userinfo['username'],
            "parent_id" => $userinfo['parent_id'],
            "parent_path" => $userinfo['parent_path'],
            "cur_account"   =>  $userinfo['cur_account'],
            "wallet_account" => $_b,
            "serial_number" => 0,
            "runner_id" => $_uid,
            "runner_name" => session("SESSION_NAME"),
            "change_time" => time(),
            "is_addto" => 0,
            "remark"	=> "获得上级派发日工资"
        );
        $_account_change_id = $accountChange->db(0)->add($_change_data);
        //更新该条账变的账变编号
        $achange_num = strtoupper($_xDe->encode($_account_change_id));
        $accountChange->db(0)->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));

        // 报表数据更新
        _team_report(array($datarateinfo['userid'],0,0,0,0,0,0,$bonus));

        // 更改user_hongli表中的状态
        M("dayrateinfo")->where(array("id"=>$id,"status"=>0))->save(array("status"=>1));

        $userModel->commit();

        echo "1-日工资派发成功";
    }
}



?>