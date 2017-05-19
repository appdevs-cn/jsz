<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/9
 * Time: 下午3:05
 */

namespace Home\Model;


use Think\Model;

class TikuanConfigModel extends Model
{
    protected $trueTableName = 'tikuan_config';

    public $list;

    public function __construct($name, $tablePrefix, $connection)
    {
        parent::__construct($name, $tablePrefix, $connection);
        $this->list = $this->find(1);
    }

    /**
     * 提款次数
     *
     * @return mixed
     */
    public function getCount()
    {
        return $this->list['count'];
    }

    /**
     * 最低提款金额
     *
     * @return mixed
     */
    public function getMinmoney()
    {
        return $this->list['min_money'];
    }

    /**
     * 获取最高提款金额
     *
     * @return mixed
     */
    public function getMaxmoney()
    {
        return $this->list['max_money'];
    }

    /**
     * 获取提款开始时间
     *
     * @return mixed
     */
    public function getStarttime()
    {
        return $this->list['starttime'];
    }

    /**
     * 获取提款结束时间
     *
     * @return mixed
     */
    public function getEndtime()
    {
        return $this->list['endtime'];
    }

    /**
     * 提款状态
     *
     * @return mixed
     */
    public function getStatus()
    {
        return $this->list['status'];
    }
}