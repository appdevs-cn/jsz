<?php

class Eleven{
    
    static function checknumformat(&$unum,$sub_zhu,$gid,$gtype,$lottery_number_id){

        switch($gid){
            case 1:
            case 2:
            case 3:
            case 4:
            case 17:
            case 18:
            case 19:
                $gid = 1;break;
            case 5:
            case 6:
            case 7:
            case 8:
            case 24:
            case 25:
                $gid = 5;break;
            case 9:
            case 10:
            case 26:
            case 27:
                $gid = 9;break;
            case 11:
            case 12:
                $gid = 11;break;
            case 13:
                $gid = 13;break;
            case 15:
            case 16:
                $gid = 15;break;
        }

        switch($gtype){

            case '前三直选复式':
            case '后三直选复式':
                if(in_array($gid, array(5,6,7,8)))
                    self::_check_number($unum,3,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self::_check_number($unum,3,1,20,$gid,$lottery_number_id,$gtype);
                self::qszhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前三直选单式':
            case '后三直选单式':
                self::_check_number_danshi($unum,3,1,11,$gid,$lottery_number_id,$gtype);
                self::qszhxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前三组选复式':
            case '后三组选复式':
                if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self:: _check_number_2($unum,1,20,$gid,$lottery_number_id,$gtype);
                self::qszx_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前三组选单式':
            case '后三组选单式':
                self::qsdx_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前二直选复式':
            case '后二直选复式':
                self::_check_number($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                self::qezhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '二连直':
                self::_check_number($unum,2,1,20,$gid,$lottery_number_id,$gtype);
                self::qezhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前二直选单式':
            case '后二直选单式':
            case '后二组选单式':
            case '前二组选单式':
                self::_check_number_danshi($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                self::qezhxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前二组选复式':
            case '后二组选复式':
                self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::qezx_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case  '二连组':
                self:: _check_number_2($unum,1,20,$gid,$lottery_number_id,$gtype);
                self::qezx_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;

            case '前三一码不定位':
            case '后三一码不定位':
            case '猜中位':
                self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::czhw_and_qsbdw($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '定位胆':
                if(in_array($gid, array(5,6,7,8)))
                    self::_filter_dingwei($unum,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self::_filter_dingwei($unum,1,20,$gid,$lottery_number_id,$gtype);
                self::dwd_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '定单双':
                self::_filter_two_row_11X5($unum,$gid,$lottery_number_id,$gtype);
                self::ddsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '任选一':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rxy_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '红球任选一':
            case '蓝球任选一':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::rxy_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '任选二':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,2,$gtype,$gid,$lottery_number_id);
                break;
            case '红球任选二':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::rx_zhu($unum,$sub_zhu,2,$gtype,$gid,$lottery_number_id);
                break;
            case '任选三':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,3,$gtype,$gid,$lottery_number_id);
                break;
            case '红球任选三':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::rx_zhu($unum,$sub_zhu,3,$gtype,$gid,$lottery_number_id);
                break;
            case '任选四':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,4,$gtype,$gid,$lottery_number_id);
                break;
            case '任选四复式':
            case '红球任选四':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::rx_zhu($unum,$sub_zhu,4,$gtype,$gid,$lottery_number_id);
                break;
            case '任选五':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,5,$gtype,$gid,$lottery_number_id);
                break;
            case '任选五复式':
            case '红球任选五':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::rx_zhu($unum,$sub_zhu,5,$gtype,$gid,$lottery_number_id);
                break;
            case '任选六':
            case '任选六复式':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,6,$gtype,$gid,$lottery_number_id);
                break;
            case '任选七':
            case '任选七复式':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,7,$gtype,$gid,$lottery_number_id);
                break;
            case '任选八':
            case '任选八复式':
                if($gid==13)
                    self:: _check_number_2($unum,1,80,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(5,6,7,8)))
                    self:: _check_number_2($unum,1,11,$gid,$lottery_number_id,$gtype);
                self::rx_zhu($unum,$sub_zhu,8,$gtype,$gid,$lottery_number_id);
                break;
            case '任选九':
            case '任选九复式':
                self::_filter_one_row($unum, 1, 11,$gtype);
                self::rx_zhu($unum,$sub_zhu,9,$gtype,$gid,$lottery_number_id);
                break;
            case '选十中六':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::hq_rx_zhu($unum,$sub_zhu,10);
                break;
            case '选十五中六':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::hq_rx_zhu($unum,$sub_zhu,15);
                break;
            case '选十八中六':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::hq_rx_zhu($unum,$sub_zhu,18);
                break;
            case '选二十中六':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::hq_rx_zhu($unum,$sub_zhu,20);
                break;
            case '选二十五中六':
                self::_filter_one_row($unum, 1, 33,$gtype);
                self::hq_rx_zhu($unum,$sub_zhu,25);
                break;
            case '蓝球任选一':
            case '红球任选一':
                self::_filter_one_row($unum, 1, 16,$gtype);
                self::shs_hqrxy($unum,$sub_zhu);
                break;
            case '任选二胆拖':
                if(in_array($gid, array(5,6,7,8)))
                    self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self::_check_number_dantuo($unum,2,1,20,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,2,$gtype,$gid,$lottery_number_id);
                break;
            case '任选三胆拖':
                if(in_array($gid, array(5,6,7,8)))
                    self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self::_check_number_dantuo($unum,2,1,20,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,3,$gtype,$gid,$lottery_number_id);
                break;
            case '任选四胆拖':
                if(in_array($gid, array(5,6,7,8)))
                    self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self::_check_number_dantuo($unum,2,1,20,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,4,$gtype,$gid,$lottery_number_id);
                break;
            case '任选五胆拖':
                if(in_array($gid, array(5,6,7,8)))
                    self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                else if(in_array($gid, array(9,10)))
                    self::_check_number_dantuo($unum,2,1,20,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,5,$gtype,$gid,$lottery_number_id);
                break;
            case '任选六胆拖':
                self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,6,$gtype,$gid,$lottery_number_id);
                break;
            case '任选七胆拖':
                self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,7,$gtype,$gid,$lottery_number_id);
                break;
            case '任选八胆拖':
                self::_check_number_dantuo($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdt($unum,$sub_zhu,8,$gtype,$gid,$lottery_number_id);
                break;
            case '任选一单式':
                self::_check_number_danshi($unum,1,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,1,$gtype,$gid,$lottery_number_id);
                break;
            case '任选二单式':
                self::_check_number_danshi($unum,2,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,2,$gtype,$gid,$lottery_number_id);
                break;
            case '任选三单式':
                self::_check_number_danshi($unum,3,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,3,$gtype,$gid,$lottery_number_id);
                break;
            case '任选四单式':
                self::_check_number_danshi($unum,4,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,4,$gtype,$gid,$lottery_number_id);
                break;
            case '任选五单式':
                self::_check_number_danshi($unum,5,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,5,$gtype,$gid,$lottery_number_id);
                break;
            case '任选六单式':
                self::_check_number_danshi($unum,6,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,6,$gtype,$gid,$lottery_number_id);
                break;
            case '任选七单式':
                self::_check_number_danshi($unum,7,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,7,$gtype,$gid,$lottery_number_id);
                break;
            case '任选八单式':
                self::_check_number_danshi($unum,8,1,11,$gid,$lottery_number_id,$gtype);
                self::xw_rxdshiy($unum,$sub_zhu,8,$gtype,$gid,$lottery_number_id);
                break;
            case '前三组选胆拖':
            case '后三组选胆拖':
                self::xw_qhsanzxdt($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前二组选胆拖':
            case '后二组选胆拖':
                self::xw_qherzxdt($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;

            case '猜冠军' :
                self::pk10_caiguanjun($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前二名直选复式' :
            case '前二名组选复式' :
            case '前二任选二' :
                self::pk10_qianerzhixuanfushi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前二名直选单式' :
            case '前二名组选单式' :
            case '前二任选二单式' :
                self::pk10_qianerzhixuandanshi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前三名直选复式' :
            case '前三名组选复式' :
            case '前三任选三' :
                self::pk10_qiansanzhixuanfushi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前三名直选单式' :
            case '前三名组选单式' :
            case '前三任选三单式' :
                self::pk10_qiansanzhixuandanshi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前四名直选复式' :
            case '前四名组选复式' :
            case '前四任选四' :
                self::pk10_qiansizhixuanfushi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前四名直选单式' :
            case '前四名组选单式' :
            case '前四任选四单式' :
                self::pk10_qiansizhixuandanshi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前五任选五' :
                self::pk10_qianwuzhixuanfushi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前五定位胆' :
            case '后五定位胆' :
                self::pk10_dingweidan($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '前五任选五单式' :
                self::pk10_qianwuzhixuandanshi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '第一名单双':
            case '第二名单双':
            case '第三名单双':
                self::pk10_danshuang($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
            case '第一名大小':
            case '第二名大小':
            case '第三名大小':
                self::pk10_daxiao($unum,$sub_zhu,$gtype,$gid,$lottery_number_id);
                break;
        }
    }

    public static function  buy_log($lottery_id, $lottery_number_id, $play_way, $msg)
    {

        $lottery_array = C("LOTTERY");
        $lottery = $lottery_array[$lottery_id]["name"];
        $series_number = M("lottery_number_mid")->where(array("id" => $lottery_number_id))->getField("series_number");
        $play_way = $play_way;
        $time = date("Y-m-d H:i:s", time());
        $username = session("SESSION_NAME");
        $content = $msg;
        SeasLog::setLogger("GameValidate");
        SeasLog::info($username . "于" . $time . "购买" . $lottery . $series_number . "期" . $play_way . "玩法号码:" . $content);
    }

    public function pk10_caiguanjun(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        if(strpos($unum," ")!==false){
            $user_num_arr=explode(" ",trim($unum));
        } else {
            $user_num_arr=array(trim($unum));
        }
        $user_num_arr_uniqic = array_unique($user_num_arr);
        if(count($user_num_arr)!=count($user_num_arr_uniqic)){
            self::buy_log($gid,$lottery_number_id,$gtype,$unum."[号码含有重复]");
            exit;
        }
        $mark=false;
        $total_ele=0;
        $len_arr=count($user_num_arr);
        for($i=0;$i<$len_arr;$i++){
            if(!empty($user_num_arr[$i])){
                if($user_num_arr[$i]!=1 && $user_num_arr[$i]!=2 && $user_num_arr[$i]!=3 && $user_num_arr[$i]!=4 && $user_num_arr[$i]!=5 && $user_num_arr[$i]!=6 && $user_num_arr[$i]!=7 && $user_num_arr[$i]!=8 && $user_num_arr[$i]!=9 && $user_num_arr[$i]!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."[号码含有非法字符]");
                    exit;
                }
            }
            if(strlen($user_num_arr[$i])!=1 && $user_num_arr[$i]!=10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."[号码含有非法字符]");
                exit;
            }

            if(strlen($user_num_arr[$i])!=2 && $user_num_arr[$i]==10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误3！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }

        if($len_arr!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误4！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(" ",$user_num_arr);

    }

    public function pk10_danshuang(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        if(strpos($unum," ")!==false){
            $user_num_arr=explode(" ",trim($unum));
        } else {
            $user_num_arr=array(trim($unum));
        }
        $user_num_arr_uniqic = array_unique($user_num_arr);
        if(count($user_num_arr)!=count($user_num_arr_uniqic)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $mark=false;
        $total_ele=0;
        $len_arr=count($user_num_arr);
        for($i=0;$i<$len_arr;$i++){
            if(!empty($user_num_arr[$i])){
                if($user_num_arr[$i]!="单" && $user_num_arr[$i]!="双"){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }

        if($len_arr!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(" ",$user_num_arr);

    }

    public function pk10_daxiao(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        if(strpos($unum," ")!==false){
            $user_num_arr=explode(" ",trim($unum));
        } else {
            $user_num_arr=array(trim($unum));
        }
        $user_num_arr_uniqic = array_unique($user_num_arr);
        if(count($user_num_arr)!=count($user_num_arr_uniqic)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $mark=false;
        $total_ele=0;
        $len_arr=count($user_num_arr);
        for($i=0;$i<$len_arr;$i++){
            if(!empty($user_num_arr[$i])){
                if($user_num_arr[$i]!="大" && $user_num_arr[$i]!="小"){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }

        if($len_arr!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(" ",$user_num_arr);

    }

    public function pk10_qianerzhixuanfushi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        if(strpos($unum,",")!==false) {
            $user_num_arr = explode(",", trim($unum));
        } else {
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误1！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(strpos(trim($user_num_arr[0])," ")!==false) {
            $a1 = explode(" ", trim($user_num_arr[0]));
        } else {
            $a1 = array(trim($user_num_arr[0]));
        }
        if(strpos(trim($user_num_arr[1])," ")!==false) {
            $a2 = explode(" ", trim($user_num_arr[1]));
        } else {
            $a2 = array(trim($user_num_arr[1]));
        }
        $a1_unique = array_unique($a1);
        $a2_unique = array_unique($a2);
        if(count($a1)!=count($a1_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误1！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a2)!=count($a2_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误2！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $count1 = $count2 = 0;
        foreach($a1 as $val){
            ++$count1;
            if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        foreach($a2 as $val){
            ++$count2;
            if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $a3 = array_intersect($a1,$a2);
        $total_zhushu = $count1*$count2-count($a3);
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误5！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    public function pk10_qianerzhixuandanshi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        if(strpos($unum,",")!==false) {
            $user_num_arr = explode(",", trim($unum));
        } else {
            $user_num_arr = array(trim($unum));
        }
        $count1 = 0;
        foreach($user_num_arr as $val){
            $a1 = explode(" ",$val);
            $len = count($a1);
            if($len!=2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            foreach($a1 as $val){
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
            $aa1 = array_unique($a1);
            if(count($a1)!=count($aa1)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            } else {
                $count1++;
            }
        }
        $total_zhushu = $count1;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    public function pk10_qiansanzhixuanfushi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        if(strpos($unum,",")!==false) {
            $user_num_arr = explode(",", trim($unum));
        } else {
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(strpos(trim($user_num_arr[0])," ")!==false) {
            $a1 = explode(" ", trim($user_num_arr[0]));
        } else {
            $a1 = array(trim($user_num_arr[0]));
        }
        if(strpos(trim($user_num_arr[1])," ")!==false) {
            $a2 = explode(" ", trim($user_num_arr[1]));
        } else {
            $a2 = array(trim($user_num_arr[1]));
        }

        if(strpos(trim($user_num_arr[2])," ")!==false) {
            $a3 = explode(" ", trim($user_num_arr[2]));
        } else {
            $a3 = array(trim($user_num_arr[2]));
        }


        $a1_unique = array_unique($a1);
        $a2_unique = array_unique($a2);
        $a3_unique = array_unique($a3);
        if(count($a1)!=count($a1_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a2)!=count($a2_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a3)!=count($a3_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $a4 = array_intersect($a1,$a2,$a3);

        $a5 = array_intersect($a1,$a2);
        $a6 = array_intersect($a1,$a3);
        $a7 = array_intersect($a2,$a3);
        $count1 = $count2 = $count3=0;
        foreach($a1 as $val){
            $count1++;
            if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        foreach($a2 as $val){
            $count2++;
            if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        foreach($a3 as $val){
            $count3++;
            if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $total_zhushu = $count1*$count2*$count3-$count3*count($a5)-$count2*count($a6)-$count1*count($a7)+count($a4)*2;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    public function pk10_qiansanzhixuandanshi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        if(strpos($unum,",")!==false) {
            $user_num_arr = explode(",", trim($unum));
        } else {
            $user_num_arr = array($unum);
        }
        $count1 = 0;
        foreach($user_num_arr as $val){
            $a1 = explode(" ",$val);
            $len = count($a1);
            if($len!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            foreach($a1 as $val){
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
            $aa1 = array_unique($a1);
            if(count($a1)!=count($aa1)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            } else {
                $count1++;
            }
        }
        $total_zhushu = $count1;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    public function pk10_qiansizhixuanfushi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        $user_num_arr=explode(",",trim($unum));
        if(strpos(trim($user_num_arr[0])," ")!==false)
            $a1 = explode(" ",trim($user_num_arr[0]));
        else
            $a1 = array(trim($user_num_arr[0]));
        if(strpos(trim($user_num_arr[1])," ")!==false)
            $a2 = explode(" ",trim($user_num_arr[1]));
        else
            $a2 = array(trim($user_num_arr[1]));
        if(strpos(trim($user_num_arr[2])," ")!==false)
            $a3 = explode(" ",trim($user_num_arr[2]));
        else
            $a3 = array(trim($user_num_arr[2]));
        if(strpos(trim($user_num_arr[3])," ")!==false)
            $a4 = explode(" ",trim($user_num_arr[3]));
        else
            $a4 = array(trim($user_num_arr[3]));

        $a1_unique = array_unique($a1);
        $a2_unique = array_unique($a2);
        $a3_unique = array_unique($a3);
        $a4_unique = array_unique($a4);
        if(count($a1)!=count($a1_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a2)!=count($a2_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a3)!=count($a3_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a4)!=count($a4_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $count1 = $count2 = $count3=$count4 = 0;
        foreach($a1 as $val){
            $count1++;
            if(!in_array($val,array(1,2,3,4,5,6,7,8,9,10))){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        foreach($a2 as $val){
            $count2++;
            if(!in_array($val,array(1,2,3,4,5,6,7,8,9,10))){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        foreach($a3 as $val){
            $count3++;
            if(!in_array($val,array(1,2,3,4,5,6,7,8,9,10))){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        foreach($a4 as $val){
            $count4++;
            if(!in_array($val,array(1,2,3,4,5,6,7,8,9,10))){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $total_zhushu = $count1*$count2*$count3*$count4;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    public function pk10_qiansizhixuandanshi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        $user_num_arr=explode(",",trim($unum));
        $count1 = 0;
        foreach($user_num_arr as $val){
            $a1 = explode(" ",$val);
            $len = count($a1);
            if($len!=4){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            foreach($a1 as $v){
                if(!in_array($v,array(1,2,3,4,5,6,7,8,9,10))){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
            $aa1 = array_unique($a1);
            if(count($a1)!=count($aa1)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            } else {
                $count1++;
            }
        }
        $total_zhushu = $count1;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }


    public function pk10_qianwuzhixuanfushi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        $user_num_arr=explode(",",trim($unum));
        if(strpos(trim($user_num_arr[0])," ")!==false)
            $a1 = explode(" ",trim($user_num_arr[0]));
        else
            $a1 = array(trim($user_num_arr[0]));
        if(strpos(trim($user_num_arr[1])," ")!==false)
            $a2 = explode(" ",trim($user_num_arr[1]));
        else
            $a2 = array(trim($user_num_arr[1]));
        if(strpos(trim($user_num_arr[2])," ")!==false)
            $a3 = explode(" ",trim($user_num_arr[2]));
        else
            $a3 = array(trim($user_num_arr[2]));
        if(strpos(trim($user_num_arr[3])," ")!==false)
            $a4 = explode(" ",trim($user_num_arr[3]));
        else
            $a4 = array(trim($user_num_arr[3]));
        if(strpos(trim($user_num_arr[4])," ")!==false)
            $a5 = explode(" ",trim($user_num_arr[4]));
        else
            $a5 = array(trim($user_num_arr[4]));

        $a1_unique = array_unique($a1);
        $a2_unique = array_unique($a2);
        $a3_unique = array_unique($a3);
        $a4_unique = array_unique($a4);
        $a5_unique = array_unique($a5);
        if(count($a1)!=count($a1_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误1！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a2)!=count($a2_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误2！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a3)!=count($a3_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误3！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a4)!=count($a4_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误4！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a5)!=count($a5_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误5！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $count1 = $count2 = $count3=$count4 = $count5=0;
        if(!empty($a1[0])) {
            foreach ($a1 as $val) {
                $count1++;
                if (!in_array($val, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) {
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum . "注数:" . $sub_zhu);
                    echo json_encode(array("status" => 0, "info" => "错误6！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a2[0])) {
            foreach ($a2 as $val) {
                $count2++;
                if (!in_array($val, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) {
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum . "注数:" . $sub_zhu);
                    echo json_encode(array("status" => 0, "info" => "错误7！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a3[0])) {
            foreach ($a3 as $val) {
                $count3++;
                if (!in_array($val, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) {
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum . "注数:" . $sub_zhu);
                    echo json_encode(array("status" => 0, "info" => "错误8！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a4[0])) {
            foreach ($a4 as $val) {
                $count4++;
                if (!in_array($val, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) {
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum . "注数:" . $sub_zhu);
                    echo json_encode(array("status" => 0, "info" => "错误9！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a5[0])) {
            foreach ($a5 as $val) {
                $count5++;
                if (!in_array($val, array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10))) {
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum . "注数:" . $sub_zhu);
                    echo json_encode(array("status" => 0, "info" => "错误10！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        $total_zhushu = $count1*$count2*$count3*$count4*$count5;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误11！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }


    public function pk10_dingweidan(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        if(strpos($unum,",")!==false) {
            $user_num_arr = explode(",", trim($unum));
        } else {
            $user_num_arr = array(trim($unum));
        }
        if(strpos(trim($user_num_arr[0])," ")!==false)
            $a1 = explode(" ",trim($user_num_arr[0]));
        else {
            $a1 = array(trim($user_num_arr[0]));
        }
        if(strpos(trim($user_num_arr[1])," ")!==false)
            $a2 = explode(" ",trim($user_num_arr[1]));
        else {
            $a2 = array(trim($user_num_arr[1]));
        }
        if(strpos(trim($user_num_arr[2])," ")!==false)
            $a3 = explode(" ",trim($user_num_arr[2]));
        else {
            $a3 = array(trim($user_num_arr[2]));
        }
        if(strpos(trim($user_num_arr[3])," ")!==false)
            $a4 = explode(" ",trim($user_num_arr[3]));
        else {
            $a4 = array(trim($user_num_arr[3]));
        }
        if(strpos(trim($user_num_arr[4])," ")!==false)
            $a5 = explode(" ",trim($user_num_arr[4]));
        else {
            $a5 = array(trim($user_num_arr[4]));
        }

        $a1_unique = array_unique($a1);
        $a2_unique = array_unique($a2);
        $a3_unique = array_unique($a3);
        $a4_unique = array_unique($a4);
        $a5_unique = array_unique($a5);
        if(count($a1)!=count($a1_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误1！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a2)!=count($a2_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误2！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a3)!=count($a3_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误3！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a4)!=count($a4_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误4！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($a5)!=count($a5_unique)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误5！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $count1 = $count2 = $count3=$count4 = $count5=0;
        if(!empty($a1[0])) {
            foreach ($a1 as $val) {
                $count1++;
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a2[0])) {
            foreach ($a2 as $val) {
                $count2++;
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a3[0])) {
            foreach ($a3 as $val) {
                $count3++;
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a4[0])) {
            foreach ($a4 as $val) {
                $count4++;
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        if(!empty($a5[0])) {
            foreach ($a5 as $val) {
                $count5++;
                if($val!=1 && $val!=2 && $val!=3 && $val!=4 && $val!=5 && $val!=6 && $val!=7 && $val!=8 && $val!=9 && $val!=10){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
        }
        $total_zhushu = $count1+$count2+$count3+$count4+$count5;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误11！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    public function pk10_qianwuzhixuandanshi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id) {
        $user_num_arr=explode(",",trim($unum));
        $count1 = 0;
        foreach($user_num_arr as $val){
            $a1 = explode(" ",$val);
            $len = count($a1);
            if($len!=5){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            foreach($a1 as $v){
                if(!in_array($v,array(1,2,3,4,5,6,7,8,9,10))){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                    echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
            $aa1 = array_unique($a1);
            if(count($a1)!=count($aa1)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
                echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            } else {
                $count1++;
            }
        }
        $total_zhushu = $count1;
        if($total_zhushu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"错误！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }

    //过滤定位胆玩法
    public static function _filter_dingwei($inputNumber,$min,$max,$gid,$lottery_number_id,$gtype){
        $buy_number_arr = explode(",", $inputNumber);
        if(in_array($gid,array(5,6,7,8))) {
            if (count($buy_number_arr) > 5) {
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status" => 0, "info" => "提交非法数据","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        } else if(in_array($gid,array(9,10))){
            if (count($buy_number_arr) > 8) {
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status" => 0, "info" => "提交非法数据","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
        $temp = array();
        foreach ($buy_number_arr as $k=>$v){
            $a = explode(" ",$v);
            $len = count($a);
            for ($i=0; $i<$len;$i++){
                if(!empty($a[$i])){
                    if($a[$i]<$min OR $a[$i]>$max){
                        self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                        echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                        exit;
                    }
                    if(!ctype_digit($a[$i])){
                        self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                        echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                        exit;
                    }
                    array_push($temp,$a[$i]);
                } else {
                    continue;
                }
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            $temp = array();
        }
    }

    public  function _check_number_dantuo($inputNumber,$len,$min,$max,$gid,$lottery_number_id,$gtype){
        $temp_num_arr=explode(",",$inputNumber);
        $_a_1 = $temp_num_arr[0];
        $_a_2 = $temp_num_arr[1];
        $_a_1_2 = array_intersect($_a_1,$_a_2);
        if(count($_a_1_2)>0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($temp_num_arr)!=$len){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $a = explode(" ",$v);
            $len = count($a);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($a[$i]<$min OR $a[$i]>$max){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                if(!ctype_digit($a[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                array_push($temp,$a[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
    }

    public static function _check_number($inputNumber,$len,$min,$max,$gid,$lottery_number_id,$gtype){
        $temp_num_arr=explode(",",$inputNumber);
        if(count($temp_num_arr)!=$len){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $a = explode(" ",$v);
            $len = count($a);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($a[$i]<$min OR $a[$i]>$max){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据，将冻结平台帐号2！"));
                    exit;
                }
                if(!ctype_digit($a[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                array_push($temp,$a[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }
    }

    /*
     *
     * 针对单行复选的选号判断
     *
     * */
    public static function  _check_number_2($inputNumber,$min,$max,$gid,$lottery_number_id,$gtype){
        $temp_num_arr=explode(",",$inputNumber);
        $array_unique = array_unique($temp_num_arr);
        $len = count($temp_num_arr);
        $blen = count($array_unique );
        if($len!=$blen) {
        		self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $temp = array();
        for($i=0;$i<$len;$i++){
            $_a = explode(" ",$temp_num_arr[$i]);
            foreach($_a as $_v){
                if($_v<$min OR $_v>$max){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                if(!ctype_digit($_v)){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
            }
            array_push($temp,$temp_num_arr[$i]);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
    }

    /*
     *
     *针对单式进行号码判断
     *
     * */
    public static function _check_number_danshi($inputNumber,$len,$_min,$_max,$gid,$lottery_number_id,$gtype){
        if(strpos($inputNumber,",")!==false){
            $numArray=explode(",",$inputNumber);
        }elseif(strpos($inputNumber,";")!==false){
            $numArray=explode(";",$inputNumber);
        } else {
            $numArray = array($inputNumber);
        }
        //验证开始
        foreach($numArray as $key=>$value){
            $arr = explode(" ",$value);
            $str_len = count($arr);
            if($str_len!=$len){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            $_a_1 = array_unique($arr);
            if(count($_a_1)!=$len){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            $max = $arr[array_search(max($arr),$arr)];
            $min = $arr[array_search(min($arr),$arr)];
            if($max>$_max || $min<$_min){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            foreach($arr as $v) {
                if (!ctype_digit($v)) {
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status" => 0, "info" => "提交非法数据"));
                    exit;
                }
            }
        }
    }

    //过滤直选单式号码
    public static function _filter_ds($inputNumber,$min,$max,$len){
        $temp_num_arr = trim($inputNumber);
        $num_array = explode(",", $temp_num_arr);
        $temp = array();
        foreach($num_array as $k=>$v){
            $vv = explode(" ", $v);
            if(count($vv)!=$len){
                echo json_encode(array('info'=>'数据格式不正确！'));
                exit;
            }
            foreach ($vv as $s=>$sv){
                if($sv<$min OR $sv>$max){
                    echo json_encode(array('info'=>'数据格式不正确！'));
                    exit;
                }
                if(!ctype_digit($sv)){
                    echo json_encode(array('info'=>'数据格式不正确！'));
                    exit;
                }
                unset($temp);
                array_push($temp,$sv);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                echo json_encode(array('info'=>'数据格式不正确！'));
                exit;
            }
        }

    }

    //过滤2行选号的
    public static function _filter_two_row($inputNumber,$min,$max,$gtype)
    {
        $buy_number_arr = explode(",", $inputNumber);
        $temp = array();
        if(strpos("定位胆", $gtype)===false){
            foreach ($buy_number_arr as $k=>$v){
                $num = explode(" ", $v);
                foreach ($num as $kk=>$vv){
                    if($vv<$min OR $vv>$max){
                        echo json_encode(array('info'=>'数据格式不正确！'));
                        exit;
                    }
                    if(!ctype_digit($vv)){
                        echo json_encode(array('info'=>'数据格式不正确！'));
                        exit;
                    }
                    unset($temp);
                    array_push($temp,$vv);
                }
                $count1 = count($temp);
                $temp_unique = array_unique($temp);
                $count2 = count($temp_unique);
                if($count1>$count2){
                    echo json_encode(array('info'=>'数据格式不正确！'));
                    exit;
                }
            }
        } else {
            foreach ($buy_number_arr as $k=>$v){
                $num = explode(" ", $v);
                foreach ($num as $kk=>$vv){
                    if(!empty($vv)){
                        if($vv<$min OR $vv>$max){
                            echo json_encode(array('info'=>'数据格式不正确！'));
                            exit;
                        }
                        if(!ctype_digit($vv)){
                            echo json_encode(array('info'=>'数据格式不正确！'));
                            exit;
                        }
                        unset($temp);
                        array_push($temp,$vv);
                    } else {
                        continue;
                    }
                }
                $count1 = count($temp);
                $temp_unique = array_unique($temp);
                $count2 = count($temp_unique);
                if($count1>$count2){
                    echo json_encode(array('info'=>'数据格式不正确！'));
                    exit;
                }
            }
        }
    }

    //过滤1行选号的
    public static function _filter_one_row($inputNumber,$min,$max,$gtype){
        $temp_num_arr = trim($inputNumber);
        $num_array = explode(" ", $temp_num_arr);
        $temp = array();
        foreach($num_array as $k=>$v){
            if($v<$min OR $v>$max){
                echo json_encode(array('info'=>'数据格式不正确！'));
                exit;
            }
            if(!ctype_digit($v)){
                echo json_encode(array('info'=>'数据格式不正确！'));
                exit;
            }
            array_push($temp,$v);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            echo json_encode(array('info'=>'数据格式不正确！'));
            exit;
        }
    }

    //过滤11选5文字［定单双］
    public static function _filter_two_row_11X5($inputNumber,$gid,$lottery_number_id,$gtype)
    {
        $wordArray = array("5单0双","4单1双","3单2双","2单3双","1单4双","0单5双");
        $temp_num_arr = trim($inputNumber);
        $num_array = explode(" ", $temp_num_arr);
        $temp = array();
        foreach ($num_array as $k=>$v){
            if(!in_array($v, $wordArray)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            array_push($temp, $v);
        }
        $count1 = count($num_array);
        $array_unick = array_unique($temp);
        $count2 = count($array_unick);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
    }

    /*
     *
     * 定位胆_filter_dingwei($inputNumber,$min,$max,$gid,$lottery_number_id,$gtype)
     *
     */
    public function dwd_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $user_num_arr=explode(",",trim($unum));
        $mark=false;
        $total_ele=0;
        $len_arr=count($user_num_arr);
        for($i=0;$i<$len_arr;$i++){
            if(!empty($user_num_arr[$i])){
                $a = explode(" ",$user_num_arr[$i]);
                $str_len = count($a);
                $total_ele += $str_len;
            }
        }

        if($total_ele!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);

    }


    function xw_qhsanzxdt(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){


        $temp_num_arr=explode(",",$unum);
        $dh_arr=explode(" ",$temp_num_arr[0]);
        $th_arr=explode(" ",$temp_num_arr[1]);
        $_a = array_intersect($dh_arr,$th_arr);
        if(count($_a)>0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($temp_num_arr)!=2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $_a = explode(" ",$v);
            $len = count($_a);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($_a[$i]<1 OR $_a[$i]>11){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                if(!ctype_digit($_a[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                array_push($temp,$_a[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }

        $new_dh_arr=array();
        $i=0;
        foreach($dh_arr AS $val){

            if(ctype_digit($val)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;


            }
            if(in_array($val,$th_arr)){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            $new_dh_arr[]=trim(intval($val));
            $i++;
        }
        if($i>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $new_th_arr=array();
        $j=0;
        foreach($th_arr AS $val){

            if(ctype_digit($val)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;


            }
            $new_th_arr[]=trim(intval($val));
            $j++;
        }




        $temp_zhu=1;
        if($i==2){

            $temp_zhu=$j;
            if($temp_zhu<1){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }elseif($i==1){

            $sec_len=$j;
            if($sec_len<2){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }


            $den=$sec_len*($sec_len-1);

            $ele=1;
            for($i=2;$i>=1;$i--){

                $ele*=$i;
            }
            $temp_zhu=$den/$ele;

        }


        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $new_dh_str=implode(" ",$new_dh_arr);
        $new_th_str=implode(" ",$new_th_arr);
        $unum=$new_dh_str.",".$new_th_str;

    }

    function xw_qherzxdt(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $temp_num_arr=explode(",",$unum);
        $dh_arr=explode(" ",$temp_num_arr[0]);
        $th_arr=explode(" ",$temp_num_arr[1]);
        $_a = array_intersect($dh_arr,$th_arr);
        if(count($_a)>0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        if(count($temp_num_arr)!=2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $_a = explode(" ",$v);
            $len = count($_a);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($_a[$i]<1 OR $_a[$i]>11){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                if(!ctype_digit($_a[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;
                }
                array_push($temp,$_a[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
        }

        $new_dh_arr=array();
        $i=0;
        foreach($dh_arr AS $val){

            if(ctype_digit($val)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;


            }
            if(in_array($val,$th_arr)){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            $new_dh_arr[]=trim(intval($val));
            $i++;
        }
        if($i>1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }


        $new_th_arr=array();
        $j=0;
        foreach($th_arr AS $val){

            if(ctype_digit($val)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;


            }
            $new_th_arr[]=trim(intval($val));
            $j++;
        }




        $temp_zhu=$j;
        if($temp_zhu<1){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }



        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $new_dh_str=implode(" ",$new_dh_arr);
        $new_th_str=implode(" ",$new_th_arr);
        $unum=$new_dh_str.",".$new_th_str;

    }
    function shslqxy_zhu(&$unum,$sub_zhu){


        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(" ",$unum);
        if(count($user_num_arr)!=$sub_zhu){
            echo json_encode(array('info'=>'数据的注数不正确！',"moshi"=>session("model".session("SESSION_ID"))));
            //echo '数据的注数不正确！';
            exit;


        }



    }



    function shs_hqrxy(&$unum,$sub_zhu){

        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(" ",$unum);
        if(count($user_num_arr)!=$sub_zhu){


            echo json_encode(array('info'=>'数据的注数不正确！',"moshi"=>session("model".session("SESSION_ID"))));

            exit;


        }


    }



//前三直选复式
    function qszhxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $user_num_arr=explode(",",$unum);

        if(count($user_num_arr)<3){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }



        if(empty($user_num_arr[0]) OR empty($user_num_arr[1]) OR empty($user_num_arr[2])){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $fir_arr=explode(" ",rtrim($user_num_arr[0]));

        $sec_arr=explode(" ",rtrim($user_num_arr[1]));

        $thr_arr=explode(" ",rtrim($user_num_arr[2]));

        $fir_count=count($fir_arr);

        $sec_count=count($sec_arr);

        $thr_count=count($thr_arr);

        $res_arr=array();

        $mark=0;

        for($l=0;$l<$fir_count;$l++){

            for($i=0;$i<$sec_count;$i++){

                for($j=0;$j<$thr_count;$j++){

                    if(ctype_digit((String)$fir_arr[$l])==false OR  ctype_digit((String)$sec_arr[$i])==false OR ctype_digit((String)$thr_arr[$j])==false){
                        self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                        echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                        exit;

                    }

                    $fir_arr[$l]=intval(trim($fir_arr[$l]));

                    $sec_arr[$i]=intval(trim($sec_arr[$i]));

                    $thr_arr[$j]=intval(trim($thr_arr[$j]));

                    if( $fir_arr[$l]==$sec_arr[$i] OR $fir_arr[$l]==$thr_arr[$j] OR $sec_arr[$i]==$thr_arr[$j]){

                        continue;

                    }

                    $mark++;



                }

            }

        }
        if($mark==0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        if($mark!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $fir_str=implode(" ",$fir_arr);
        $sec_str=implode(" ",$sec_arr);
        $thr_str=implode(" ",$thr_arr);

        $unum=$fir_str.",".$sec_str.",".$thr_str;
    }
//前三直选单式
    function qszhxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $temp_num=trim($unum);

        $temp_num_arr=array();
        if(strpos($temp_num,",")!==false){
            $temp_num_arr=explode(",",$temp_num);
        }elseif(strpos($temp_num,";")!==false){
            $temp_num_arr=explode(";",$temp_num);
        }else{

            $temp_num_arr[0]=$temp_num;

        }

        //$temp_num_arr=explode(",",$temp_num);
        $temp_count=count($temp_num_arr);

        if($temp_count!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $user_num_arr=array();
        for($i=0;$i<$temp_count;$i++){

            $user_num_arr[$i]=preg_replace("/\s+/"," ",$temp_num_arr[$i]);
            $temp_arr1=explode(" ",$user_num_arr[$i]);
            foreach ($temp_arr1 AS $val){

                if(ctype_digit($val)==false){

                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;

                }

            }
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);


    }

//前二直选单式
    function qezhxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $temp_num=trim($unum);


        if(strpos($temp_num,",")!==false){
            $temp_num_arr=explode(",",$temp_num);
        }elseif(strpos($temp_num,";")!==false){
            $temp_num_arr=explode(";",$temp_num);
        }else{

            $temp_num_arr[0]=$temp_num;

        }

        //$temp_num_arr=explode(",",$temp_num);
        $temp_count=count($temp_num_arr);
        if($temp_count!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $user_num_arr=array();
        for($i=0;$i<$temp_count;$i++){

            $user_num_arr[$i]=preg_replace("/\s+/"," ",$temp_num_arr[$i]);
            $temp_arr1=explode(" ",$user_num_arr[$i]);
            foreach ($temp_arr1 AS $val){

                if(ctype_digit($val)==false){

                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;

                }

            }
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }


//前三组选
    function qszx_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($len_user_num_arr<3){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $count_zhu=$len_user_num_arr*($len_user_num_arr-1)*($len_user_num_arr-2)/6;

        if($count_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $temp_arr=array();
        for($l=0;$l<$len_user_num_arr;$l++){

            if(ctype_digit($user_num_arr[$l])==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;

            }
            $temp_arr[]=trim($user_num_arr[$l]);


        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(" ",$temp_arr);



    }

    function qsdx_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        if(strpos($unum,",")!==false){
            $numArray=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $numArray=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $numArray=explode(" ",$unum);
        }
        //验证开始
        foreach($numArray as $key=>$value){
            $arr = explode(" ",$value);
            $str_len = count($arr);
            $_a_1 = array_unique($arr);
            if($str_len!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            if(count($_a_1)!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;
            }
            $max = $arr[array_search(max($arr),$arr)];
            $min = $arr[array_search(min($arr),$arr)];
            if($max>11 || $min<1){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据".$max));
                exit;
            }
            foreach($arr as $val) {
                if (!ctype_digit($val)) {
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum);
                    echo json_encode(array("status" => 0, "info" => "提交非法数据"));
                    exit;
                }
            }
        }

        $user_num_arr=$numArray;

        $count_zhu=count($user_num_arr);

        if($count_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);
    }



//前二直选数据格式检查函数

    function qezhxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){


        $user_num_arr=explode(",",$unum);

        if(count($user_num_arr)<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }



        if(empty($user_num_arr[0]) OR empty($user_num_arr[1])){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $fir_arr=explode(" ",$user_num_arr[0]);

        $sec_arr=explode(" ",$user_num_arr[1]);



        $fir_count=count($fir_arr);

        $sec_count=count($sec_arr);



        $res_arr=array();

        $mark=0;

        for($l=0;$l<$fir_count;$l++){

            for($i=0;$i<$sec_count;$i++){


                if(ctype_digit($fir_arr[$l])==false OR ctype_digit($sec_arr[$i])==false){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                    exit;

                }
                $fir_arr[$l]=trim($fir_arr[$l]);

                $sec_arr[$i]=trim($sec_arr[$i]);


                if( $fir_arr[$l]==$sec_arr[$i]){

                    continue;

                }

                $mark++;





            }

        }

        if($mark==0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        if($mark!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $fir_str=implode(" ",$fir_arr);
        $sec_str=implode(" ",$sec_arr);


        $unum=$fir_str.",".$sec_str;
    }


//前二组选
    function qezx_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){

        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($len_user_num_arr<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $count_zhu=$len_user_num_arr*($len_user_num_arr-1)/2;

        if($count_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $temp_arr=array();
        for($l=0;$l<$len_user_num_arr;$l++){

            if(is_numeric($user_num_arr[$l])==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;

            }
            $temp_arr[]=intval(trim($user_num_arr[$l]));


        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(" ",$temp_arr);



    }
//前三不定位,猜中位,

    function czhw_and_qsbdw(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){


        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($sub_zhu!=$len_user_num_arr){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $temp_arr=array();

        for($l=0;$l<$len_user_num_arr;$l++){

            if(is_numeric($user_num_arr[$l])==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;

            }
            $temp_arr[]=intval(trim($user_num_arr[$l]));

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $unum=implode(" ",$temp_arr);

    }


//定位胆

    function qsbdw_zhu(&$unum,$sub_zhu,$gid){

        $user_num_arr=explode(",",$unum);

        $len_user_num_arr=count($user_num_arr);
        if(intval($gid)==10 || intval($gid) == 9){
            if($len_user_num_arr!=8){
                echo json_encode(array('info'=>'提交数据格式不正确！'));
                //echo "提交数据格式不正确";
                exit;
            }
        } else {
            if($len_user_num_arr!=5){
                echo json_encode(array('info'=>'提交数据格式不正确！'));
                //echo "提交数据格式不正确";
                exit;
            }
        }

        $mark=false;
        $total_ele=0;
        if(intval($gid)==10 || intval($gid) == 9){
            for($i=0;$i<8;$i++){


                if(empty($user_num_arr[$i])){

                    continue;

                }else{
                    $user_num_arr[$i]=trim($user_num_arr[$i]);
                    $user_num_arr[$i]=preg_replace("/\s+/"," ",$user_num_arr[$i]);
                    $temp_arr=explode(" ",$user_num_arr[$i]);
                    $total_ele+=count($temp_arr);

                }



            }
        } else {
            for($i=0;$i<5;$i++){


                if(empty($user_num_arr[$i])){

                    continue;

                }else{
                    $user_num_arr[$i]=trim($user_num_arr[$i]);
                    $user_num_arr[$i]=preg_replace("/\s+/"," ",$user_num_arr[$i]);
                    $temp_arr=explode(" ",$user_num_arr[$i]);
                    $total_ele+=count($temp_arr);

                }



            }
        }


        if($total_ele!=$sub_zhu){
            echo json_encode(array('info'=>'数据的注数不正确！',"moshi"=>session("model".session("SESSION_ID"))));

            exit;

        }

        $unum=preg_replace("/\s+/"," ",trim($unum));

    }



//定单双

    function ddsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){



        $unum=rtrim($unum);
        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($len_user_num_arr==0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        if($len_user_num_arr!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }

    }



//任选一

    function rxy_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id){



        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($len_user_num_arr==0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        if($len_user_num_arr!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }

    }



//任选二

    function rxer_zhu(&$unum,$sub_zhu){



        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($len_user_num_arr<2){
            echo json_encode(array('info'=>'提交数据格式不正确！'));
            // echo "提交数据格式不正确";
            exit;

        }
        $total_ele=$len_user_num_arr*($len_user_num_arr-1)/2;
        if($total_ele!=$sub_zhu){
            echo json_encode(array('info'=>'数据的注数不正确！',"moshi"=>session("model".session("SESSION_ID"))));
            //echo "数据的注数不正确！";
            exit;

        }


    }


//任选三

    function rx_zhu(&$unum,$sub_zhu,$length,$gtype,$gid,$lottery_number_id){
        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(" ",$unum);

        foreach($user_num_arr as $val){
            $num = intval($val);
            $temp[] = $num;
        }
        $user_num_arr = $temp;
        $arry_uniqu = array_unique($user_num_arr);

        if(count($user_num_arr)!=count($arry_uniqu)){
            self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum);
            echo json_encode(array("status" => 0, "info" => "提交非法数据，将冻结平台帐号a！"));
            exit;
        }

        $len_user_num_arr=count($user_num_arr);

        if($gid==13){
            $max = 80;
            $min = 1;
        } else if(in_array($gid,array(5,6,7,8))){
            $max = 11;
            $min = 1;
        }
        $temp = array();
        foreach($user_num_arr as $va) {
            $va = intval($va);
            if ($va>$max && $va<=$min) {
                self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum);
                echo json_encode(array("status" => 0, "info" => "提交非法数据，将冻结平台帐号a！"));
                exit;
            }
            $temp[] = $va;
        }

        $temp_val=1;
        $diff=$len_user_num_arr-$length;
        //算分子的值
        for($i=1;$i<=$length;$i++){

            $temp_val*=$i;

        }


        $temp_val1=1;
        //算分母的值
        for($j=$len_user_num_arr;$j>$diff;$j--){

            $temp_val1*=$j;

        }

        $total_ele=$temp_val1/$temp_val;

        if($total_ele!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }

        $unum = implode(" ",$temp);

    }

    function xw_rxdt(&$unum,$sub_zhu,$len,$gtype,$gid,$lottery_number_id){

        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(",",trim($unum));

        if(count($user_num_arr)!=2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $dm_arr=explode(" ",trim($user_num_arr[0]));
        $tm_arr=explode(" ",trim($user_num_arr[1]));
        $unin_arr=array_intersect($dm_arr,$tm_arr);
        if(count($unin_arr)){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }
        $dm_len=count($dm_arr);
        $tm_len=count($tm_arr);
        if(($dm_len+$tm_len)<$len){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }

        if($dm_len>($len-1) OR $dm_len==0 OR $tm_len==0){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;
        }
        //任选几，看看胆码选了几个号码，还剩余几个号码。
        $cha_num=$len-$dm_len;
        $den=1;
        for($i=$tm_len;$i>=($tm_len-$cha_num+1);$i--){

            $den*=$i;
        }
        $ele=1;
        for($j=$cha_num;$j>=1;$j--){

            $ele*=$j;
        }
        $temp_zhu=$den/$ele;

        if($sub_zhu!=$temp_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
            exit;

        }

        $new_haoma="";

        foreach($dm_arr AS $val){

            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;

            }

            $new_haoma.=trim($val)." ";
        }
        $new_haoma=trim($new_haoma);
        $new_haoma1="";

        foreach($tm_arr AS $val){

            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"提交非法数据！","moshi"=>session("model".session("SESSION_ID"))));
                exit;

            }

            $new_haoma1.=trim($val)." ";
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }
        $new_haoma1=trim($new_haoma1);

        $unum=$new_haoma.",".$new_haoma1;

    }

    function xw_rxdshi(&$unum,$sub_zhu,$len){
        $unum=preg_replace("/\s+/"," ",trim($unum));
        $unum_arr=explode(",",$unum);
        if(strpos($unum,",")!==false){

            $new_arr=array();
            $new_str="";
            foreach($unum_arr AS $val){

                $temp_arr=explode(" ",trim($val));
                if(count($temp_arr)!=$len){

                    echo json_encode(array('info'=>'提交数据格式不正确！'));
                    exit;
                }

                foreach($temp_arr AS $val1){

                    if(ctype_digit($val1)==false){

                        echo json_encode(array('info'=>'提交数据格式不正确！'));
                        exit;
                    }
                    if($val1>11 OR  $val1<1){

                        echo json_encode(array('info'=>'提交数据格式不正确！'));
                        exit;
                    }
                    $tem=intval($val1);
                    $new_str.=" ".$tem;

                }
                $new_str=trim($new_str);
                $new_arr[]=$new_str;
                $new_str="";

            }
            $unum=implode(",",$new_arr);
        }else{

            $unum=preg_replace("/\s+/"," ",trim($unum));
        }

        if(count($unum_arr)!=$sub_zhu){

            echo json_encode(array('info'=>'数据的注数不正确！',"moshi"=>session("model".session("SESSION_ID"))));
            //echo "数据的注数不正确！";
            exit;
        }



    }





    function xw_rxdshiy(&$unum,$sub_zhu,$len,$gtype,$gid,$lottery_number_id){
        $unum=preg_replace("/\s+/"," ",trim($unum));
        $unum_arr=explode(",",$unum);
        $array_unique = array_unique($unum_arr);
        $lenb = count($unum_arr);
        $blen = count($array_unique);
        if($lenb!=$blen){
            self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum);
            echo json_encode(array("status" => 0, "info" => "提交非法数据，将冻结平台帐号A！"));
            exit;
        }
        if(strpos($unum,",")!==false){

            $new_arr=array();
            $new_str="";
            foreach($unum_arr AS $val){
                $_a = explode(" ",$val);
                if(count($_a)!=$len){
                    self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum);
                    echo json_encode(array("status" => 0, "info" => "提交非法数据，将冻结平台帐号B！"));
                    exit;
                }
                foreach($_a as $v) {
                    if (ctype_digit($v) == false) {

                        self::buy_log($gid, $lottery_number_id, $gtype, "试图提交异常号码:" . $unum);
                        echo json_encode(array("status" => 0, "info" => "提交非法数据，将冻结平台帐号C！"));
                        exit;
                    }
                    if($v>11 OR  $v<1){

                        self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                        echo json_encode(array("status"=>0,"info"=>"提交非法数据，将冻结平台帐号D！"));
                        exit;
                    }
                }
                $new_arr[]=$val;
            }
            $unum=implode(",",$new_arr);
        }else{

            $unum=preg_replace("/\s+/"," ",trim($unum));
        }

        if(count($unum_arr)!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"提交非法数据，将冻结平台帐号E！"));
            exit;
        }
        $check_zhushu_res = M("ssc_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) && !empty($activegx)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"活动期间，本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注"));
                exit;
            }
        }


    }





//红球选十中六等等

    function hq_rx_zhu(&$unum,$sub_zhu,$length){



        $unum=preg_replace("/\s+/"," ",trim($unum));
        $user_num_arr=explode(" ",$unum);

        $len_user_num_arr=count($user_num_arr);

        if($len_user_num_arr<$length){
            echo json_encode(array('info'=>'提交数据格式不正确！'));
            //echo "提交数据格式不正确";
            exit;

        }
        $temp_val=1;
        $diff=$len_user_num_arr-$length;
        //算分子的值
        for($i=1;$i<=$diff;$i++){

            $temp_val*=$i;

        }


        $temp_val1=1;
        //算分母的值
        for($j=$len_user_num_arr;$j>$length;$j--){

            $temp_val1*=$j;

        }

        $total_ele=$temp_val1/$temp_val;

        if($total_ele!=$sub_zhu){
            echo json_encode(array('info'=>'数据的注数不正确！',"moshi"=>session("model".session("SESSION_ID"))));
            //echo "数据的注数不正确！";
            exit;

        }


    }

    function do_limit_action($uid,$gid,$gnum,$gtname,$ml,$gno){//游戏ID，购买号码，玩法，倍数,金额限制

        global $db,$g_eleven,$limit_amount,$memcachedon,$mem;

        $ip=$_SERVER['REMOTE_ADDR'];
        $key=md5($ip.$gnum.$gid.$gno.$ml);







        if($gtname=="任选五" OR $gtname=="任选六" OR $gtname=="任选七" OR $gtname=="任选八"){

            $gnum=preg_replace("/\s+/"," ",trim($gnum));
            $user_num_arr=explode(" ",$gnum);
            sort($user_num_arr);
            $u_arr=array();

            self::zuhe(5,$user_num_arr,$u_arr);
            $count=count($u_arr);
            $jj=intval($g_eleven[80]["$gtname"]*$ml);


            if($jj>$limit_amount){
                echo json_encode(array('info'=>'由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。！'));
                // echo '由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。';
                exit;

            }



            for($i=0;$i<$count;$i++){
                $temp_str=implode(",",$u_arr[$i]);
                if($exist_rot=$db->query_first("SELECT amount FROM games_xw_limit WHERE gameNum='{$temp_str}' AND gameNO=$gno")){

                    if(($exist_rot['amount']+$jj)>$limit_amount){
                        echo json_encode(array('info'=>'由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。'));
                        //echo '由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。';
                        exit;

                    }else{


                        $cur_amount=$exist_rot['amount']+$jj;

                        $db->query("UPDATE games_xw_limit SET amount=$cur_amount WHERE gameNum='{$temp_str}' AND gameNO=$gno");


                    }



                }else{



                    $db->query("INSERT INTO games_xw_limit (gameid,gameNum,amount,gameNO) VALUES ($gid,'{$temp_str}',$jj,$gno)");


                }




            }




        }else{
            if($ml>300){
                echo json_encode(array('info'=>'由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。'));
                // echo '由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。';
                exit;

            }else{
                if($mul_old=$mem->get($key)){

                    $new_mul=$mul_old+$ml;
                }else{
                    $new_mul=$ml;
                }

                if($new_mul>300){
                    echo json_encode(array('info'=>'由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。'));
                    // echo '由于该号码所购买量达到平台最高赔付300万，请您选择别的号码进行购买！谢谢。';
                    exit;

                }else{
                    $mem->set("$key",$new_mul,MEMCACHE_COMPRESSED,900);

                }
            }

        }




    }




    function zuhe($n,$arr,&$return,$tmp_array = array()){
        $len = count($arr);//算出数组个数
        if($n == 0){//如果是0则不要判断了直接返回
            $return[] = $tmp_array;
        }
        else{
            for($i=0; $i<$len; $i++){
                $tmp = $tmp_array;
                $tmp[] = $arr[$i];
                $arr2 = array_slice($arr,($i+1));//把前面的都要扔掉
                $m = $n-1;//次数减
                zuhe($m, $arr2, $return,$tmp);
            }
        }
    }
}
?>