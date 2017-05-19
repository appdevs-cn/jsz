<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午7:35
 */

namespace Home\Controller;


class ProxyChangeRecordController extends CommonController
{
    public function index()
    {
        $this->menu = "manager";
        $this->managermenu = 'proxy';
        $this->proxymanager = 'changerecord';
        $this->display();
    }
}