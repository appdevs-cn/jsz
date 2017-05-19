<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/14
 * Time: 上午9:54
 */

namespace Home\Controller;

class TeamReportController extends CommonController
{
    public function index()
    {
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxymanager = 'teamreport';
        $this->display();
    }

    public function getTeamReport()
    {
        $username = I("post.username");
        $type = I("post.type");
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            $accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            $accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }

        $starttime = I("post.starttime","");
        $stime = (!empty($starttime)) ? strtotime($starttime." 3:0:0") : $accordStartTime;
        $endtime = I("post.endtime","");
        $etime = (!empty($endtime)) ? strtotime($endtime." 3:0:0") : $accordEndTime;
        $selfusername = session("SESSION_NAME");
        $username = (!empty($username)) ? $username : $selfusername;
        if($type=="lottery")
        {
            $userModel = M("user");
            $accordingTimeModel = M("according_time");
            $uid = $userModel->where("username='".$username."'")->getField("id");
            if($username!=$selfusername)
            {
                $ischild = $userModel->where("FIND_IN_SET(".session("SESSION_ID").",parent_path) and id=".$uid)->find();
                if(empty($ischild)) exit();
            }
            $map["_string"] = "parent_id=".$uid." or id=".$uid;
            $_count = $userModel->where($map)->field("id,parent_id,parent_path")->count();
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
            $userlist = $userModel->where($map)->field("id,username,parent_id,parent_path")->limit($p->firstRow,$p->listRows)->order("id asc")->select();
            $result = array();

            $_field = array(
                "according_time.username",
                "according_time.userid",
                "sum(according_time.rechargeAmount) as rechargeAmount",
                "sum(according_time.tixianAmount) as tixianAmount",
                "sum(according_time.touzhuAmount) as touzhuAmount",
                "sum(according_time.fandianAmount) as fandianAmount",
                "sum(according_time.bonusAmount) as bonusAmount",
                "sum(according_time.privilegeAmount) as privilegeAmount",
                "sum(according_time.commission) as commission",
                "sum(according_time.gain) as gain",
                "sum(according_time.receive) as receive"
            );
            $temp = array();
            foreach($userlist as $item)
            {
                if($item['id']!=$uid)
                    $where = "(FIND_IN_SET(" . $item["id"] . ",parent_path) or userid=" . $item["id"] . ") and accordTime>=" . $stime . " and accordTime<" . $etime;
                else if($item['id']==$uid)
                    $where = "userid=" . $item["id"] . " and accordTime>=" . $stime . " and accordTime<" . $etime;
                $res = $accordingTimeModel->where($where)->field($_field)->find();
                if(empty($res["rechargeAmount"]) && empty($res["tixianAmount"]) && empty($res["touzhuAmount"]) && empty($res["fandianAmount"]) && empty($res["bonusAmount"]) && empty($res["privilegeAmount"]) && empty($res["commission"]) && empty($res["gain"]) && empty($res["receive"]))
                {
                    $res = array(
                        "username" => $item['username'],
                        "userid" => $item['id'],
                        "rechargeAmount" => "0.0000",
                        "tixianAmount" => "0.0000",
                        "touzhuAmount" => "0.0000",
                        "fandianAmount" => "0.0000",
                        "bonusAmount" => "0.0000",
                        "privilegeAmount" => "0.0000",
                        "commission" => "0.0000",
                        "gain" => "0.0000",
                        "receive" => "0.0000"
                    );

                    if($item['id']!=$uid)
                    {
                        $res['haveData'] = false;
                        $res['color'] = "success";
                        $res['operate'] = '<a style="cursor:pointer" class="ml--10 disabled" title="自身盈亏">自身盈亏</a><a style="cursor:pointer" class="ml--10 disabled" title="下级盈亏">下级盈亏</a>';
                    }
                    else if($item['id']==$uid)
                    {
                        $res['haveData'] = true;
                        $res['color'] = "info";
                        $res['operate'] = '自身盈亏';
                    }
                }
                else
                {
                    $res = array(
                        "username" => $item['username'],
                        "userid" => $item['id'],
                        "rechargeAmountValue" => $res['rechargeAmount'],
                        "rechargeAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['rechargeAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['rechargeAmount']/100000).'</a>',
                        "tixianAmountValue" => $res['tixianAmount'],
                        "tixianAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['tixianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['tixianAmount']/100000).'</a>',
                        "touzhuAmountValue" => $res['touzhuAmount'],
                        "touzhuAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['touzhuAmount']/100000).'</a>',
                        "fandianAmountValue" => $res['fandianAmount'],
                        "fandianAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['fandianAmount']/100000).'</a>',
                        "bonusAmountValue" => $res['bonusAmount'],
                        "bonusAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['bonusAmount']/100000).'</a>',
                        "privilegeAmountValue" => $res['privilegeAmount'],
                        "privilegeAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['privilegeAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['privilegeAmount']/100000).'</a>',
                        "commissionValue" => $res['commission'],
                        "commission" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['commission']/100000).'" data-placement="top">'.sprintf("%.4f",$res['commission']/100000).'</a>',
                        "gainValue" => $res['gain'],
                        "gain" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$res['gain']/100000).'</a>',
                        "receiveValue" => $res['receive'],
                        "receive" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['receive']/100000).'" data-placement="top">'.sprintf("%.4f",$res['receive']/100000).'</a>',
                    );
                    if($item['id']!=$uid)
                    {
                        $res['color'] = "danger";
                        //查询自己的盈亏情况
                        $self = $accordingTimeModel->where("userid=".$item['id']." and accordTime>=".$stime." and accordTime<".$etime)->field($_field)->find();
                        $_html = '充值:'.sprintf("%.4f",$self['rechargeAmount']/100000).'|';
                        $_html .= '取款:'.sprintf("%.4f",$self['tixianAmount']/100000).'|';
                        $_html .= '消费:'.sprintf("%.4f",$self['touzhuAmount']/100000).'|';
                        $_html .= '中奖:'.sprintf("%.4f",$self['bonusAmount']/100000).'|';
                        $_html .= '返点:'.sprintf("%.4f",$self['fandianAmount']/100000).'|';
                        $_html .= '广告:'.sprintf("%.4f",$self['commission']/100000).'|';
                        $_html .= '活动:'.sprintf("%.4f",$self['privilegeAmount']/100000).'|';
                        $_html .= '分红:'.sprintf("%.4f",$self['receive']/100000).'|';
                        $_html .= '盈亏:'.sprintf("%.4f",$self['gain']/100000);
                        $res['operate'] = '<a style="cursor:pointer" class="c--f35c19 ml--10" data-method="slefreport" data-content="'.htmlspecialchars($_html).'">自身盈亏</a><a style="cursor:pointer" class="c--f35c19 ml--10" data-method="searchChild" uname="'.$res["username"].'" title="下级盈亏">下级盈亏</a>';
                    }
                    else if($item['id']==$uid)
                    {
                        $res['color'] = "default";
                        $res['operate'] = '自身盈亏';
                    }
                    $res['haveData'] = true;
                }
                $temp[] = $res;
            }
            $total_rechargeAmount = $total_tixianAmount = $total_touzhuAmount = $total_fandianAmount = $total_bonusAmount = $total_privilegeAmount = $total_commission = $total_gain = $total_receive = 0;
            foreach ($temp as $key=>$val) {
                $total_rechargeAmount = $total_rechargeAmount + $val["rechargeAmountValue"];
                $total_tixianAmount = $total_tixianAmount + $val["tixianAmountValue"];
                $total_touzhuAmount = $total_touzhuAmount + $val["touzhuAmountValue"];
                $total_fandianAmount = $total_fandianAmount + $val["fandianAmountValue"];
                $total_bonusAmount = $total_bonusAmount + $val["bonusAmountValue"];
                $total_privilegeAmount = $total_privilegeAmount + $val["privilegeAmountValue"];
                $total_commission = $total_commission + $val["commissionValue"];
                $total_gain = $total_gain + $val["gainValue"];
                $total_receive = $total_receive + $val["receiveValue"];
            }
            $singerCensus = array();
            $singerCensus['rechargeAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_rechargeAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_rechargeAmount/100000).'</a>';
            $singerCensus['tixianAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_tixianAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_tixianAmount/100000).'</a>';
            $singerCensus['touzhuAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_touzhuAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_touzhuAmount/100000).'</a>';
            $singerCensus['fandianAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_fandianAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_fandianAmount/100000).'</a>';
            $singerCensus['bonusAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_bonusAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_bonusAmount/100000).'</a>';
            $singerCensus['privilegeAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_privilegeAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_privilegeAmount/100000).'</a>';
            $singerCensus['commission'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_commission/100000).'" data-placement="top">'.sprintf("%.4f",$total_commission/100000).'</a>';
            $singerCensus['gain'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_gain/100000).'" data-placement="top">'.sprintf("%.4f",$total_gain/100000).'</a>';
            $singerCensus['receive'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_receive/100000).'" data-placement="top">'.sprintf("%.4f",$total_receive/100000).'</a>';

            //计算总的统计
            $total_where = "(FIND_IN_SET(" . $uid . ",parent_path) or userid=" . $uid . ") and accordTime>=" . $stime . " and accordTime<" . $etime;
            $_total_field = array(
                "sum(according_time.rechargeAmount) as rechargeAmount",
                "sum(according_time.tixianAmount) as tixianAmount",
                "sum(according_time.touzhuAmount) as touzhuAmount",
                "sum(according_time.fandianAmount) as fandianAmount",
                "sum(according_time.bonusAmount) as bonusAmount",
                "sum(according_time.privilegeAmount) as privilegeAmount",
                "sum(according_time.commission) as commission",
                "sum(according_time.gain) as gain",
                "sum(according_time.receive) as receive"
            );
            $totalresult = $accordingTimeModel->where($total_where)->field($_total_field)->find();
            $totalCensus = array();
            $totalCensus['rechargeAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['rechargeAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['rechargeAmount']/100000).'</a>';
            $totalCensus['tixianAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['tixianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['tixianAmount']/100000).'</a>';
            $totalCensus['touzhuAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['touzhuAmount']/100000).'</a>';
            $totalCensus['fandianAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['fandianAmount']/100000).'</a>';
            $totalCensus['bonusAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['bonusAmount']/100000).'</a>';
            $totalCensus['privilegeAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['privilegeAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['privilegeAmount']/100000).'</a>';
            $totalCensus['commission'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['commission']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['commission']/100000).'</a>';
            $totalCensus['gain'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['gain']/100000).'</a>';
            $totalCensus['receive'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['receive']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['receive']/100000).'</a>';

            $result['list'] = $temp;
            $result['singerCensus'] = $singerCensus;
            $result['totalCensus'] = $totalCensus;
            $result['page'] = $page;
            $result['type'] = $type;
            echo json_encode($result);
        }
        else if($type=="klc")
        {
            $userModel = M("user");
            $accordingTimeModel = M("according_time_klc");
            $uid = $userModel->where("username='".$username."'")->getField("id");
            if($username!=$selfusername)
            {
                $ischild = $userModel->where("FIND_IN_SET(".session("SESSION_ID").",parent_path) and id=".$uid)->find();
                if(empty($ischild)) exit();
            }
            $map["_string"] = "parent_id=".$uid." or id=".$uid;
            $_count = $userModel->where($map)->field("id,parent_id,parent_path")->count();
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
            $userlist = $userModel->where($map)->field("id,username,parent_id,parent_path")->limit($p->firstRow,$p->listRows)->order("id asc")->select();
            $result = array();

            $_field = array(
                "according_time_klc.username",
                "according_time_klc.userid",
                "sum(according_time_klc.touzhuAmount) as touzhuAmount",
                "sum(according_time_klc.fandianAmount) as fandianAmount",
                "sum(according_time_klc.bonusAmount) as bonusAmount",
                "sum(according_time_klc.gain) as gain"
            );
            $temp = array();
            foreach($userlist as $item)
            {
                if($item['id']!=$uid)
                    $where = "(FIND_IN_SET(" . $item["id"] . ",parent_path) or userid=" . $item["id"] . ") and accordTime>=" . $stime . " and accordTime<" . $etime;
                else if($item['id']==$uid)
                    $where = "userid=" . $item["id"] . " and accordTime>=" . $stime . " and accordTime<" . $etime;
                $res = $accordingTimeModel->where($where)->field($_field)->find();
                if(empty($res["touzhuAmount"]) && empty($res["fandianAmount"]) && empty($res["bonusAmount"])&& empty($res["gain"]))
                {
                    $res = array(
                        "username" => $item['username'],
                        "userid" => $item['id'],
                        "touzhuAmount" => "0.0000",
                        "fandianAmount" => "0.0000",
                        "bonusAmount" => "0.0000",
                        "gain" => "0.0000"
                    );

                    if($item['id']!=$uid)
                    {
                        $res['haveData'] = false;
                        $res['color'] = "danger";
                        $res['operate'] = '<a style="cursor:pointer" class="ml--10 disabled" title="自身盈亏">自身盈亏</a><a style="cursor:pointer" class="ml--10 disabled" title="下级盈亏">下级盈亏</a>';
                    }
                    else if($item['id']==$uid)
                    {
                        $res['haveData'] = true;
                        $res['color'] = "default";
                        $res['operate'] = '自身盈亏</span>';
                    }
                }
                else
                {
                    $res = array(
                        "username" => $item['username'],
                        "userid" => $item['id'],
                        "touzhuAmountValue" => $res['touzhuAmount'],
                        "touzhuAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['touzhuAmount']/100000).'</a>',
                        "fandianAmountValue" => $res['fandianAmount'],
                        "fandianAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['fandianAmount']/100000).'</a>',
                        "bonusAmountValue" => $res['bonusAmount'],
                        "bonusAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['bonusAmount']/100000).'</a>',
                        "gainValue" => $res['gain'],
                        "gain" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$res['gain']/100000).'</a>',
                     );
                    if($item['id']!=$uid)
                    {
                        $res['color'] = "success";
                        //查询自己的盈亏情况
                        $self = $accordingTimeModel->where("userid=".$item['id']." and accordTime>=".$stime." and accordTime<".$etime)->field($_field)->find();
                        $_html .= '消费:'.sprintf("%.4f",$self['touzhuAmount']/100000).'|';
                        $_html .= '中奖:'.sprintf("%.4f",$self['bonusAmount']/100000).'|';
                        $_html .= '返点:'.sprintf("%.4f",$self['fandianAmount']/100000).'|';
                        $_html .= '盈亏:'.sprintf("%.4f",$self['gain']/100000);
                        $res['operate'] = '<a style="cursor:pointer" style="cursor:pointer" class="c--f35c19 ml--10" data-method="slefreport" data-content="'.htmlspecialchars($_html).'">自身盈亏</a><a style="cursor:pointer" class="c--f35c19 ml--10" data-method="searchChild" uname="'.$res["username"].'" title="下级盈亏">下级盈亏</a>';
                    }
                    else if($item['id']==$uid)
                    {
                        $res['color'] = "info";
                        $res['operate'] = '自身盈亏';
                    }
                    $res['haveData'] = true;
                }
                $temp[] = $res;
            }
            $total_rechargeAmount = $total_tixianAmount = $total_touzhuAmount = $total_fandianAmount = $total_bonusAmount = $total_privilegeAmount = $total_commission = $total_gain = $total_receive = 0;
            foreach ($temp as $key=>$val) {
                $total_touzhuAmount = $total_touzhuAmount + $val["touzhuAmountValue"];
                $total_fandianAmount = $total_fandianAmount + $val["fandianAmountValue"];
                $total_bonusAmount = $total_bonusAmount + $val["bonusAmountValue"];
                $total_gain = $total_gain + $val["gainValue"];
            }
            $singerCensus = array();
            $singerCensus['touzhuAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_touzhuAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_touzhuAmount/100000).'</a>';
            $singerCensus['fandianAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_fandianAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_fandianAmount/100000).'</a>';
            $singerCensus['bonusAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_bonusAmount/100000).'" data-placement="top">'.sprintf("%.4f",$total_bonusAmount/100000).'</a>';
            $singerCensus['gain'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$total_gain/100000).'" data-placement="top">'.sprintf("%.4f",$total_gain/100000).'</a>';
            
            //计算总的统计
            $total_where = "(FIND_IN_SET(" . $uid . ",parent_path) or userid=" . $uid . ") and accordTime>=" . $stime . " and accordTime<" . $etime;
            $_total_field = array(
                "sum(according_time_klc.touzhuAmount) as touzhuAmount",
                "sum(according_time_klc.fandianAmount) as fandianAmount",
                "sum(according_time_klc.bonusAmount) as bonusAmount",
                "sum(according_time_klc.gain) as gain"
            );
            $totalresult = $accordingTimeModel->where($total_where)->field($_total_field)->find();
            $totalCensus = array();
            $totalCensus['touzhuAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['touzhuAmount']/100000).'</a>';
            $totalCensus['fandianAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['fandianAmount']/100000).'</a>';
            $totalCensus['bonusAmount'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['bonusAmount']/100000).'</a>';
            $totalCensus['gain'] = '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$totalresult['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$totalresult['gain']/100000).'</a>';

            $result['list'] = $temp;
            $result['singerCensus'] = $singerCensus;
            $result['totalCensus'] = $totalCensus;
            $result['page'] = $page;
            $result['type'] = $type;
            echo json_encode($result);
        }
    }
}