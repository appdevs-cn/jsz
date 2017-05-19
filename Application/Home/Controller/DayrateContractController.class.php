<?php

namespace Home\Controller;

use Home\Model\DayratecontractModel as Dayratecontract;
class DayrateContractController extends CommonController
{
    // 签署日工资契约
    public function sign()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace()) exit();
        $Dayratecontract->data = $_POST;
        echo $Dayratecontract->AddContractItem();
    }

    // 查询签约中的契约
    public function selectContractingItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace())
        {
            echo json_encode(array());
            exit();
        }
        $Dayratecontract->data = array("contractId"=>I("post.contractId"));
        echo $Dayratecontract->SearchContracting();
    }

    // 查询签约成功的契约
    public function selectContractendItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace())
        {
            echo json_encode(array());
            exit();
        }
        $Dayratecontract->data = array("contractId"=>I("post.contractId"));
        echo $Dayratecontract->SearchContractend();
    }

    // 查看等待解除的契约
    public function selectDisContractingItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace())
        {
            echo json_encode(array());
            exit();
        }
        $Dayratecontract->data = array("contractId"=>I("post.contractId"));
        echo $Dayratecontract->SearchDisContracting();
    }

    // 签订契约
    public function replayContractItem()
    {
        if($this->userType==1) exit();
        $this->proxymanager = "contract";
        $this->menu = "proxy";
        $this->contractmanager = "dayratecontract";
        $this->display('index');
    }

    // 获取当前的契约
    public function getCurrentContractItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace())
        {
            echo json_encode(array());
            exit();
        }
        echo $Dayratecontract->GetContract();
    }

    //  确定日工资签约
    public function agreeContract()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace()) exit();
        $Dayratecontract->data = array("id"=>$agreeId);
        echo $Dayratecontract->agreeContractHandler();
    }

    // 申请解除契约p[下级向上级申请解除契约]
    public function applyRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace()) exit();
        $Dayratecontract->data = array("id"=>$agreeId);
        echo $Dayratecontract->releaseContractHandler();
    }

    // 同意解除契约分红【上级同意下级的申请解除契约】
    public function agreeRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace()) exit();
        $Dayratecontract->data = array("id"=>$agreeId);
        echo $Dayratecontract->agreeReleaseContractHandler();
    }

    // 申请解除契约p[上级向下级申请解除契约]
    public function TopToDownapplyRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace()) exit();
        $Dayratecontract->data = array("id"=>$agreeId);
        echo $Dayratecontract->TopToDownreleaseContractHandler();
    }

    // 同意解除契约分红【下级同意上级的申请解除契约】
    public function DownToTopagreeRelease()
    {
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Dayratecontract = new Dayratecontract();
        if(!$Dayratecontract->isHaveDayratecontrace()) exit();
        $Dayratecontract->data = array("id"=>$agreeId);
        echo $Dayratecontract->DownToTopagreeReleaseContractHandler();
    }
}


?>