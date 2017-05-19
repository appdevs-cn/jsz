<?php

namespace Home\Controller;
use Think\Controller;
class DownAppController extends CommonController
{
    public function index()
    {
        $this->menu = "app";
        $this->display();
    }
}



?>