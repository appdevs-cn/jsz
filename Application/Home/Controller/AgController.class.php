<?php

namespace Home\Controller;

use Home\Controller\CommonController;

class AgController extends CommonController
{
    public function index()
    {
        echo httpRequest("http://www.baidu.com");
        //$this->display();
    }
}




?>