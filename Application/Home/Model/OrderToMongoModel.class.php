<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2017/3/2
 * Time: 下午6:42
 */

namespace Home\Model;

use Think\Model\MongoModel;


class OrderToMongoModel extends MongoModel
{

    protected $connection = "mongo://app:123456@192.168.1.4:27017/admin";
    protected $tableName = "fanjiangorder";
    protected $dbName = 'order';
}