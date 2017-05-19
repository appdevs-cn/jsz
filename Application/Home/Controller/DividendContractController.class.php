<?php

namespace Home\Controller;

use Home\Model\SharecontractModel as Sharecontract;
class DividendContractController extends CommonController
{
    // 签署分红契约
    public function sign()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace()) exit();
        $Sharecontract->data = $_POST;
        echo $Sharecontract->AddContractItem();
    }

    // 查询签约中的契约
    public function selectContractingItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace())
        {
            echo json_encode(array());
            exit();
        }
        $Sharecontract->data = array("contractId"=>I("post.contractId"));
        echo $Sharecontract->SearchContracting();
    }

    // 查询签约成功的契约
    public function selectContractendItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace())
        {
            echo json_encode(array());
            exit();
        }
        $Sharecontract->data = array("contractId"=>I("post.contractId"));
        echo $Sharecontract->SearchContractend();
    }

    // 查看等待解除的契约
    public function selectDisContractingItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace())
        {
            echo json_encode(array());
            exit();
        }
        $Sharecontract->data = array("contractId"=>I("post.contractId"));
        echo $Sharecontract->SearchDisContracting();
    }

    // 签订契约
    public function replayContractItem()
    {
        if($this->userType==1) exit();
        $this->proxymanager = "contract";
        $this->menu = "proxy";
        $this->contractmanager = "contractgain";
        $this->display('index');
    }

    // 获取当前的契约
    public function getCurrentContractItem()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace())
        {
            echo json_encode(array());
            exit();
        } 
        echo $Sharecontract->GetContract();
    }

    //  确定分红签约
    public function agreeContract()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace()) exit();
        $Sharecontract->data = array("id"=>$agreeId);
        echo $Sharecontract->agreeContractHandler();
    }

    // 申请解除契约p[下级向上级申请解除契约]
    public function applyRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace()) exit();
        $Sharecontract->data = array("id"=>$agreeId);
        echo $Sharecontract->releaseContractHandler();
    }

    // 同意解除契约分红【上级同意下级的申请解除契约】
    public function agreeRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace()) exit();
        $Sharecontract->data = array("id"=>$agreeId);
        echo $Sharecontract->agreeReleaseContractHandler();
    }

    // 申请解除契约p[上级向下级申请解除契约]
    public function TopToDownapplyRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace()) exit();
        $Sharecontract->data = array("id"=>$agreeId);
        echo $Sharecontract->TopToDownreleaseContractHandler();
    }

    // 同意解除契约分红【下级同意上级的申请解除契约】
    public function DownToTopagreeRelease()
    {
        if($this->userType==1) exit();
        if(!IS_POST) exit();
        $agreeId = I("post.agreeid");
        $Sharecontract = new Sharecontract();
        if(!$Sharecontract->isHaveSharecontrace()) exit();
        $Sharecontract->data = array("id"=>$agreeId);
        echo $Sharecontract->DownToTopagreeReleaseContractHandler();
    }
}


?>