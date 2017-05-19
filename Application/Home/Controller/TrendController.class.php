<?php
namespace Home\Controller;
use Think\Controller;

class TrendController extends CommonController {

    public function index(){
        $trent = I("get.u","");
        if($trent=="cqssc"){
            $this->display("cqssctrend");
        }

        if($trent=="jxssc"){
            $this->display("jxssctrend");
        }

        if($trent=="xjssc"){
            $this->display("xjssctrend");
        }

        if($trent=="tjssc"){
            $this->display("tjssctrend");
        }

        if($trent=="yfssc"){
            $this->display("yfssctrend");
        }

        if($trent=="efssc"){
            $this->display("efssctrend");
        }

        if($trent=="wfssc"){
            $this->display("wfssctrend");
        }

        if($trent=="bjssc"){
            $this->display("bjssctrend");
        }

        if($trent=="jndssc"){
            $this->display("jndssctrend");
        }

        if($trent=="txssc"){
            $this->display("txssctrend");
        }

        if($trent=="qqssc"){
            $this->display("qqssctrend");
        }

        if($trent=="twssc"){
            $this->display("twssctrend");
        }
    }


}
