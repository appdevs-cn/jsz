<?php

namespace Home\Controller;

use Home\Controller\CommonController;

class CpController extends CommonController
{
    public function index()
    {
        $this->menu = "lottery";
        $this->display();
    }
}


?>