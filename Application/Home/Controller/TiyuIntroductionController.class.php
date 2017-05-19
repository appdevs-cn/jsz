<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:19
 */

namespace Home\Controller;


use Think\Controller;

class TiyuIntroductionController extends CommonController
{
    public function index()
    {
        $this->menu = "TiyuIntroduction";
        $this->display();
    }

    public function Sbt()
    {
         $this->menu = "TiyuIntroduction";
        $this->display();
    }
}