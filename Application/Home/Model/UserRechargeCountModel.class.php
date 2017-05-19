<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/9
 * Time: 下午4:30
 */

namespace Home\Model;


use Think\Model;

class UserRechargeCountModel extends Model
{

    protected $trueTableName = 'user_recharge_count';

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
     * 当天总的充值列表
     *
     * @return mix
     */
    public function getTodayRecharge()
    {
        return $this->where("userid=".$this->uid." AND type='充值' AND time>=".$this->accordStartTime." and time<=".$this->accordEndTime)->select();

    }

    /**
     * 当天总的提款列表
     *
     * @return int
     */
    public function getTodayTikuan()
    {
        return $this->where("userid=".$this->uid." AND type='提款' AND time>=".$this->accordStartTime." and time<=".$this->accordEndTime)->select();
    }

    /**
     * 获取最后一次提款时间
     *
     * @return mixed
     */
    public function getLastWithDrawTime()
    {
        return $this->where(array("userid"=>$this->uid,"type"=>"提款"))->order("id DESC")->getField("time");
    }

    /**
     * 从上次提款到现在充值的金额
     *
     * @return int
     */
    public function getLastToNowRecharge()
    {
        $withDrawLastTime = $this->getLastWithDrawTime();
        if(!empty($withDrawLastTime))
        {
            $_map["userid"] = $this->uid;
            $_map["type"] = "充值";
            $_map["_string"] = "time>=".$withDrawLastTime;
        }
        else
        {
            $_map["userid"] = $this->uid;
            $_map["type"] = "充值";
        }
        $res = $this->where($_map)->select();
        $total_liushui = 0;
        foreach ($res as $key => $value) {
            $total_liushui = $total_liushui+$value["amount"];
        }
        return sprintf("%.4f",$total_liushui/100000);
    }
}