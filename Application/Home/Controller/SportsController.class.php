<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:19
 */

namespace Home\Controller;


use Think\Controller;

class SportsController extends CommonController
{
    public function index()
    {
        $this->menu = "sports";
        $this->display();
    }
}