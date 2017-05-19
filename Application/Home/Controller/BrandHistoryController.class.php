<?php

namespace Home\Controller;
use Think\Controller;
class BrandHistoryController extends CommonController
{
    public function index()
    {
        $this->menu = "brand";
        $this->display();
    }

    // 品牌历程 详情
    public function detail()
    {
        $this->menu = "brand";
        $type = I("get.type");
        if($type==1)
            $this->display('brandDetail1');
        else if($type==2)
            $this->display('brandDetail2');
    }
}



?>