<?php
namespace Home\Controller;

use Think\Controller;

class BonusController extends CommonController
{
    public function index()
    {
        $this->menu = "selfRecord";
        $this->managermenu = 'report';
        $this->reportmanager = 'selfbonus';
        $lottery = M("lottery")->where(true)->select();
        $uid = session("SESSION_ID");
        $lottery_id = I("get.lid","1");
        $temp = array();
        foreach($lottery as $val) {
            if($val["id"]==1){
                $temp[] = array("id" => $val["id"],"name" => "时时彩");
            }
            if($val["id"]==5){
                $temp[] = array("id" => $val["id"],"name" => "11选5");
            }
            if($val["id"]==9){
                $temp[] = array("id" => $val["id"],"name" => "快乐十分");
            }
            if($val["id"]==11){
                $temp[] = array("id" => $val["id"],"name" => "福彩3D|P3");
            }
            if($val["id"]==15){
                $temp[] = array("id" => $val["id"],"name" => "快三");
            }
            if($val["id"]==13){
                $temp[] = array("id" => $val["id"],"name" => "北京快乐8");
            }
            if($val["id"]==21){
                $temp[] = array("id" => $val["id"],"name" => "北京PK10");
            }
        }
        $this->temp = $temp;

        //获取用户奖金
        $_temp = array();
        $_user_bonus = $this->CreateUserBonus($lottery_id,$_UUID);
        $bonusArray = array_map("doArray", $_user_bonus);
        $_temp = array();
        foreach($bonusArray as $key=>$val){
            if($lottery_id==1)
                $lotteryName = "时时彩";
            else if($lottery_id==5)
                $lotteryName = "11选5";
            else if($lottery_id==9)
                $lotteryName = "快乐十分";
            else if($lottery_id==11)
                $lotteryName = "福彩3D|P3";
            else if($lottery_id==15)
                $lotteryName = "快三";
            else if($lottery_id==13)
                $lotteryName = "北京快乐8";
            else if($lottery_id==21)
                $lotteryName = "北京PK10";
            $playWay = $key;
            if(strpos($val,"||")===false){
                $_a = explode("_",$val);
                $bonus = $_a[0];
                $fandian = $_a[1];
            } else {
                $_b = explode("||",$val);
                $_count = count($_b)-1;
                $str = "";
                for($i=1;$i<$_count;$i++){
                    $str .= $_b[$i]."<br/>";
                }
                $_b_1 = explode("_",$_b[$_count]);
                $bonus = $str.$_b_1[0];
                $fandian = $_b_1[1];
            }
            $_arr = array(
                "lotteryName" => $lotteryName,
                "playWay"     => $playWay,
                "bonus"       => $bonus,
                "fandian"     => $fandian
            );
            $_temp[] = $_arr;
        }
        $this->_temp = $_temp;
        $this->lid = $lottery_id;
        $this->managermenu = 'bonus';
        $this->display();
    }
}



?>