<?php
namespace Home\Model;

use Think\Model;

class TransferRecordModel extends Model
{
    protected $trueTableName = "transfer_record";

    // 添加数据
    public function addTransferRecord()
    {
        $data = $this->data;
        $InsertData = $data['data']['add'];
        if($this->add($InsertData))
            return true;
        else 
            return false;
    }
}



?>