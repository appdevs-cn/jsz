<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/15
 * Time: 上午8:51
 */

namespace Home\Model;


use Think\Model;

class SystemMessageModel extends Model
{
    protected $trueTableName = 'system_message';

    public function addMessage()
    {
        $data = $this->data;
        $_data = $data['data'];
        if($this->add($_data))
            return true;
        else
            return false;
    }

    public function delMessage()
    {
        $data = $this->data;
        $id = $data['data']['id'];
        $this->where(array("id"=>$id))->delete();
        return true;
    }

    public function updateMessage()
    {
        $data = $this->data;
        $id = $data['data']['id'];
        if($this->where(array("id"=>$id))->save(array("status"=>1)))
            return true;
        else
            return false;
    }
}