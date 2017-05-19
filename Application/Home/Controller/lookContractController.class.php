<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class lookContractController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "lookcontract";
        $this->display();
    }
}



?>