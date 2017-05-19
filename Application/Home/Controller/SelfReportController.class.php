<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/14
 * Time: 上午9:54
 */

namespace Home\Controller;

class SelfReportController extends CommonController
{
    public function index()
    {
        $this->menu = "selfRecord";
        $this->managermenu = 'report';
        $this->reportmanager = 'selfreport';
        $this->display();
    }

    public function getSelfReportByDays()
    {
        $type = I("post.type");
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        //如果是 那么统计时间是前一天的03：00：00 到 今天的 02：59：59
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
        $uid = session("SESSION_ID");

        if($type=="lottery")
        {
            $accordingTimeModel = M("according_time");
            $where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;
            $_count = $accordingTimeModel->where($where)->count();
            import("Class.Page");
            //分页循环变量
            $listvar = 'list';
            //每页显示的数据量
            $listRows = C("LISTROWS");
            $roolPage = C("ROOLPAGE");
            $url = "";
            //获取数据总数
            $totalRows = $_count;
            $p=new \Page($totalRows, $listRows, http_build_query($where),$url);

            //分页栏每页显示的页数
            $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
            $pages = C('PAGE');
            //可以使用该方法前用C临时改变配置
            foreach ($pages as $key => $value) {
                $p->setConfig($key, $value);
            }
            //分页显示
            $page = $p->show();
            $listrows = $accordingTimeModel->where($where)->limit($p->firstRow,$p->listRows)->order("id desc,accordTime desc")->select();
            $result = array();
            $temp = array();
            foreach($listrows as $res)
            {
                if(empty($res["rechargeAmount"]) && empty($res["tixianAmount"]) && empty($res["touzhuAmount"]) && empty($res["fandianAmount"]) && empty($res["bonusAmount"]) && empty($res["privilegeAmount"]) && empty($res["commission"]) && empty($res["gain"]) && empty($res["receive"]))
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
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
                    $res['haveData'] = false;
                }
                else
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
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
                    $res['color'] = "info";
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
            $total_where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;
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

            $result['list'] = $temp;
            $result['singerCensus'] = $singerCensus;
            $result['totalCensus'] = $totalCensus;
            $result['page'] = $page;
            $result['type'] = $type;
            echo json_encode($result);
        }
        else if($type=="klc")
        {
            $accordingTimeModel = M("according_time_klc");
            $where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;
            $_count = $accordingTimeModel->where($where)->count();
            import("Class.Page");
            //分页循环变量
            $listvar = 'list';
            //每页显示的数据量
            $listRows = C("LISTROWS");
            $roolPage = C("ROOLPAGE");
            $url = "";
            //获取数据总数
            $totalRows = $_count;
            $p=new \Page($totalRows, $listRows, http_build_query($where),$url);

            //分页栏每页显示的页数
            $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
            $pages = C('PAGE');
            //可以使用该方法前用C临时改变配置
            foreach ($pages as $key => $value) {
                $p->setConfig($key, $value);
            }
            //分页显示
            $page = $p->show();
            $listrows = $accordingTimeModel->where($where)->limit($p->firstRow,$p->listRows)->order("id desc,accordTime desc")->select();
            $result = array();
            $temp = array();
            foreach($listrows as $res)
            {
                if(empty($res["touzhuAmount"]) && empty($res["fandianAmount"]) && empty($res["bonusAmount"]) && empty($res["gain"]))
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
                        "touzhuAmount" => "0.0000",
                        "fandianAmount" => "0.0000",
                        "bonusAmount" => "0.0000",
                        "gain" => "0.0000"
                    );
                    $res['haveData'] = false;
                }
                else
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
                        "touzhuAmountValue" => $res['touzhuAmount'],
                        "touzhuAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['touzhuAmount']/100000).'</a>',
                        "fandianAmountValue" => $res['fandianAmount'],
                        "fandianAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['fandianAmount']/100000).'</a>',
                        "bonusAmountValue" => $res['bonusAmount'],
                        "bonusAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['bonusAmount']/100000).'</a>',
                        "gainValue" => $res['gain'],
                        "gain" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$res['gain']/100000).'</a>',
                    );
                    $res['color'] = "info";
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
            $total_where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;
            $_total_field = array(
                "sum(according_time.touzhuAmount) as touzhuAmount",
                "sum(according_time.fandianAmount) as fandianAmount",
                "sum(according_time.bonusAmount) as bonusAmount",
                "sum(according_time.gain) as gain"
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

    public function getSelfReportByMonths()
    {
        $nowTime = time();
        $type=I("post.type");
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

        if($type=="lottery")
        {
            $accordingTimeModel = M("according_time");
            $where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;

            $listrows = $accordingTimeModel->where($where)->order("id desc,accordTime desc")->select();
            $result = array();
            $temp = array();
            foreach($listrows as $res)
            {
                if(empty($res["rechargeAmount"]) && empty($res["tixianAmount"]) && empty($res["touzhuAmount"]) && empty($res["fandianAmount"]) && empty($res["bonusAmount"]) && empty($res["privilegeAmount"]) && empty($res["commission"]) && empty($res["gain"]) && empty($res["receive"]))
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
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
                    $res['haveData'] = false;
                }
                else
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
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
                    $res['color'] = "info";
                    $res['haveData'] = true;
                }
                $temp[] = $res;
            }
            $months = array();

            foreach($temp as $monthItem)
            {
                if(isset($months[$monthItem['month']]))
                {
                    $months[$monthItem['month']]['rechargeAmount'] = $months[$monthItem['month']]['rechargeAmount'] +$monthItem['rechargeAmountValue'];
                    $months[$monthItem['month']]['tixianAmount'] = $months[$monthItem['month']]['tixianAmount'] + $monthItem['tixianAmountValue'];
                    $months[$monthItem['month']]['touzhuAmount'] = $months[$monthItem['month']]['touzhuAmount'] + $monthItem['touzhuAmountValue'];
                    $months[$monthItem['month']]['fandianAmount'] = $months[$monthItem['month']]['fandianAmount'] + $monthItem['fandianAmountValue'];
                    $months[$monthItem['month']]['bonusAmount'] = $months[$monthItem['month']]['bonusAmount'] + $monthItem['bonusAmountValue'];
                    $months[$monthItem['month']]['privilegeAmount'] = $months[$monthItem['month']]['privilegeAmount'] + $monthItem['privilegeAmountValue'];
                    $months[$monthItem['month']]['commission'] = $months[$monthItem['month']]['commission'] + $monthItem['commissionValue'];
                    $months[$monthItem['month']]['gain'] = $months[$monthItem['month']]['gain'] + $monthItem['gainValue'];
                    $months[$monthItem['month']]['receive'] = $months[$monthItem['month']]['receive'] + $monthItem['receiveValue'];
                }
                else
                {
                    $months[$monthItem['month']]['rechargeAmount'] = $monthItem['rechargeAmountValue'];
                    $months[$monthItem['month']]['tixianAmount'] = $monthItem['tixianAmountValue'];
                    $months[$monthItem['month']]['touzhuAmount'] = $monthItem['touzhuAmountValue'];
                    $months[$monthItem['month']]['fandianAmount'] = $monthItem['fandianAmountValue'];
                    $months[$monthItem['month']]['bonusAmount'] = $monthItem['bonusAmountValue'];
                    $months[$monthItem['month']]['privilegeAmount'] = $monthItem['privilegeAmountValue'];
                    $months[$monthItem['month']]['commission'] = $monthItem['commissionValue'];
                    $months[$monthItem['month']]['gain'] = $monthItem['gainValue'];
                    $months[$monthItem['month']]['receive'] = $monthItem['receiveValue'];
                }
            }
            $temp = array();
            foreach($months as $k1=>$res)
            {
                $newres = array(
                    "time" => $k1,
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
                $newres['color'] = "info";
                $newres['haveData'] = true;
                $temp[] = $newres;
            }

            import("Class.Page");
            $count=count($temp);
            $Page = new \Page($count,60,http_build_query($where));
            $pages = C('PAGE');
            foreach ($pages as $key => $value) {
                $Page->setConfig($key, $value);
            }
            $page = $Page->show();
            $list=array_slice($temp,$Page->firstRow,$Page->listRows);

            $total_rechargeAmount = $total_tixianAmount = $total_touzhuAmount = $total_fandianAmount = $total_bonusAmount = $total_privilegeAmount = $total_commission = $total_gain = $total_receive = 0;
            foreach ($list as $key=>$val) {
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
            $total_where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;
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

            $result['list'] = $list;
            $result['singerCensus'] = $singerCensus;
            $result['totalCensus'] = $totalCensus;
            $result['page'] = $page;
            $result['type'] = $type;
            echo json_encode($result);
        }
        else if($type=="klc")
        {
            $accordingTimeModel = M("according_time_klc");
            $where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;

            $listrows = $accordingTimeModel->where($where)->order("id desc,accordTime desc")->select();
            $result = array();
            $temp = array();
            foreach($listrows as $res)
            {
                if(empty($res["touzhuAmount"]) && empty($res["fandianAmount"]) && empty($res["bonusAmount"])&& empty($res["gain"]))
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
                        "touzhuAmount" => "0.0000",
                        "fandianAmount" => "0.0000",
                        "bonusAmount" => "0.0000",
                        "gain" => "0.0000"
                    );
                    $res['haveData'] = false;
                }
                else
                {
                    $res = array(
                        "time" => date("Y-m-d",$res['accordTime']),
                        "month" => date("Y-m",$res['accordTime']),
                        "userid" => $res['userid'],
                        "touzhuAmountValue" => $res['touzhuAmount'],
                        "touzhuAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['touzhuAmount']/100000).'</a>',
                        "fandianAmountValue" => $res['fandianAmount'],
                        "fandianAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['fandianAmount']/100000).'</a>',
                        "bonusAmountValue" => $res['bonusAmount'],
                        "bonusAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['bonusAmount']/100000).'</a>',
                        "gainValue" => $res['gain'],
                        "gain" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$res['gain']/100000).'</a>',
                    );
                    $res['color'] = "info";
                    $res['haveData'] = true;
                }
                $temp[] = $res;
            }
            $months = array();

            foreach($temp as $monthItem)
            {
                if(isset($months[$monthItem['month']]))
                {
                    $months[$monthItem['month']]['touzhuAmount'] = $months[$monthItem['month']]['touzhuAmount'] + $monthItem['touzhuAmountValue'];
                    $months[$monthItem['month']]['fandianAmount'] = $months[$monthItem['month']]['fandianAmount'] + $monthItem['fandianAmountValue'];
                    $months[$monthItem['month']]['bonusAmount'] = $months[$monthItem['month']]['bonusAmount'] + $monthItem['bonusAmountValue'];
                    $months[$monthItem['month']]['gain'] = $months[$monthItem['month']]['gain'] + $monthItem['gainValue'];
                }
                else
                {
                    $months[$monthItem['month']]['touzhuAmount'] = $monthItem['touzhuAmountValue'];
                    $months[$monthItem['month']]['fandianAmount'] = $monthItem['fandianAmountValue'];
                    $months[$monthItem['month']]['bonusAmount'] = $monthItem['bonusAmountValue'];
                    $months[$monthItem['month']]['gain'] = $monthItem['gainValue'];
                }
            }
            $temp = array();
            foreach($months as $k1=>$res)
            {
                $newres = array(
                    "time" => $k1,
                    "touzhuAmountValue" => $res['touzhuAmount'],
                    "touzhuAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['touzhuAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['touzhuAmount']/100000).'</a>',
                    "fandianAmountValue" => $res['fandianAmount'],
                    "fandianAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['fandianAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['fandianAmount']/100000).'</a>',
                    "bonusAmountValue" => $res['bonusAmount'],
                    "bonusAmount" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['bonusAmount']/100000).'" data-placement="top">'.sprintf("%.4f",$res['bonusAmount']/100000).'</a>',
                     "gainValue" => $res['gain'],
                    "gain" => '<a href="javascript:;" data-toggle="tooltip" title="'.sprintf("%.4f",$res['gain']/100000).'" data-placement="top">'.sprintf("%.4f",$res['gain']/100000).'</a>',
                );
                $newres['color'] = "info";
                $newres['haveData'] = true;
                $temp[] = $newres;
            }

            import("Class.Page");
            $count=count($temp);
            $Page = new \Page($count,60,http_build_query($where));
            $pages = C('PAGE');
            foreach ($pages as $key => $value) {
                $Page->setConfig($key, $value);
            }
            $page = $Page->show();
            $list=array_slice($temp,$Page->firstRow,$Page->listRows);

            $total_rechargeAmount = $total_tixianAmount = $total_touzhuAmount = $total_fandianAmount = $total_bonusAmount = $total_privilegeAmount = $total_commission = $total_gain = $total_receive = 0;
            foreach ($list as $key=>$val) {
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
            $total_where = "userid=" . $uid . " and accordTime>=" . $stime . " and accordTime<" . $etime;
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

            $result['list'] = $list;
            $result['singerCensus'] = $singerCensus;
            $result['totalCensus'] = $totalCensus;
            $result['page'] = $page;
            $result['type'] = $type;
            echo json_encode($result);
        }
    }
}