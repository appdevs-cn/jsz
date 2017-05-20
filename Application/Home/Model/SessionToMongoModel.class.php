<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2017/3/2
 * Time: 下午6:42
 */

namespace Home\Model;

use Think\Model\MongoModel;


class SessionToMongoModel extends MongoModel
{

    protected $connection = "mongo://app:123456@127.0.0.1:27017/admin";
    protected $tableName = "session";
    protected $dbName = 'order';
}
