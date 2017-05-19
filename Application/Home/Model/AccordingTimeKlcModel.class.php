<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/9
 * Time: 下午4:55
 */

namespace Home\Model;


use Think\Model;

class AccordingTimeKlcModel extends Model
{
    protected $trueTableName = 'according_time_klc';

    public $nowTime;
    public $today0Time;
    public $today3Time;
    public $accordStartTime;
    public $accordEndTime;
    public $uid;

    public function __construct($name, $tablePrefix, $connection)
    {
        parent::__construct($name, $tablePrefix, $connection);
        $this->nowTime = time();
        $this->today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $this->today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        $this->uid = session("SESSION_ID");
        if ($this->nowTime >= $this->today0Time && $this->nowTime < $this->today3Time) {
            $this->accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") - 1, date("Y"))));
            $this->accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d"), date("Y"))));
        } else {
            $this->accordStartTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
            $this->accordEndTime = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d")+1, date("Y"))));
        }
    }

    /**
     * 获取指定时间到现在报表数据
     *
     * @param $starttime
     * @param $endtime
     * @return mixed
     */
    public function getReport($starttime="")
    {
        $_map["userid"] = $this->uid;
        $_map["_string"] = ($starttime!="") ? "accordTime>=".$starttime." and accordTime<=".time() : "";
        $_self_field = array(
            "according_time_klc.username",
            "according_time_klc.userid",
            "sum(according_time_klc.touzhuAmount)/100000 as touzhuAmount",
            "sum(according_time_klc.fandianAmount)/100000 as fandianAmount",
            "sum(according_time_klc.bonusAmount)/100000 as bonusAmount",
            "sum(according_time_klc.gain)/100000 as gain"
        );
        return $this->where($_map)->field($_self_field)->find();
    }

    /**
     * 获取团队时间范围内的报表数据
     *
     * @param $starttime
     * @param string $endtime
     * @return mixed
     */
    public function getTeamReport($starttime,$endtime='')
    {
        $endtime = ($endtime=="") ? mktime(3,0,0,date('m'),date('d')+1,date('Y')) : $endtime;
        $_self_field = array(
            "according_time_klc.username",
            "according_time_klc.userid",
            "sum(according_time_klc.touzhuAmount)/100000 as touzhuAmount",
            "sum(according_time_klc.fandianAmount)/100000 as fandianAmount",
            "sum(according_time_klc.bonusAmount)/100000 as bonusAmount",
            "sum(according_time_klc.gain)/100000 as gain"
        );
        return $this->where("(FIND_IN_SET(".$this->uid.",parent_path) or userid=".$this->uid.") and accordTime>=".$starttime." and accordTime<".$endtime)->field($_self_field)->find();
    }

    /**
     * 查询今天的报表
     *
     * @return mixed
     */
    public function getTodayReport()
    {
        $_map["userid"] = $this->uid;
        $_map["_string"] = "accordTime>=".$this->accordStartTime." and accordTime<".$this->accordEndTime;
        $_self_field = array(
            "according_time_klc.username",
            "according_time_klc.userid",
            "sum(according_time_klc.touzhuAmount)/100000 as touzhuAmount",
            "sum(according_time_klc.fandianAmount)/100000 as fandianAmount",
            "sum(according_time_klc.bonusAmount)/100000 as bonusAmount",
            "sum(according_time_klc.gain)/100000 as gain"
        );
        return $this->where($_map)->field($_self_field)->find();
    }
}