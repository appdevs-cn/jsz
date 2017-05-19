<?php
namespace Home\Model;

use Think\Model\MongoModel;

class FoundChangeMongoModel extends MongoModel
{
    protected $connection = "mongo://app:123456@192.168.1.4:27017/admin";
    protected $tableName = "foundchange";
    protected $dbName = 'order';
}



?>