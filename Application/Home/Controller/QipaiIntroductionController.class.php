<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:19
 */

namespace Home\Controller;


use Think\Controller;

class QipaiIntroductionController extends CommonController
{
    public function index()
    {
        $this->menu = "QipaiIntroduction";
        $this->display();
    }

    public function Quesheng()
    {
         $this->menu = "QipaiIntroduction";
        $this->display();
    }

    public function Niuniu()
    {
         $this->menu = "QipaiIntroduction";
        $this->display();
    }
}