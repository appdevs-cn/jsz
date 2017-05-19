<?php
/**
 * 时时彩封锁
 * User: jason
 * Date: 14/12/7
 * Time: 下午12:07
 */

Class LimitSsc {

    /*******************************************************************************************
     * 参数说明⬇️
     * $uid 用户的ID
     * $unum 用户购买的号码
     * $lottery_number_id 期号id
     * $lid  彩票的ID
     * $buy_type 玩法名称
     * $mul 倍数
     * $yuan 模式
     * $bonus_type  奖金模式 要返点与不要返点
     * $lim_num 限制购买的总中奖金额   从限制购买金额配置中读取
     *******************************************************************************************/

    public static function lim_dispatch($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type, $position) {
        $lim_ssc = array();

        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $redisObj->_setOption();
        $sscSwitchSetModel = M("ssc_switch_set");
        if($redisObj->exists("SSCSWITCHSET")){
            $sscswitch = $redisObj->_get("SSCSWITCHSET");
            foreach ($sscswitch as $key => $value) {
                $lim_ssc[$value["playway"]]  = $value["money"];
            }
        } else {
            $sscswitch = $sscSwitchSetModel->where(true)->select();
            $redisObj->_set("SSCSWITCHSET",$sscswitch);
            foreach ($sscswitch as $key => $value) {
                $lim_ssc[$value["playway"]]  = $value["money"];
            }
        }

        switch($lid){
            case 1 :
                $_w = "cqssc_lid=".$lottery_number_id;
                break;
            case 2 :
                $_w = "jxssc_lid=".$lottery_number_id;
                break;
            case 3 :
                $_w = "xjssc_lid=".$lottery_number_id;
                break;
            case 4 :
                $_w = "tjssc_lid=".$lottery_number_id;
                break;
            case 17 :
                $_w = "wfssc_lid=".$lottery_number_id;
                break;
            case 18 :
                $_w = "efssc_lid=".$lottery_number_id;
                break;
            case 19 :
                $_w = "yfssc_lid=".$lottery_number_id;
                break;
        }

        switch($buy_type) {
            case "五星直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_wxzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "五星直选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_wxzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "组选120" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx120($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选60" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx60($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选30" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx30($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选20" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx20($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选10" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx10($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选5" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx5($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "一帆风顺" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_yffs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "好事成双" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_hscs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "三星报喜" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxbx($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "四季发财" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sjfc($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前四直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_qs_zxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前四直选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_qs_zxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "后四直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_hs_zxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "后四直选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_hs_zxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "组选24" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx24($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选12" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx12($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选6" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx6($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组选4" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_zx4($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前三直选复式" :
            case "中三直选复式" :
            case "后三直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前三直选单式" :
            case "中三直选单式" :
            case "后三直选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_sxzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "前三组三" :
            case "中三组三" :
            case "后三组三" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxz3($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前三组六" :
            case "中三组六" :
            case "后三组六" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxz6($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前三组六胆拖" :
            case "中三组六胆拖" :
            case "后三组六胆拖" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxz6_dt($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前三混合组选" :
            case "中三混合组选" :
            case "后三混合组选" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_sxhh($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;

            case "前二直选复式" :
            case "后二直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_erzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前二直选单式" :
            case "后二直选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_erzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "前二直选和值" :
            case "后二直选和值" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_erzxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前二组选复式" :
            case "后二组选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_erzhuxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前二组选单式" :
            case "后二组选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_erzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "前二组选胆拖" :
            case "后二组选胆拖" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_erzxdt($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前二组选和值" :
            case "后二组选和值" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_erzhuxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前二大小单双" :
            case "后二大小单双" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_erdxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "前三大小单双" :
            case "后三大小单双" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sandxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "定位胆" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_dwd($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "后三一码不定位" :
            case "前三一码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxymbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "后三二码不定位" :
            case "前三二码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sxembdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "后四一码不定位" :
            case "前四一码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sixymbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "后四二码不定位" :
            case "前四二码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_sixembdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "五星一码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_wxymbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "五星二码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_wxembdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "五星三码不定位" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_wxsmbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任二直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_renerzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任二直选单式" :
                if($lid==17 || $lid==18 || $lid==19) {
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_renerzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "任二直选和值" :
                if($lid==17 || $lid==18 || $lid==19){
                echo json_encode(array("status"=>0,"info"=>"该玩法进行维护，暂时关闭"));
                exit();
                }
                $lim_num = $lim_ssc[$buy_type];
                self::lim_renerzxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任二组选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_renerzhuxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任二组选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_renerzhuxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "任二组选和值" :
                if($lid==17 || $lid==18 || $lid==19){
                    echo json_encode(array("status"=>0,"info"=>"该玩法进行维护，暂时关闭"));
                    exit();
                    }
                $lim_num = $lim_ssc[$buy_type];
                self::lim_renerzhuxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;

            case "任三直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensanzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任三直选单式" :
                if($lid==17 || $lid==18 || $lid==19) {
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_rensanzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type, $lim_num, $position, $_w);
                }
                break;
            case "任三直选和值" :
                if($lid==17 || $lid==18 || $lid==19){
                echo json_encode(array("status"=>0,"info"=>"该玩法进行维护，暂时关闭"));
                exit();
                }
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensanzxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组三复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensanz3fs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组三单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_rensanz3ds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "组六复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensanz6fs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "组六单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_rensanz6ds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "任三混合组选" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_rensanhhzx($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;
            case "任三组选和值" :
                if($lid==17 || $lid==18 || $lid==19){
                echo json_encode(array("status"=>0,"info"=>"该玩法进行维护，暂时关闭"));
                exit();
                }
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensanzhuxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;

            case "任四直选复式" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensizxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任四直选单式" :
                if($lid==17 || $lid==18 || $lid==19){
                    $lim_num = $lim_ssc[$buy_type];
                    self::lim_rensizxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                }
                break;

            case "任四组选24" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensizx24($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任四组选12" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensizx12($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任四组选6" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensizx6($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
            case "任四组选4" :
                $lim_num = $lim_ssc[$buy_type];
                self::lim_rensizx4($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position, $_w);
                break;
        }
    }



    //五星直选复式
    public function lim_wxzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position, $_w) {

        $model = self::getModel($buy_type,$lid);

        $unum_arr = explode(",", $unum);

        $ten_thousand_num_count = strlen(rtrim($unum_arr[0]));
        $thousand_num_count = strlen(rtrim($unum_arr[1]));
        $hundred_num_count = strlen(rtrim($unum_arr[2]));
        $ten_num_count = strlen(rtrim($unum_arr[3]));
        $bit_num_count = strlen(rtrim($unum_arr[4]));

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        $ten_thousand_num_arr = array();
        $thousand_num_arr = array();
        $hundred_num_arr = array();
        $ten_num_arr = array();
        $bit_num_arr = array();

        for($i=0; $i<$ten_thousand_num_count; $i++){
            $ten_thousand_num_arr[$i] = $unum_arr[0][$i];
        }
        $ten_thousand_num_str = implode(",", $ten_thousand_num_arr);

        for($j=0; $j<$thousand_num_count; $j++){
            $thousand_num_arr[$j] = $unum_arr[1][$j];
        }
        $thousand_num_str = implode(",", $thousand_num_arr);

        for($k=0; $k<$hundred_num_count; $k++){
            $hundred_num_arr[$k] = $unum_arr[2][$k];
        }
        $hundred_num_str = implode(",", $hundred_num_arr);

        for($l=0; $l<$ten_num_count; $l++){
            $ten_num_arr[$l] = $unum_arr[3][$l];
        }
        $ten_num_str = implode(",", $ten_num_arr);

        for($m=0; $m<$bit_num_count; $m++){
            $bit_num_arr[$m] = $unum_arr[4][$m];
        }
        $bit_num_str = implode(",", $bit_num_arr);


        if(in_array($lid,array(17,18,19))) {
            $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$bit_num_str') AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str') AND FIND_IN_SET(ten_thousand_num,'$ten_thousand_num_str')")->setInc('total_money', $user_bonus);
        } else {
            self::getMaxBonus($model,$_w." AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$bit_num_str') AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str') AND FIND_IN_SET(ten_thousand_num,'$ten_thousand_num_str')", $user_bonus, $lim_num, "zxfs_money");
            $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$bit_num_str') AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str') AND FIND_IN_SET(ten_thousand_num,'$ten_thousand_num_str')")->setInc('zxfs_money',$user_bonus);
        }
    }

    //五星直选单式
    public function lim_wxzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum_arr = explode(",", $unum);

        $arr_count = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        /*
        if(!in_array($lid,array(17,18,19))) {
            for ($i = 0; $i < $arr_count; $i++) {
                self::getMaxBonus($model, $_w . " AND is_zxds=1 AND number=\"{$unum_arr[$i]}\" ", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
            }
        }*/

        if(in_array($lid,array(17,18,19))) {
            $model->where($_w." AND is_zxds=1 AND number IN(".$unum.")")->setInc('total_money', $user_bonus);
        } else {
            //$model->where($_w . " AND is_zxds=1 AND number IN(".$unum.")")->setInc('zxds_money', $user_bonus);
        }


        /*
        for($i=0; $i<$arr_count; $i++) {
            if(in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_zxds=1 AND number=\"{$unum_arr[$i]}\"")->setInc('total_money', $user_bonus);
            } else {
                $model->where($_w . " AND is_zxds=1 AND number=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
            }
        }*/
    }

    //组选120
    public function lim_zx120($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $unum = rtrim($unum);
        $len = strlen($unum);

        $num_arr = array();

        for($i=0; $i<$len; $i++){
            $num_arr[$i] = $unum[$i];
        }
        $str = implode(",", $num_arr);

        $model = self::getModel($buy_type,$lid);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(!in_array($lid,array(17,18,19))) {

            $w = " AND bit_num!=ten_num AND bit_num!=hundred_num AND bit_num!=thousand_num AND bit_num!=ten_thousand_num AND ten_num!=hundred_num AND ten_num!=thousand_num AND ten_num!=ten_thousand_num AND hundred_num!=thousand_num AND hundred_num!=ten_thousand_num AND thousand_num!=ten_thousand_num";
            self::getMaxBonus($model,$_w." AND is_zx120=1 AND FIND_IN_SET(bit_num,'$str') AND FIND_IN_SET(ten_num,'$str') AND FIND_IN_SET(hundred_num,'$str') AND FIND_IN_SET(thousand_num,'$str') AND FIND_IN_SET(ten_thousand_num,'$str') ".$w, $user_bonus, $lim_num,"zx120_money");
            $model->where($_w." AND is_zx120=1 AND FIND_IN_SET(bit_num,'$str') AND FIND_IN_SET(ten_num,'$str') AND FIND_IN_SET(hundred_num,'$str') AND FIND_IN_SET(thousand_num,'$str') AND FIND_IN_SET(ten_thousand_num,'$str') ".$w)->setInc('zx120_money',$user_bonus);
        } else {
            $w = " AND bit_num!=ten_num AND bit_num!=hundred_num AND bit_num!=thousand_num AND bit_num!=ten_thousand_num AND ten_num!=hundred_num AND ten_num!=thousand_num AND ten_num!=ten_thousand_num AND hundred_num!=thousand_num AND hundred_num!=ten_thousand_num AND thousand_num!=ten_thousand_num";
            $model->where($_w . " AND is_zx120=1 AND FIND_IN_SET(bit_num,'$str') AND FIND_IN_SET(ten_num,'$str') AND FIND_IN_SET(hundred_num,'$str') AND FIND_IN_SET(thousand_num,'$str') AND FIND_IN_SET(ten_thousand_num,'$str') ".$w)->setInc('total_money', $user_bonus);
        }
    }

    //组选60
    public function lim_zx60($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $num_arr = explode(",", $unum);
        $zx60_erch = $num_arr[0];
        $zx60_erch_len = strlen($zx60_erch);
        $dh = $num_arr[1];
        $dh_len = strlen($dh);
        $zx60_erch_arr = array();
        $dh_arr = array();

        for($i=0; $i<$zx60_erch_len; $i++){
            $zx60_erch_arr[$i] = $zx60_erch[$i];
        }
        $zx60_erch_str = implode(",", $zx60_erch_arr);

        for($i=0; $i<$dh_len; $i++){
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx60=1 AND FIND_IN_SET(zx60_erch,'$zx60_erch_str') AND FIND_IN_SET(zx60_dh_one,'$dh_str') AND FIND_IN_SET(zx60_dh_two,'$dh_str') AND FIND_IN_SET(zx60_dh_three,'$dh_str')", $user_bonus, $lim_num, "zx60_money");
            $model->where($_w . " AND is_zx60=1 AND FIND_IN_SET(zx60_erch,'$zx60_erch_str') AND FIND_IN_SET(zx60_dh_one,'$dh_str') AND FIND_IN_SET(zx60_dh_two,'$dh_str') AND FIND_IN_SET(zx60_dh_three,'$dh_str')")->setInc('zx60_money', $user_bonus);
        } else {
            $model->where($_w . " AND is_zx60=1 AND FIND_IN_SET(zx60_erch,'$zx60_erch_str') AND FIND_IN_SET(zx60_dh_one,'$dh_str') AND FIND_IN_SET(zx60_dh_two,'$dh_str') AND FIND_IN_SET(zx60_dh_three,'$dh_str')")->setInc('total_money', $user_bonus);
        }
    }

    //组选30
    public function lim_zx30($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $num_arr = explode(",", $unum);
        $zx30_erch = $num_arr[0];
        $zx30_erch_len = strlen($zx30_erch);
        $dh = $num_arr[1];
        $dh_len = strlen($dh);
        $zx30_erch_arr = array();
        $dh_arr = array();

        for($i=0; $i<$zx30_erch_len; $i++){
            $zx30_erch_arr[$i] = $zx30_erch[$i];
        }
        $zx30_erch_str = implode(",", $zx30_erch_arr);

        for($i=0; $i<$dh_len; $i++){
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx30=1 AND FIND_IN_SET(zx30_erch_one,'$zx30_erch_str') AND FIND_IN_SET(zx30_erch_two,'$zx30_erch_str') AND FIND_IN_SET(zx30_dh,'$dh_str')", $user_bonus, $lim_num, "zx30_money");
            $model->where($_w . " AND is_zx30=1 AND FIND_IN_SET(zx30_erch_one,'$zx30_erch_str') AND FIND_IN_SET(zx30_erch_two,'$zx30_erch_str') AND FIND_IN_SET(zx30_dh,'$dh_str')")->setInc('zx30_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx30=1 AND FIND_IN_SET(zx30_erch_one,'$zx30_erch_str') AND FIND_IN_SET(zx30_erch_two,'$zx30_erch_str') AND FIND_IN_SET(zx30_dh,'$dh_str')")->setInc('total_money',$user_bonus);
        }}

    //组选20
    public function lim_zx20($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $num_arr = explode(",", $unum);
        $zx20_sanch = $num_arr[0];
        $zx20_sanch_len = strlen($zx20_sanch);
        $dh = $num_arr[1];
        $dh_len = strlen($dh);
        $zx20_sanch_arr = array();
        $dh_arr = array();

        for($i=0; $i<$zx20_sanch_len; $i++){
            $zx20_sanch_arr[$i] = $zx20_sanch[$i];
        }
        $zx20_sanch_str = implode(",", $zx20_sanch_arr);

        for($i=0; $i<$dh_len; $i++){
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx20=1 AND FIND_IN_SET(zx20_sanch,'$zx20_sanch_str') AND FIND_IN_SET(zx20_dh_one,'$dh_str') AND FIND_IN_SET(zx20_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx20_money");
            $model->where($_w . " AND is_zx20=1 AND FIND_IN_SET(zx20_sanch,'$zx20_sanch_str') AND FIND_IN_SET(zx20_dh_one,'$dh_str') AND FIND_IN_SET(zx20_dh_two,'$dh_str')")->setInc('zx20_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx20=1 AND FIND_IN_SET(zx20_sanch,'$zx20_sanch_str') AND FIND_IN_SET(zx20_dh_one,'$dh_str') AND FIND_IN_SET(zx20_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
        }}

    //组选10
    public function lim_zx10($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $num_arr = explode(",", $unum);
        $zx10_sanch = $num_arr[0];
        $zx10_sanch_len = strlen($zx10_sanch);
        $zx10_erch = $num_arr[1];
        $zx10_erch_len = strlen($zx10_erch);
        $zx10_sanch_arr = array();
        $zx10_erch_arr = array();

        for($i=0; $i<$zx10_sanch_len; $i++){
            $zx10_sanch_arr[$i] = $zx10_sanch[$i];
        }
        $zx10_sanch_str = implode(",", $zx10_sanch_arr);

        for($i=0; $i<$zx10_erch_len; $i++){
            $zx10_erch_arr[$i] = $zx10_erch[$i];
        }
        $zx10_erch_str = implode(",", $zx10_erch_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx10=1 AND FIND_IN_SET(zx10_sanch,'$zx10_sanch_str') AND FIND_IN_SET(zx10_erch,'$zx10_erch_str')", $user_bonus, $lim_num, "zx10_money");
            $model->where($_w . " AND is_zx10=1 AND FIND_IN_SET(zx10_sanch,'$zx10_sanch_str') AND FIND_IN_SET(zx10_erch,'$zx10_erch_str')")->setInc('zx10_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx10=1 AND FIND_IN_SET(zx10_sanch,'$zx10_sanch_str') AND FIND_IN_SET(zx10_erch,'$zx10_erch_str')")->setInc('total_money',$user_bonus);
        }
    }

    //组选5
    public function lim_zx5($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $num_arr = explode(",", $unum);
        $zx5_sich = $num_arr[0];
        $zx5_sich_len = strlen($zx5_sich);
        $dh = $num_arr[1];
        $dh_len = strlen($dh);
        $zx5_sich_arr = array();
        $dh_arr = array();

        for($i=0; $i<$zx5_sich_len; $i++){
            $zx5_sich_arr[$i] = $zx5_sich[$i];
        }
        $zx5_sich_str = implode(",", $zx5_sich_arr);

        for($i=0; $i<$dh_len; $i++){
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx5=1 AND FIND_IN_SET(zx5_sich,'$zx5_sich_str') AND FIND_IN_SET(zx5_dh,'$dh_str')", $user_bonus, $lim_num, "zx5_money");
            $model->where($_w . " AND is_zx5=1 AND FIND_IN_SET(zx5_sich,'$zx5_sich_str') AND FIND_IN_SET(zx5_dh,'$dh_str')")->setInc('zx5_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx5=1 AND FIND_IN_SET(zx5_sich,'$zx5_sich_str') AND FIND_IN_SET(zx5_dh,'$dh_str')")->setInc('total_money',$user_bonus);
        }
    }

    //一帆风顺
    public function lim_yffs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w){
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_yffs=1 AND (FIND_IN_SET(bit_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(ten_thousand_num,'$unum_str'))", $user_bonus, $lim_num, "yffs_money");
            $model->where($_w . " AND is_yffs=1 AND (FIND_IN_SET(bit_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(ten_thousand_num,'$unum_str'))")->setInc('yffs_money', $user_bonus);
        } else {
            $model->where($_w." AND is_yffs=1 AND (FIND_IN_SET(bit_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(ten_thousand_num,'$unum_str'))")->setInc('total_money',$user_bonus);
        }
    }

    //好事成双
    public function lim_hscs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w){
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_hscs=1 AND FIND_IN_SET(hscs_erch,'$unum_str')", $user_bonus, $lim_num, "hscs_money");
            $model->where($_w . " AND is_hscs=1 AND FIND_IN_SET(hscs_erch,'$unum_str')")->setInc('hscs_money', $user_bonus);
        } else {
            $model->where($_w." AND is_hscs=1 AND FIND_IN_SET(hscs_erch,'$unum_str')")->setInc('total_money',$user_bonus);
        }
    }

    //三星报喜
    public function lim_sxbx($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w){
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_sxbx=1 AND FIND_IN_SET(sxbx_sanch,'$unum_str')", $user_bonus, $lim_num, "sxbx_money");
            $model->where($_w . " AND is_sxbx=1 AND FIND_IN_SET(sxbx_sanch,'$unum_str')")->setInc('sxbx_money', $user_bonus);
        } else {
            $model->where($_w." AND is_sxbx=1 AND FIND_IN_SET(sxbx_sanch,'$unum_str')")->setInc('total_money',$user_bonus);
        }
    }

    //四季发财
    public function lim_sjfc($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w){
        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_sjfc=1 AND FIND_IN_SET(sjfc_sich,'$unum_str')", $user_bonus, $lim_num, "sjfc_money");
            $model->where($_w . " AND is_sjfc=1 AND FIND_IN_SET(sjfc_sich,'$unum_str')")->setInc('sjfc_money', $user_bonus);
        } else {
            $model->where($_w." AND is_sjfc=1 AND FIND_IN_SET(sjfc_sich,'$unum_str')")->setInc('total_money',$user_bonus);
        }
    }

    //前四直选复式
    public function lim_qs_zxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum_arr = explode(",", $unum);

        $ten_thousand_num_count = strlen(rtrim($unum_arr[0]));
        $thousand_num_count = strlen(rtrim($unum_arr[1]));
        $hundred_num_count = strlen(rtrim($unum_arr[2]));
        $ten_num_count = strlen(rtrim($unum_arr[3]));

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        $ten_thousand_num_arr = array();
        $thousand_num_arr = array();
        $hundred_num_arr = array();
        $ten_num_arr = array();

        for($i=0; $i<$ten_thousand_num_count; $i++){
            $ten_thousand_num_arr[$i] = $unum_arr[0][$i];
        }
        $ten_thousand_num_str = implode(",", $ten_thousand_num_arr);

        for($j=0; $j<$thousand_num_count; $j++){
            $thousand_num_arr[$j] = $unum_arr[1][$j];
        }
        $thousand_num_str = implode(",", $thousand_num_arr);

        for($k=0; $k<$hundred_num_count; $k++){
            $hundred_num_arr[$k] = $unum_arr[2][$k];
        }
        $hundred_num_str = implode(",", $hundred_num_arr);

        for($l=0; $l<$ten_num_count; $l++){
            $ten_num_arr[$l] = $unum_arr[3][$l];
        }
        $ten_num_str = implode(",", $ten_num_arr);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_qs_zxfs=1 AND  FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str') AND FIND_IN_SET(ten_thousand_num,'$ten_thousand_num_str')", $user_bonus, $lim_num, "qs_zxfs_money");
            $model->where($_w . " AND is_qs_zxfs=1 AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str') AND FIND_IN_SET(ten_thousand_num,'$ten_thousand_num_str')")->setInc('qs_zxfs_money', $user_bonus);
        } else {
            $model->where($_w." AND is_qs_zxfs=1 AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str') AND FIND_IN_SET(ten_thousand_num,'$ten_thousand_num_str')")->setInc('total_money',$user_bonus);
        }
    }

    //前四直选单式
    public function lim_qs_zxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum_arr = explode(",", $unum);

        $arr_count = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            for($i=0; $i<$arr_count; $i++){
                self::getMaxBonus($model,$_w." AND is_qs_zxds=1 AND qs_number=\"{$unum_arr[$i]}\" ", $user_bonus, $lim_num, "qs_zxds_money",$unum_arr[$i]);
            }}
        */
        if(!in_array($lid,array(17,18,19))) {
            $model->where($_w." AND is_qs_zxds=1 AND qs_number IN(".$unum.")")->setInc('qs_zxds_money', $user_bonus);
        } else {
            $model->where($_w . " AND is_qs_zxds=1 AND qs_number IN(".$unum.")")->setInc('total_money', $user_bonus);
        }
        /*
        for($i=0; $i<$arr_count; $i++) {
            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w . " AND is_qs_zxds=1 AND qs_number=\"{$unum_arr[$i]}\"")->setInc('qs_zxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qs_zxds=1 AND qs_number=\"{$unum_arr[$i]}\"")->setInc('total_money', $user_bonus);
            }
        }*/
    }

    //后四直选复式
    public function lim_hs_zxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);
        $unum_arr = explode(",", $unum);

        $thousand_num_count = strlen(rtrim($unum_arr[1]));
        $hundred_num_count = strlen(rtrim($unum_arr[2]));
        $ten_num_count = strlen(rtrim($unum_arr[3]));
        $bit_num_count = strlen(rtrim($unum_arr[4]));

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        $thousand_num_arr = array();
        $hundred_num_arr = array();
        $ten_num_arr = array();
        $bit_num_arr = array();

        for($j=0; $j<$thousand_num_count; $j++){
            $thousand_num_arr[$j] = $unum_arr[1][$j];
        }
        $thousand_num_str = implode(",", $thousand_num_arr);

        for($k=0; $k<$hundred_num_count; $k++){
            $hundred_num_arr[$k] = $unum_arr[2][$k];
        }
        $hundred_num_str = implode(",", $hundred_num_arr);

        for($l=0; $l<$ten_num_count; $l++){
            $ten_num_arr[$l] = $unum_arr[3][$l];
        }
        $ten_num_str = implode(",", $ten_num_arr);

        for($m=0; $m<$bit_num_count; $m++){
            $bit_num_arr[$m] = $unum_arr[4][$m];
        }
        $bit_num_str = implode(",", $bit_num_arr);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_hs_zxfs=1 AND FIND_IN_SET(bit_num,'$bit_num_str') AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str')", $user_bonus, $lim_num, "hs_zxfs_money");
            $model->where($_w . " AND is_hs_zxfs=1 AND FIND_IN_SET(bit_num,'$bit_num_str') AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str')")->setInc('hs_zxfs_money', $user_bonus);
        } else {
            $model->where($_w." AND is_hs_zxfs=1 AND FIND_IN_SET(bit_num,'$bit_num_str') AND FIND_IN_SET(ten_num,'$ten_num_str') AND FIND_IN_SET(hundred_num,'$hundred_num_str') AND FIND_IN_SET(thousand_num,'$thousand_num_str')")->setInc('total_money',$user_bonus);
        }
    }

    //后四直选单式
    public function lim_hs_zxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum_arr = explode(",", $unum);

        $arr_count = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            for($i=0; $i<$arr_count; $i++){
                self::getMaxBonus($model,$_w." AND is_hs_zxds=1 AND hs_number=\"{$unum_arr[$i]}\" ", $user_bonus, $lim_num, "hs_zxds_money",$unum_arr[$i]);
            }}
        */
        if(!in_array($lid,array(17,18,19))) {
            $model->where($_w." AND is_hs_zxds=1 AND hs_number IN(".$unum.")")->setInc('hs_zxds_money', $user_bonus);
        } else {
            $model->where($_w . " AND is_hs_zxds=1 AND hs_number IN(".$unum.")")->setInc('total_money', $user_bonus);
        }
        /*
        for($i=0; $i<$arr_count; $i++) {
            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w . " AND is_hs_zxds=1 AND hs_number=\"{$unum_arr[$i]}\"")->setInc('hs_zxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hs_zxds=1 AND hs_number=\"{$unum_arr[$i]}\"")->setInc('total_money', $user_bonus);
            }
        }*/
    }

    //组选24
    public function lim_zx24($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();

        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }

        $unum_str = implode(",", $unum_arr);


        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            $w = " AND bit_num!=ten_num AND bit_num!=hundred_num AND bit_num!=thousand_num AND ten_num!=hundred_num AND ten_num!=thousand_num AND hundred_num!=thousand_num";
            self::getMaxBonus($model, $_w . " AND is_zx24=1 AND (FIND_IN_SET(bit_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str')) ".$w, $user_bonus, $lim_num, "zx24_money");
            $model->where($_w . " AND is_zx24=1 AND (FIND_IN_SET(bit_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str')) ".$w)->setInc('zx24_money', $user_bonus);
        } else {
            $w = " AND bit_num!=ten_num AND bit_num!=hundred_num AND bit_num!=thousand_num AND ten_num!=hundred_num AND ten_num!=thousand_num AND hundred_num!=thousand_num";
            $model->where($_w." AND is_zx24=1 AND (FIND_IN_SET(bit_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str')) ".$w)->setInc('total_money',$user_bonus);
        }
    }

    //组选12
    public function lim_zx12($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //二重号
        $erch = $unum_arr[0];
        $erch_len = strlen($erch);
        $erch_arr = array();
        //单号
        $dh = $unum_arr[1];
        $dh_len = strlen($dh);
        $dh_arr = array();

        for($i=0; $i<$erch_len; $i++){
            $erch_arr[$i] = $erch[$i];
        }
        $erch_str = implode(",", $erch_arr);

        for($i=0; $i<$dh_len; $i++){
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx12=1 AND FIND_IN_SET(zx12_erch,'$erch_str') AND FIND_IN_SET(zx12_dh_one,'$dh_str') AND FIND_IN_SET(zx12_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx12_money");
            $model->where($_w . " AND is_zx12=1 AND FIND_IN_SET(zx12_erch,'$erch_str') AND FIND_IN_SET(zx12_dh_one,'$dh_str') AND FIND_IN_SET(zx12_dh_two,'$dh_str')")->setInc('zx12_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx12=1 AND FIND_IN_SET(zx12_erch,'$erch_str') AND FIND_IN_SET(zx12_dh_one,'$dh_str') AND FIND_IN_SET(zx12_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
        }
    }

    //组选6
    public function lim_zx6($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();

        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx6=1 AND FIND_IN_SET(zx6_erch_one,'$unum_str') AND FIND_IN_SET(zx6_erch_two,'$unum_str')", $user_bonus, $lim_num, "zx6_money");
            $model->where($_w . " AND is_zx6=1 AND FIND_IN_SET(zx6_erch_one,'$unum_str') AND FIND_IN_SET(zx6_erch_two,'$unum_str')")->setInc('zx6_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx6=1 AND FIND_IN_SET(zx6_erch_one,'$unum_str') AND FIND_IN_SET(zx6_erch_two,'$unum_str')")->setInc('total_money',$user_bonus);
        }
    }

    //组选4
    public function lim_zx4($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //三重号
        $sanch = $unum_arr[0];
        $sanch_len = strlen($sanch);
        $sanch_arr = array();
        //单号
        $dh = $unum_arr[1];
        $dh_len = strlen($dh);
        $dh_arr = array();

        for($i=0; $i<$sanch_len; $i++){
            $sanch_arr[$i] = $sanch[$i];
        }
        $sanch_str = implode(",", $sanch_arr);

        for($i=0; $i<$dh_len; $i++){
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);
        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_zx4=1 AND FIND_IN_SET(zx4_sanch,'$sanch_str') AND FIND_IN_SET(zx4_dh,'$dh_str')", $user_bonus, $lim_num, "zx4_money");
            $model->where($_w . " AND is_zx4=1 AND FIND_IN_SET(zx4_sanch,'$sanch_str') AND FIND_IN_SET(zx4_dh,'$dh_str')")->setInc('zx4_money', $user_bonus);
        } else {
            $model->where($_w." AND is_zx4=1 AND FIND_IN_SET(zx4_sanch,'$sanch_str') AND FIND_IN_SET(zx4_dh,'$dh_str')")->setInc('total_money',$user_bonus);
        }
    }

    //三星直选复式
    public function lim_sxzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w){

        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $_a_1_len = strlen($unum_arr[0]);
        $_a_1_arr = array();
        $_a_2_len = strlen($unum_arr[1]);
        $_a_2_arr = array();
        $_a_3_len = strlen($unum_arr[2]);
        $_a_3_arr = array();

        for($i=0; $i<$_a_1_len; $i++){
            $_a_1_arr[$i] = $unum_arr[0][$i];
        }
        $_a_1_str = implode(",",$_a_1_arr);

        for($i=0; $i<$_a_2_len; $i++){
            $_a_2_arr[$i] = $unum_arr[1][$i];
        }
        $_a_2_str = implode(",",$_a_2_arr);

        for($i=0; $i<$_a_3_len; $i++){
            $_a_3_arr[$i] = $unum_arr[2][$i];
        }
        $_a_3_str = implode(",",$_a_3_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsan_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$_a_1_str') AND FIND_IN_SET(thousand_num,'$_a_2_str') AND FIND_IN_SET(hundred_num,'$_a_3_str')", $user_bonus, $lim_num, "qsan_zxfs_money");
                $model->where($_w . " AND is_qsan_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$_a_1_str') AND FIND_IN_SET(thousand_num,'$_a_2_str') AND FIND_IN_SET(hundred_num,'$_a_3_str')")->setInc('qsan_zxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$_a_1_str') AND FIND_IN_SET(thousand_num,'$_a_2_str') AND FIND_IN_SET(hundred_num,'$_a_3_str')")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"中三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_zsan_zxfs=1 AND FIND_IN_SET(thousand_num',$_a_1_str') AND FIND_IN_SET(hundred_num,'$_a_2_str') AND FIND_IN_SET(ten_num,'$_a_3_str')", $user_bonus, $lim_num, "zsan_zxfs_money");
                $model->where($_w . " AND is_zsan_zxfs=1 AND FIND_IN_SET(thousand_num,'$_a_1_str') AND FIND_IN_SET(hundred_num,'$_a_2_str') AND FIND_IN_SET(ten_num,'$_a_3_str')")->setInc('zsan_zxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_zsan_zxfs=1 AND FIND_IN_SET(thousand_num,'$_a_1_str') AND FIND_IN_SET(hundred_num,'$_a_2_str') AND FIND_IN_SET(ten_num,'$_a_3_str')")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"后三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsan_zxfs=1 AND FIND_IN_SET(hundred_num',$_a_1_str') AND FIND_IN_SET(ten_num,'$_a_2_str') AND FIND_IN_SET(bit_num,'$_a_3_str')", $user_bonus, $lim_num, "hsan_zxfs_money");
                $model->where($_w . " AND is_hsan_zxfs=1 AND FIND_IN_SET(hundred_num,'$_a_1_str') AND FIND_IN_SET(ten_num,'$_a_2_str') AND FIND_IN_SET(bit_num,'$_a_3_str')")->setInc('hsan_zxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsan_zxfs=1 AND FIND_IN_SET(hundred_num,'$_a_1_str') AND FIND_IN_SET(ten_num,'$_a_2_str') AND FIND_IN_SET(bit_num,'$_a_3_str')")->setInc('total_money',$user_bonus);

            }

        }
    }

    //三星直选单式
    public function lim_sxzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w){
        $model = self::getModel($buy_type,$lid);

        //$unum_arr = explode(",", $unum);

        //$arr_count = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            /*
            if(!in_array($lid,array(17,18,19))) {
                for($i=0; $i<$arr_count; $i++){
                    self::getMaxBonus($model,$_w." AND is_qsan_zxds=1 AND qsan_number=\"{$unum_arr[$i]}\" ", $user_bonus, $lim_num, "qsan_zxds_money",$unum_arr[$i]);
                }}

            if(in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_qsan_zxds=1 AND qsan_number IN(".$unum.")")->setInc('qsan_zxds_money', $user_bonus);
            } else {
                $model->where($_w . " AND is_qsan_zxds=1 AND qsan_number IN(".$unum.")")->setInc('total_money', $user_bonus);
            }*/

            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_qsan_zxds=1 AND qsan_number IN (".$unum.")")->setInc('qsan_zxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_zxds=1 AND qsan_number IN (".$unum.")")->setInc('total_money', $user_bonus);
                //echo $model->getLastSql();
            }

        } else if(strpos($buy_type,"中三")!==false) {
            /*
            for($i=0; $i<$arr_count; $i++){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model,$_w." AND is_zsan_zxds=1 AND zsan_number=\"{$unum_arr[$i]}\" ", $user_bonus, $lim_num, "zsan_zxds_money",$unum_arr[$i]);
                }}

            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_zsan_zxds=1 AND zsan_number IN(".$unum.")")->setInc('zsan_zxds_money', $user_bonus);
            } else {
                $model->where($_w . " AND is_zsan_zxds=1 AND zsan_number IN(".$unum.")")->setInc('total_money', $user_bonus);
            }
            */

            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_zsan_zxds=1 AND zsan_number IN (".$unum.")")->setInc('zsan_zxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_zsan_zxds=1 AND zsan_number IN (".$unum.")")->setInc('total_money', $user_bonus);
            }

        } else if(strpos($buy_type,"后三")!==false) {
            /*
            for($i=0; $i<$arr_count; $i++){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model,$_w." AND is_hsan_zxds=1 AND hsan_number=\"{$unum_arr[$i]}\" ", $user_bonus, $lim_num, "hsan_zxds_money",$unum_arr[$i]);
                }}

            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_hsan_zxds=1 AND hsan_number IN(".$unum.")")->setInc('hsan_zxds_money', $user_bonus);
            } else {
                $model->where($_w . " AND is_hsan_zxds=1 AND hsan_number IN(".$unum.")")->setInc('total_money', $user_bonus);
            }
            */
            if(!in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_hsan_zxds=1 AND hsan_number IN (".$unum.")")->setInc('hsan_zxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsan_zxds=1 AND hsan_number IN (".$unum.")")->setInc('total_money', $user_bonus);
            }

        }
    }

    // 三星组三
    public function lim_sxz3($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();

        for($i=0; $i<$unum_len; $i++) {
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsan_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')", $user_bonus, $lim_num, "qsan_z3_money");
                $model->where($_w . " AND is_qsan_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('qsan_z3_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"中三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_zsan_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "zsan_z3_money");
                $model->where($_w . " AND is_zsan_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('zsan_z3_money', $user_bonus);
            } else {
                $model->where($_w." AND is_zsan_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"后三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsan_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "hsan_z3_money");
                $model->where($_w . " AND is_hsan_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('hsan_z3_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsan_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
            }

        }
    }

    // 三星组六
    public function lim_sxz6($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();

        for($i=0; $i<$unum_len; $i++) {
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsan_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')", $user_bonus, $lim_num, "qsan_z6_money");
                $model->where($_w . " AND is_qsan_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('qsan_z6_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"中三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_zsan_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "zsan_z6_money");
                $model->where($_w . " AND is_zsan_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('zsan_z6_money', $user_bonus);
            } else {
                $model->where($_w." AND is_zsan_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"后三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsan_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "hsan_z6_money");
                $model->where($_w . " AND is_hsan_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('hsan_z6_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsan_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
            }

        }
    }

    //三星组六胆拖
    public function lim_sxz6_dt($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",",$unum);
        //胆码
        $dm = $unum_arr[0];
        $dm_len = strlen($dm);
        $dm_arr = array();
        //拖码
        $tm = $unum_arr[1];
        $tm_len = strlen($tm);
        $tm_arr = array();


        for($i=0; $i<$dm_len; $i++) {
            $dm_arr[$i] = $dm[$i];
        }
        $dm_str = implode(",", $dm_arr);

        for($i=0; $i<$tm_len; $i++) {
            $tm_arr[$i] = $tm[$i];
        }
        $tm_str = implode(",", $tm_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, "前三组六", $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsan_z6_dt=1 AND (FIND_IN_SET(ten_thousand_num,'$dm_str') OR FIND_IN_SET(thousand_num,'$dm_str') OR FIND_IN_SET(hundred_num,'$dm_str')) AND (FIND_IN_SET(ten_thousand_num,'$tm_str') OR FIND_IN_SET(thousand_num,'$tm_str') OR FIND_IN_SET(hundred_num,'$tm_str'))", $user_bonus, $lim_num, "qsan_z6_dt_money");
                $model->where($_w . " AND is_qsan_z6_dt=1 AND (FIND_IN_SET(ten_thousand_num,'$dm_str') OR FIND_IN_SET(thousand_num,'$dm_str') OR FIND_IN_SET(hundred_num,'$dm_str')) AND (FIND_IN_SET(ten_thousand_num,'$tm_str') OR FIND_IN_SET(thousand_num,'$tm_str') OR FIND_IN_SET(hundred_num,'$tm_str'))")->setInc('qsan_z6_dt_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_z6_dt=1 AND (FIND_IN_SET(ten_thousand_num,'$dm_str') OR FIND_IN_SET(thousand_num,'$dm_str') OR FIND_IN_SET(hundred_num,'$dm_str')) AND (FIND_IN_SET(ten_thousand_num,'$tm_str') OR FIND_IN_SET(thousand_num,'$tm_str') OR FIND_IN_SET(hundred_num,'$tm_str'))")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"中三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsan_z6_dt=1 AND (FIND_IN_SET(thousand_num,'$dm_str') OR FIND_IN_SET(hundred_num,'$dm_str') OR FIND_IN_SET(ten_num,'$dm_str')) AND (FIND_IN_SET(thousand_num,'$tm_str') OR FIND_IN_SET(hundred_num,'$tm_str') OR FIND_IN_SET(ten_num,'$tm_str'))", $user_bonus, $lim_num, "zsan_z6_dt_money");
                $model->where($_w . " AND is_qsan_z6_dt=1 AND (FIND_IN_SET(thousand_num,'$dm_str') OR FIND_IN_SET(hundred_num,'$dm_str') OR FIND_IN_SET(ten_num,'$dm_str')) AND (FIND_IN_SET(thousand_num,'$tm_str') OR FIND_IN_SET(hundred_num,'$tm_str') OR FIND_IN_SET(ten_num,'$tm_str'))")->setInc('zsan_z6_dt_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_z6_dt=1 AND (FIND_IN_SET(thousand_num,'$dm_str') OR FIND_IN_SET(hundred_num,'$dm_str') OR FIND_IN_SET(ten_num,'$dm_str')) AND (FIND_IN_SET(thousand_num,'$tm_str') OR FIND_IN_SET(hundred_num,'$tm_str') OR FIND_IN_SET(ten_num,'$tm_str'))")->setInc('total_money',$user_bonus);
            }

        } else if(strpos($buy_type,"后三")!==false) {
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsan_z6_dt=1 AND (FIND_IN_SET(hundred_num,'$dm_str') OR FIND_IN_SET(ten_num,'$dm_str') OR FIND_IN_SET(bit_num,'$tm_str')) AND (FIND_IN_SET(hundred_num,'$tm_str') OR FIND_IN_SET(ten_num,'$tm_str') OR FIND_IN_SET(bit_num,'$tm_str'))", $user_bonus, $lim_num, "hsan_z6_dt_money");
                $model->where($_w . " AND is_qsan_z6_dt=1 AND (FIND_IN_SET(hundred_num,'$dm_str') OR FIND_IN_SET(ten_num,'$dm_str') OR FIND_IN_SET(bit_num,'$tm_str')) AND (FIND_IN_SET(hundred_num,'$tm_str') OR FIND_IN_SET(ten_num,'$tm_str') OR FIND_IN_SET(bit_num,'$tm_str'))")->setInc('hsan_z6_dt_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsan_z6_dt=1 AND (FIND_IN_SET(hundred_num,'$dm_str') OR FIND_IN_SET(ten_num,'$dm_str') OR FIND_IN_SET(bit_num,'$tm_str')) AND (FIND_IN_SET(hundred_num,'$tm_str') OR FIND_IN_SET(ten_num,'$tm_str') OR FIND_IN_SET(bit_num,'$tm_str'))")->setInc('total_money',$user_bonus);
            }

        }
    }

    //三星混合组选
    public function lim_sxhh($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $_bonusTypeArr = explode("|",$bonus_type);
        //$bonus_arr = explode("-",$_bonusTypeArr[0]);
        //$bonus_arr = array("566.67","283.33");
        $zs = 650.67;
        $zl = 325.33;
        $zs_bonus = $zs*$mul;
        $zl_bonus = $zl*$mul;

        if($yuan==1){
            $zs_bonus = $zs*$mul/10;
            $zl_bonus = $zl*$mul/10;
        }elseif($yuan==2){
            $zs_bonus = $zs*$mul/100;
            $zl_bonus = $zl*$mul/100;
        }elseif($yuan==3){
            $zs_bonus = $zs*$mul/1000;
            $zl_bonus = $zl*$mul/1000;
        }



        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);
        $arr_count = count($unum_arr);
        /*
        for($i=0; $i<$arr_count; $i++) {
            $unum_str = $unum_arr[$i][0] . ',' . $unum_arr[$i][1] . ',' . $unum_arr[$i][2];
            if(!in_array($lid,array(17,18,19))) {
                if(strpos($buy_type,"前三")!==false){

                    self::getMaxBonus($model,$_w." AND is_qsan_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')", $zs_bonus, $lim_num, "qsan_z3_money");
                    self::getMaxBonus($model,$_w." AND is_qsan_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')", $zl_bonus, $lim_num, "qsan_z6_money");

                } else if(strpos($buy_type,"中三")!==false) {

                    self::getMaxBonus($model,$_w." AND is_zsan_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $zs_bonus, $lim_num, "zsan_z3_money");
                    self::getMaxBonus($model,$_w." AND is_zsan_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $zl_bonus, $lim_num, "zsan_z6_money");

                } else if(strpos($buy_type,"后三")!==false) {

                    self::getMaxBonus($model,$_w." AND is_hsan_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $zs_bonus, $lim_num, "hsan_z3_money");
                    self::getMaxBonus($model,$_w." AND is_hsan_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $zl_bonus, $lim_num, "hsan_z6_money");

                }
            }}
        */
        for($i=0; $i<$arr_count; $i++) {
            $unum_str = $unum_arr[$i];
            if(strpos($buy_type,"前三")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_qsan_z6=1 AND qsan_z6_number=".$unum_str)->setInc('qsan_z6_money', $zl_bonus);
                } else {
                    $model->where($_w." AND is_qsan_z6=1 AND qsan_z6_number=".$unum_str)->setInc('total_money',$zl_bonus);
                }
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_qsan_z3=1 AND qsan_z3_number=".$unum_str)->setInc('qsan_z3_money', $zs_bonus);
                } else {
                    $model->where($_w." AND is_qsan_z3=1 AND qsan_z3_number=".$unum_str)->setInc('total_money',$zs_bonus);
                }

            } else if(strpos($buy_type,"中三")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_zsan_z6=1 AND zsan_z6_number=".$unum_str)->setInc('zsan_z6_money', $zl_bonus);
                } else {
                    $model->where($_w." AND is_zsan_z6=1 AND zsan_z6_number=".$unum_str)->setInc('total_money',$zl_bonus);
                }
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_zsan_z3=1 AND zsan_z3_number=".$unum_str)->setInc('zsan_z3_money', $zs_bonus);
                } else {
                    $model->where($_w." AND is_zsan_z3=1 AND zsan_z3_number=".$unum_str)->setInc('total_money',$zs_bonus);
                }

            } else if(strpos($buy_type,"后三")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_hsan_z6=1 AND hsan_z6_number=".$unum_str)->setInc('hsan_z6_money', $zl_bonus);
                } else {
                    $model->where($_w." AND is_hsan_z6=1 AND hsan_z6_number=".$unum_str)->setInc('total_money',$zl_bonus);
                }
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_hsan_z3=1 AND hsan_z3_number=".$unum_str)->setInc('hsan_z3_money', $zs_bonus);
                } else {
                    $model->where($_w." AND is_hsan_z3=1 AND hsan_z3_number=".$unum_str)->setInc('total_money',$zs_bonus);
                }

            }
        }

    }

    //两星直选复式
    public function lim_erzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //万位
        $tenThousand = $unum_arr[0];
        $tenThousand_len = strlen($tenThousand);
        $tenThousand_arr = array();
        for($i=0; $i<$tenThousand_len;$i++){
            $tenThousand_arr[$i] = $tenThousand[$i];
        }
        $tenThousand_str = implode(",", $tenThousand_arr);

        //千位
        $thousand = $unum_arr[1];
        $thousand_len = strlen($thousand);
        $thousand_arr = array();
        for($i=0; $i<$thousand_len; $i++) {
            $thousand_arr[$i] = $thousand[$i];
        }
        $thousand_str = implode(",", $thousand_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qer_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$tenThousand_str') AND FIND_IN_SET(thousand_num,'$thousand_str')", $user_bonus, $lim_num, "qer_zxfs_money");
                $model->where($_w . " AND is_qer_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$tenThousand_str') AND FIND_IN_SET(thousand_num,'$thousand_str')")->setInc('qer_zxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qer_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$tenThousand_str') AND FIND_IN_SET(thousand_num,'$thousand_str')")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_her_zxfs=1 AND FIND_IN_SET(ten_num,'$tenThousand_str') AND FIND_IN_SET(bit_num,'$thousand_str')", $user_bonus, $lim_num, "her_zxfs_money");
                $model->where($_w . " AND is_her_zxfs=1 AND FIND_IN_SET(ten_num,'$tenThousand_str') AND FIND_IN_SET(bit_num,'$thousand_str')")->setInc('her_zxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_her_zxfs=1 AND FIND_IN_SET(ten_num,'$tenThousand_str') AND FIND_IN_SET(bit_num,'$thousand_str')")->setInc('total_money',$user_bonus);
            }

        }

    }

    //两星直选单式
    public function lim_erzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);
        $count_arr = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                for($i=0; $i<$count_arr; $i++){

                    self::getMaxBonus($model,$_w." AND is_qer_zxds=1 AND qer_number=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "qer_zxds_money",$unum_arr[$i]);

                }}

            if(in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_qer_zxds=1 AND qer_number IN(".$unum.")")->setInc('qer_zxds_money', $user_bonus);
            } else {
                $model->where($_w . " AND is_qer_zxds=1 AND qer_number IN(".$unum.")")->setInc('total_money', $user_bonus);
            }

            for($i=0; $i<$count_arr; $i++) {
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_qer_zxds=1 AND qer_number=\"{$unum_arr[$i]}\"")->setInc('qer_zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qer_zxds=1 AND qer_number=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                }
            }
        }

        if(strpos($buy_type,"后二")!==false){

            if(!in_array($lid,array(17,18,19))) {
                for($i=0; $i<$count_arr; $i++){

                    self::getMaxBonus($model,$_w." AND is_her_zxds=1 AND her_number=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "her_zxds_money",$unum_arr[$i]);

                }}

            if(in_array($lid,array(17,18,19))) {
                $model->where($_w." AND is_her_zxds=1 AND her_number IN(".$unum.")")->setInc('her_zxds_money', $user_bonus);
            } else {
                $model->where($_w . " AND is_her_zxds=1 AND her_number IN(".$unum.")")->setInc('total_money', $user_bonus);
            }

            for($i=0; $i<$count_arr; $i++) {
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_her_zxds=1 AND her_number=\"{$unum_arr[$i]}\"")->setInc('her_zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_her_zxds=1 AND her_number=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                }
            }
        }

    }

    //两星直选和值
    public function lim_erzxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qer_zxhz=1 AND FIND_IN_SET(qer_zxhz_number,'$unum')", $user_bonus, $lim_num, "qer_zxhz_money");
                $model->where($_w . " AND is_qer_zxhz=1 AND FIND_IN_SET(qer_zxhz_number,'$unum')")->setInc('qer_zxhz_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qer_zxhz=1 AND FIND_IN_SET(qer_zxhz_number,'$unum')")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_her_zxhz=1 AND FIND_IN_SET(her_zxhz_number,'$unum')", $user_bonus, $lim_num, "her_zxhz_money");
                $model->where($_w . " AND is_her_zxhz=1 AND FIND_IN_SET(her_zxhz_number,'$unum')")->setInc('her_zxhz_money', $user_bonus);
            } else {
                $model->where($_w." AND is_her_zxhz=1 AND FIND_IN_SET(her_zxhz_number,'$unum')")->setInc('total_money',$user_bonus);
            }
        }
    }

    //两星组选复式
    public function lim_erzhuxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //万位
        $tenThousand = $unum_arr[0];
        $tenThousand_len = strlen($tenThousand);
        $tenThousand_arr = array();
        for($i=0; $i<$tenThousand_len;$i++){
            $tenThousand_arr[$i] = $tenThousand[$i];
        }
        $tenThousand_str = implode(",", $tenThousand_arr);

        //千位
        $thousand = $unum_arr[1];
        $thousand_len = strlen($thousand);
        $thousand_arr = array();
        for($i=0; $i<$thousand_len; $i++) {
            $thousand_arr[$i] = $thousand[$i];
        }
        $thousand_str = implode(",", $thousand_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qer_zhuxfs=1 AND FIND_IN_SET(qer_zhuxfs_num1,'$tenThousand_str') AND FIND_IN_SET(qer_zhuxfs_num2,'$thousand_str')", $user_bonus, $lim_num, "qer_zhuxfs_money");
                $model->where($_w . " AND is_qer_zhuxfs=1 AND FIND_IN_SET(qer_zhuxfs_num1,'$tenThousand_str') AND FIND_IN_SET(qer_zhuxfs_num2,'$thousand_str')")->setInc('qer_zhuxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qer_zhuxfs=1 AND FIND_IN_SET(qer_zhuxfs_num1,'$tenThousand_str') AND FIND_IN_SET(qer_zhuxfs_num2,'$thousand_str')")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_her_zhuxfs=1 AND FIND_IN_SET(her_zhuxfs_num1,'$tenThousand_str') AND FIND_IN_SET(her_zhuxfs_num2,'$thousand_str')", $user_bonus, $lim_num, "her_zhuxfs_money");
                $model->where($_w . " AND is_her_zhuxfs=1 AND FIND_IN_SET(her_zhuxfs_num1,'$tenThousand_str') AND FIND_IN_SET(her_zhuxfs_num2,'$thousand_str')")->setInc('her_zhuxfs_money', $user_bonus);
            } else {
                $model->where($_w." AND is_her_zhuxfs=1 AND FIND_IN_SET(her_zhuxfs_num1,'$tenThousand_str') AND FIND_IN_SET(her_zhuxfs_num2,'$thousand_str')")->setInc('total_money',$user_bonus);
            }
        }

    }

    //两星组选单式
    public function lim_erzhuxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);
        $count_arr = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                for($i=0; $i<$count_arr; $i++){

                    self::getMaxBonus($model,$_w." AND is_qer_zhuxds=1 AND qer_zhuxds_number=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "qer_zhuxds_money",$unum_arr[$i]);

                }}

            for($i=0; $i<$count_arr; $i++) {
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_qer_zhuxds=1 AND qer_zhuxds_number=\"{$unum_arr[$i]}\"")->setInc('qer_zhuxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qer_zhuxds=1 AND qer_zhuxds_number=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                }
            }
        }

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                for($i=0; $i<$count_arr; $i++){

                    self::getMaxBonus($model,$_w." AND is_her_zhuxds=1 AND her_zhuxds_number=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "her_zhuxds_money",$unum_arr[$i]);

                }}

            for($i=0; $i<$count_arr; $i++) {
                if(!in_array($lid,array(17,18,19))) {
                    $model->where($_w . " AND is_her_zhuxds=1 AND her_zhuxds_number=\"{$unum_arr[$i]}\"")->setInc('her_zhuxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_her_zhuxds=1 AND her_zhuxds_number=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                }
            }
        }

    }

    //两星组选胆拖
    public function lim_erzxdt($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //胆码
        $dm = $unum_arr[0];

        //拖码
        $tm = $unum_arr[1];
        $tm_len = strlen($tm);
        $tm_arr = array();
        for($i=0; $i<$tm_len; $i++){
            $tm_arr[$i] = $tm[$i];
        }
        $tm_str = implode(",", $tm_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qer_dt=1 AND ((ten_thousand_num={$dm} AND FIND_IN_SET(thousand_num,'$tm_str')) OR (thousand_num={$dm} AND FIND_IN_SET(ten_thousand_num,'$tm_str')))", $user_bonus, $lim_num, "qer_dt_money");
                $model->where($_w . " AND is_qer_dt=1 AND ((ten_thousand_num={$dm} AND FIND_IN_SET(thousand_num,'$tm_str')) OR (thousand_num={$dm} AND FIND_IN_SET(ten_thousand_num,'$tm_str')))")->setInc('qer_dt_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qer_dt=1 AND ((ten_thousand_num={$dm} AND FIND_IN_SET(thousand_num,'$tm_str')) OR (thousand_num={$dm} AND FIND_IN_SET(ten_thousand_num,'$tm_str')))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_her_dt=1 AND ((ten_num={$dm} AND FIND_IN_SET(bit_num,'$tm_str')) OR (bit_num={$dm} AND FIND_IN_SET(ten_num,'$tm_str')))", $user_bonus, $lim_num, "her_dt_money");
                $model->where($_w . " AND is_her_dt=1 AND ((ten_num={$dm} AND FIND_IN_SET(bit_num,'$tm_str')) OR (bit_num={$dm} AND FIND_IN_SET(ten_num,'$tm_str')))")->setInc('her_dt_money', $user_bonus);
            } else {
                $model->where($_w." AND is_her_dt=1 AND ((ten_num={$dm} AND FIND_IN_SET(bit_num,'$tm_str')) OR (bit_num={$dm} AND FIND_IN_SET(ten_num,'$tm_str')))")->setInc('total_money',$user_bonus);
            }
        }
    }

    //两星组选和值
    public function lim_erzhuxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qer_zhuxhz=1 AND FIND_IN_SET(qer_zhuxhz_number,'$unum')", $user_bonus, $lim_num, "qer_zhuxhz_money");
                $model->where($_w . " AND is_qer_zhuxhz=1 AND FIND_IN_SET(qer_zhuxhz_number,'$unum')")->setInc('qer_zhuxhz_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qer_zhuxhz=1 AND FIND_IN_SET(qer_zhuxhz_number,'$unum')")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_her_zhuxhz=1 AND FIND_IN_SET(her_zhuxhz_number,'$unum')", $user_bonus, $lim_num, "her_zhuxhz_money");
                $model->where($_w . " AND is_her_zhuxhz=1 AND FIND_IN_SET(her_zhuxhz_number,'$unum')")->setInc('her_zhuxhz_money', $user_bonus);
            } else {
                $model->where($_w." AND is_her_zhuxhz=1 AND FIND_IN_SET(her_zhuxhz_number,'$unum')")->setInc('total_money',$user_bonus);
            }
        }
    }

    //两星大小单双
    public function lim_erdxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $_a_1 = $unum_arr[0];
        $_a_1_arr = self::StringToArray($_a_1);
        $_a_1_str = implode(",",$_a_1_arr);

        $_a_2 = $unum_arr[1];
        $_a_2_arr = self::StringToArray($_a_2);
        $_a_2_str = implode(",",$_a_2_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"后二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_dxds=1 AND (FIND_IN_SET(ten_big,'$_a_1_str') OR FIND_IN_SET(ten_small,'$_a_1_str') OR FIND_IN_SET(ten_odd,'$_a_1_str') OR FIND_IN_SET(ten_even,'$_a_1_str')) AND (FIND_IN_SET(bit_big,'$_a_2_str') OR FIND_IN_SET(bit_small,'$_a_2_str') OR FIND_IN_SET(bit_odd,'$_a_2_str') OR FIND_IN_SET(bit_even,'$_a_2_str'))", $user_bonus, $lim_num, "dxds_money");
                $model->where($_w . " AND is_dxds=1 AND (FIND_IN_SET(ten_big,'$_a_1_str') OR FIND_IN_SET(ten_small,'$_a_1_str') OR FIND_IN_SET(ten_odd,'$_a_1_str') OR FIND_IN_SET(ten_even,'$_a_1_str')) AND (FIND_IN_SET(bit_big,'$_a_2_str') OR FIND_IN_SET(bit_small,'$_a_2_str') OR FIND_IN_SET(bit_odd,'$_a_2_str') OR FIND_IN_SET(bit_even,'$_a_2_str'))")->setInc('dxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_dxds=1 AND (FIND_IN_SET(ten_big,'$_a_1_str') OR FIND_IN_SET(ten_small,'$_a_1_str') OR FIND_IN_SET(ten_odd,'$_a_1_str') OR FIND_IN_SET(ten_even,'$_a_1_str')) AND (FIND_IN_SET(bit_big,'$_a_2_str') OR FIND_IN_SET(bit_small,'$_a_2_str') OR FIND_IN_SET(bit_odd,'$_a_2_str') OR FIND_IN_SET(bit_even,'$_a_2_str'))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"前二")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_dxds=1 AND (FIND_IN_SET(ten_thousand_big,'$_a_1_str') OR FIND_IN_SET(ten_thousand_small,'$_a_1_str') OR FIND_IN_SET(ten_thousand_odd,'$_a_1_str') OR FIND_IN_SET(ten_thousand_even,'$_a_1_str')) AND (FIND_IN_SET(thousand_big,'$_a_2_str') OR FIND_IN_SET(thousand_small,'$_a_2_str') OR FIND_IN_SET(thousand_odd,'$_a_2_str') OR FIND_IN_SET(thousand_even,'$_a_2_str'))", $user_bonus, $lim_num, "dxds_money");
                $model->where($_w . " AND is_dxds=1 AND (FIND_IN_SET(ten_thousand_big,'$_a_1_str') OR FIND_IN_SET(ten_thousand_small,'$_a_1_str') OR FIND_IN_SET(ten_thousand_odd,'$_a_1_str') OR FIND_IN_SET(ten_thousand_even,'$_a_1_str')) AND (FIND_IN_SET(thousand_big,'$_a_2_str') OR FIND_IN_SET(thousand_small,'$_a_2_str') OR FIND_IN_SET(thousand_odd,'$_a_2_str') OR FIND_IN_SET(thousand_even,'$_a_2_str'))")->setInc('dxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_dxds=1 AND (FIND_IN_SET(ten_thousand_big,'$_a_1_str') OR FIND_IN_SET(ten_thousand_small,'$_a_1_str') OR FIND_IN_SET(ten_thousand_odd,'$_a_1_str') OR FIND_IN_SET(ten_thousand_even,'$_a_1_str')) AND (FIND_IN_SET(thousand_big,'$_a_2_str') OR FIND_IN_SET(thousand_small,'$_a_2_str') OR FIND_IN_SET(thousand_odd,'$_a_2_str') OR FIND_IN_SET(thousand_even,'$_a_2_str'))")->setInc('total_money',$user_bonus);
            }
        }
    }

    //三星大小单双
    public function lim_sandxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $_a_1 = $unum_arr[0];
        $_a_1_arr = self::StringToArray($_a_1);
        $_a_1_str = implode(",",$_a_1_arr);

        $_a_2 = $unum_arr[1];
        $_a_2_arr = self::StringToArray($_a_2);
        $_a_2_str = implode(",",$_a_2_arr);

        $_a_3 = $unum_arr[2];
        $_a_3_arr = self::StringToArray($_a_3);
        $_a_3_str = implode(",",$_a_3_arr);


        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(strpos($buy_type,"后三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_dxds=1 AND (FIND_IN_SET(hundred_big,'$_a_1_str') OR FIND_IN_SET(hundred_small,'$_a_1_str') OR FIND_IN_SET(hundred_odd,'$_a_1_str') OR FIND_IN_SET(hundred_even,'$_a_1_str')) AND (FIND_IN_SET(ten_big,'$_a_1_str') OR FIND_IN_SET(ten_small,'$_a_1_str') OR FIND_IN_SET(ten_odd,'$_a_1_str') OR FIND_IN_SET(ten_even,'$_a_1_str')) AND (FIND_IN_SET(bit_big,'$_a_2_str') OR FIND_IN_SET(bit_small,'$_a_2_str') OR FIND_IN_SET(bit_odd,'$_a_2_str') OR FIND_IN_SET(bit_even,'$_a_2_str'))", $user_bonus, $lim_num, "dxds_money");
                $model->where($_w . " AND is_dxds=1 AND (FIND_IN_SET(hundred_big,'$_a_1_str') OR FIND_IN_SET(hundred_small,'$_a_1_str') OR FIND_IN_SET(hundred_odd,'$_a_1_str') OR FIND_IN_SET(hundred_even,'$_a_1_str')) AND (FIND_IN_SET(ten_big,'$_a_1_str') OR FIND_IN_SET(ten_small,'$_a_1_str') OR FIND_IN_SET(ten_odd,'$_a_1_str') OR FIND_IN_SET(ten_even,'$_a_1_str')) AND (FIND_IN_SET(bit_big,'$_a_2_str') OR FIND_IN_SET(bit_small,'$_a_2_str') OR FIND_IN_SET(bit_odd,'$_a_2_str') OR FIND_IN_SET(bit_even,'$_a_2_str'))")->setInc('dxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_dxds=1 AND (FIND_IN_SET(hundred_big,'$_a_1_str') OR FIND_IN_SET(hundred_small,'$_a_1_str') OR FIND_IN_SET(hundred_odd,'$_a_1_str') OR FIND_IN_SET(hundred_even,'$_a_1_str')) AND (FIND_IN_SET(ten_big,'$_a_1_str') OR FIND_IN_SET(ten_small,'$_a_1_str') OR FIND_IN_SET(ten_odd,'$_a_1_str') OR FIND_IN_SET(ten_even,'$_a_1_str')) AND (FIND_IN_SET(bit_big,'$_a_2_str') OR FIND_IN_SET(bit_small,'$_a_2_str') OR FIND_IN_SET(bit_odd,'$_a_2_str') OR FIND_IN_SET(bit_even,'$_a_2_str'))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_dxds=1 AND (FIND_IN_SET(ten_thousand_big,'$_a_1_str') OR FIND_IN_SET(ten_thousand_small,'$_a_1_str') OR FIND_IN_SET(ten_thousand_odd,'$_a_1_str') OR FIND_IN_SET(ten_thousand_even,'$_a_1_str')) AND (FIND_IN_SET(thousand_big,'$_a_2_str') OR FIND_IN_SET(thousand_small,'$_a_2_str') OR FIND_IN_SET(thousand_odd,'$_a_2_str') OR FIND_IN_SET(thousand_even,'$_a_2_str')) AND (FIND_IN_SET(hundred_big,'$_a_1_str') OR FIND_IN_SET(hundred_small,'$_a_1_str') OR FIND_IN_SET(hundred_odd,'$_a_1_str') OR FIND_IN_SET(hundred_even,'$_a_1_str'))", $user_bonus, $lim_num, "dxds_money");
                $model->where($_w . " AND is_dxds=1 AND (FIND_IN_SET(ten_thousand_big,'$_a_1_str') OR FIND_IN_SET(ten_thousand_small,'$_a_1_str') OR FIND_IN_SET(ten_thousand_odd,'$_a_1_str') OR FIND_IN_SET(ten_thousand_even,'$_a_1_str')) AND (FIND_IN_SET(thousand_big,'$_a_2_str') OR FIND_IN_SET(thousand_small,'$_a_2_str') OR FIND_IN_SET(thousand_odd,'$_a_2_str') OR FIND_IN_SET(thousand_even,'$_a_2_str')) AND (FIND_IN_SET(hundred_big,'$_a_1_str') OR FIND_IN_SET(hundred_small,'$_a_1_str') OR FIND_IN_SET(hundred_odd,'$_a_1_str') OR FIND_IN_SET(hundred_even,'$_a_1_str'))")->setInc('dxds_money', $user_bonus);
            } else {
                $model->where($_w." AND is_dxds=1 AND (FIND_IN_SET(ten_thousand_big,'$_a_1_str') OR FIND_IN_SET(ten_thousand_small,'$_a_1_str') OR FIND_IN_SET(ten_thousand_odd,'$_a_1_str') OR FIND_IN_SET(ten_thousand_even,'$_a_1_str')) AND (FIND_IN_SET(thousand_big,'$_a_2_str') OR FIND_IN_SET(thousand_small,'$_a_2_str') OR FIND_IN_SET(thousand_odd,'$_a_2_str') OR FIND_IN_SET(thousand_even,'$_a_2_str')) AND (FIND_IN_SET(hundred_big,'$_a_1_str') OR FIND_IN_SET(hundred_small,'$_a_1_str') OR FIND_IN_SET(hundred_odd,'$_a_1_str') OR FIND_IN_SET(hundred_even,'$_a_1_str'))")->setInc('total_money',$user_bonus);
            }
        }

    }

    //定位胆
    public function lim_dwd($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {

        $model = self::getModel($buy_type,$lid);
        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $_a_1 = $unum_arr[0];
        $_a_1_len = strlen($_a_1);
        $_a_1_arr = array();
        for($i=0; $i<$_a_1_len; $i++) {
            $_a_1_arr[$i] = $_a_1[$i];
        }
        $_a_1_str = implode(",",$_a_1_arr);

        $_a_2 = $unum_arr[1];
        $_a_2_len = strlen($_a_2);
        $_a_2_arr = array();
        for($i=0; $i<$_a_2_len; $i++) {
            $_a_2_arr[$i] = $_a_2[$i];
        }
        $_a_2_str = implode(",",$_a_2_arr);

        $_a_3 = $unum_arr[2];
        $_a_3_len = strlen($_a_3);
        $_a_3_arr = array();
        for($i=0; $i<$_a_3_len; $i++) {
            $_a_3_arr[$i] = $_a_3[$i];
        }
        $_a_3_str = implode(",",$_a_3_arr);

        $_a_4 = $unum_arr[3];
        $_a_4_len = strlen($_a_4);
        $_a_4_arr = array();
        for($i=0; $i<$_a_4_len; $i++) {
            $_a_4_arr[$i] = $_a_4[$i];
        }
        $_a_4_str = implode(",",$_a_4_arr);

        $_a_5 = $unum_arr[4];
        $_a_5_len = strlen($_a_5);
        $_a_5_arr = array();
        for($i=0; $i<$_a_5_len; $i++) {
            $_a_5_arr[$i] = $_a_5[$i];
        }
        $_a_5_str = implode(",",$_a_5_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_dwd=1 AND (FIND_IN_SET(bit_num,'$_a_5_str') OR FIND_IN_SET(ten_num,'$_a_4_str') OR FIND_IN_SET(hundred_num,'$_a_3_str') OR FIND_IN_SET(thousand_num,'$_a_2_str') OR FIND_IN_SET(ten_thousand_num,'$_a_1_str'))", $user_bonus, $lim_num, "dwd_money");
            $model->where($_w . " AND is_dwd=1 AND (FIND_IN_SET(bit_num,'$_a_5_str') OR FIND_IN_SET(ten_num,'$_a_4_str') OR FIND_IN_SET(hundred_num,'$_a_3_str') OR FIND_IN_SET(thousand_num,'$_a_2_str') OR FIND_IN_SET(ten_thousand_num,'$_a_1_str'))")->setInc('dwd_money', $user_bonus);
        } else {
            $model->where($_w." AND is_dwd=1 AND (FIND_IN_SET(bit_num,'$_a_5_str') OR FIND_IN_SET(ten_num,'$_a_4_str') OR FIND_IN_SET(hundred_num,'$_a_3_str') OR FIND_IN_SET(thousand_num,'$_a_2_str') OR FIND_IN_SET(ten_thousand_num,'$_a_1_str'))")->setInc('total_money',$user_bonus);
        }

    }

    //三星一码不定位
    public function lim_sxymbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str'))", $user_bonus, $lim_num, "qsym_money");
                $model->where($_w . " AND is_qsym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str'))")->setInc('qsym_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str'))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsym=1 AND (FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))", $user_bonus, $lim_num, "hsym_money");
                $model->where($_w . " AND is_hsym=1 AND (FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))")->setInc('hsym_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsym=1 AND (FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))")->setInc('total_money',$user_bonus);
            }
        }


    }

    //三星二码不定位
    public function lim_sxembdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsem=1 AND ((FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))", $user_bonus, $lim_num, "qsem_money");
                $model->where($_w . " AND is_qsem=1 AND ((FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))")->setInc('qsem_money', $user_bonus);
            }else {
                $model->where($_w." AND is_qsem=1 AND ((FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后三")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsem=1 AND ((FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))", $user_bonus, $lim_num, "hsem_money");
                $model->where($_w . " AND is_hsem=1 AND ((FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))")->setInc('hsem_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsem=1 AND ((FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))")->setInc('total_money',$user_bonus);
            }
        }


    }

    //四星一码不定位
    public function lim_sixymbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前四")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str'))", $user_bonus, $lim_num, "qsym_money");
                $model->where($_w . " AND is_qsym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str'))")->setInc('qsym_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str'))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后四")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsym=1 AND (FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))", $user_bonus, $lim_num, "hsym_money");
                $model->where($_w . " AND is_hsym=1 AND (FIND_IN_SET(FIND_IN_SET(thousand_num,'$unum_str') OR hundred_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))")->setInc('hsym_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsym=1 AND (FIND_IN_SET(FIND_IN_SET(thousand_num,'$unum_str') OR hundred_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))")->setInc('total_money',$user_bonus);
            }
        }


    }

    //四星二码不定位
    public function lim_sixembdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if(strpos($buy_type,"前四")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_qsem=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))", $user_bonus, $lim_num, "qsem_money");
                $model->where($_w . " AND is_qsem=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))")->setInc('qsem_money', $user_bonus);
            } else {
                $model->where($_w." AND is_qsem=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))")->setInc('total_money',$user_bonus);
            }
        }

        if(strpos($buy_type,"后四")!==false){
            if(!in_array($lid,array(17,18,19))) {
                self::getMaxBonus($model, $_w . " AND is_hsem=1 AND (
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))", $user_bonus, $lim_num, "hsem_money");
                $model->where($_w . " AND is_hsem=1 AND (
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))")->setInc('hsem_money', $user_bonus);
            } else {
                $model->where($_w." AND is_hsem=1 AND (
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))")->setInc('total_money',$user_bonus);
            }
        }


    }

    //五星一码不定位
    public function lim_wxymbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_wxym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(ten_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str') OR FIND_IN_SET(bit_num,'$unum_str'))", $user_bonus, $lim_num, "wxym_money");
            $model->where($_w . " AND is_wxym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str')  OR FIND_IN_SET(bit_num,'$unum_str'))")->setInc('wxym_money', $user_bonus);
        } else {
            $model->where($_w." AND is_wxym=1 AND (FIND_IN_SET(ten_thousand_num,'$unum_str') OR FIND_IN_SET(thousand_num,'$unum_str') OR FIND_IN_SET(hundred_num,'$unum_str')  OR FIND_IN_SET(bit_num,'$unum_str'))")->setInc('total_money',$user_bonus);
        }

    }

    //五星二码不定位
    public function lim_wxembdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_wxem=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))", $user_bonus, $lim_num, "wxem_money");
            $model->where($_w . " AND is_wxem=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))")->setInc('wxem_money', $user_bonus);
        } else {
            $model->where($_w." AND is_wxem=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')))")->setInc('total_money',$user_bonus);
        }
    }

    //五星三码不定位
    public function lim_wxsmbdw($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            self::getMaxBonus($model, $_w . " AND is_wxsm=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))", $user_bonus, $lim_num, "wxsm_money");
            $model->where($_w . " AND is_wxsm=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))")->setInc('wxsm_money', $user_bonus);
        } else {
            $model->where($_w." AND is_wxsm=1 AND (
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')) OR
            (FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')))")->setInc('total_money',$user_bonus);
        }
    }

    //任二直选复式
    public function lim_renerzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //万位
        $wan = $unum_arr[0];
        $wan_len = strlen($wan);
        $wan_arr = array();
        for($i=0; $i<$wan_len;$i++){
            $wan_arr[$i] = $wan[$i];
        }
        $wan_str = implode(",", $wan_arr);

        //千位
        $qian = $unum_arr[1];
        $qian_len = strlen($qian);
        $qian_arr = array();
        for($i=0; $i<$qian_len;$i++){
            $qian_arr[$i] = $qian[$i];
        }
        $qian_str = implode(",", $qian_arr);

        //百位
        $bai = $unum_arr[2];
        $bai_len = strlen($bai);
        $bai_arr = array();
        for($i=0; $i<$bai_len;$i++){
            $bai_arr[$i] = $bai[$i];
        }
        $bai_str = implode(",", $bai_arr);

        //十位
        $shi = $unum_arr[3];
        $shi_len = strlen($shi);
        $shi_arr = array();
        for($i=0; $i<$shi_len;$i++){
            $shi_arr[$i] = $shi[$i];
        }
        $shi_str = implode(",", $shi_arr);

        //个位
        $ge = $unum_arr[4];
        $ge_len = strlen($ge);
        $ge_arr = array();
        for($i=0; $i<$ge_len;$i++){
            $ge_arr[$i] = $ge[$i];
        }
        $ge_str = implode(",", $ge_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        $positionArray = explode("、", $position);
        $len = count($positionArray);

        $diff = array();
        for ($i = 0; $i < $len; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                array_push($diff, $positionArray[$i] . "|" . $positionArray[$j]);
            }
        }
        foreach ($diff as $dk => $dval) {
            $dtemp = explode("|", $dval);
            if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "千位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(thousand_num,'$qian_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(thousand_num,'$qian_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(thousand_num,'$qian_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "百位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(hundred_num,'$bai_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(hundred_num,'$bai_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(hundred_num,'$bai_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "十位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(ten_num,'$shi_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "个位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(bit_num,'$ge_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "百位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(hundred_num,'$bai_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(hundred_num,'$bai_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(hundred_num,'$bai_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "十位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_num,'$shi_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "个位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(bit_num,'$ge_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "十位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_num,'$shi_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "个位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(bit_num,'$ge_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money', $user_bonus);
                }
            } else if (strpos($dtemp[0], "十位") !== false && strpos($dtemp[1], "个位") !== false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(bit_num,'$ge_str')", $user_bonus, $lim_num, "zxfs_money");
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('zxfs_money', $user_bonus);
                } else {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money', $user_bonus);
                }

            }
        }
    }

    // 任二直选单式
    public function lim_renerzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w)
    {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);

        $diff = array();
        for ($i = 0; $i < $len; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                array_push($diff, $positionArray[$i] . "|" . $positionArray[$j]);
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);
        $unum_count = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            for ($i = 0; $i < $unum_count; $i++) {
                foreach ($diff as $dk => $dval) {
                    $dtemp = explode("|", $dval);
                    if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "千位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_qian_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "百位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_bai_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "十位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_shi_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_ge_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "百位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND qian_bai_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "十位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND qian_shi_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND qian_ge_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "十位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND bai_shi_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND bai_ge_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "十位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND shi_ge_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zxds_money", $unum_arr[$i]);

                    }
                }
            }}
        */
        for ($i = 0; $i < $unum_count; $i++) {
            foreach ($diff as $dk => $dval) {
                $dtemp = explode("|", $dval);
                if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "千位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND wan_qian_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_qian_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "百位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND wan_bai_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_bai_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "十位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND wan_shi_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_shi_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND wan_ge_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_ge_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "百位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND qian_bai_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND qian_bai_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "十位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND qian_shi_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND qian_shi_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND qian_ge_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND qian_ge_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "十位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND bai_shi_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND bai_shi_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND bai_ge_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND bai_ge_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "十位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zxds=1 AND shi_ge_num=\"{$unum_arr[$i]}\"")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND shi_ge_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }

                }
            }
        }
    }

    //任二直选和值
    public function lim_renerzxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                array_push($diff,$positionArray[$i]."|".$positionArray[$j]);
            }
        }

        $unum = rtrim($unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_qian_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_bai_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_bai_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_bai_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_shi_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_shi_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_shi_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_ge_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_ge_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_ge_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_bai_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_bai_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(qian_bai_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_shi_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_shi_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(qian_shi_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_ge_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_ge_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(qian_ge_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(bai_shi_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(bai_shi_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(bai_shi_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(bai_ge_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(bai_ge_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(bai_ge_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "十位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(shi_ge_zxhe_num,'$unum')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(shi_ge_zxhe_num,'$unum')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(shi_ge_zxhe_num,'$unum')")->setInc('total_money',$user_bonus);
                }
            }
        }

    }

    //任二组选复式
    public function lim_renerzhuxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                array_push($diff,$positionArray[$i]."|".$positionArray[$j]);
            }
        }

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len; $i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_qian_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_qian_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_qian_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_qian_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(wan_qian_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_qian_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_bai_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_bai_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_bai_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_bai_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(wan_bai_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_bai_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_shi_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_shi_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(wan_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_shi_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_ge_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(wan_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_ge_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(wan_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(wan_ge_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(qian_bai_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_bai_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(qian_bai_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_bai_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(qian_bai_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_bai_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(qian_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_shi_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(qian_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_shi_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(qian_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_shi_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(qian_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_ge_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(qian_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_ge_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(qian_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(qian_ge_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(bai_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(bai_shi_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(bai_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(bai_shi_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(bai_shi_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(bai_shi_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(bai_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(bai_ge_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(bai_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(bai_ge_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(bai_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(bai_ge_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "十位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxfs=1 AND FIND_IN_SET(shi_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(shi_ge_zhuxfs_num2,'$unum_str')", $user_bonus, $lim_num, "zhuxfs_money");
                    $model->where($_w . " AND is_zhuxfs=1 AND FIND_IN_SET(shi_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(shi_ge_zhuxfs_num2,'$unum_str')")->setInc('zhuxfs_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxfs=1 AND FIND_IN_SET(shi_ge_zhuxfs_num1,'$unum_str') AND FIND_IN_SET(shi_ge_zhuxfs_num2,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            }
        }

    }

    //任二组选单式
    public function lim_renerzhuxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num,$position,$_w)
    {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);

        $diff = array();
        for ($i = 0; $i < $len; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                array_push($diff, $positionArray[$i] . "|" . $positionArray[$j]);
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);
        $unum_count = count($unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            for ($i = 0; $i < $unum_count; $i++) {
                foreach ($diff as $dk => $dval) {
                    $dtemp = explode("|", $dval);
                    if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "千位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND wan_qian_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "百位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND wan_bai_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "十位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND wan_shi_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND wan_ge_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "百位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND qian_bai_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "十位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND qian_shi_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND qian_ge_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "十位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND bai_shi_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND bai_ge_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);
                    } else if (strpos($dtemp[0], "十位") !== false && strpos($dtemp[1], "个位") !== false) {
                        self::getMaxBonus($model, $_w." AND is_zhuxds=1 AND shi_ge_zhuxds_num=\"{$unum_arr[$i]}\"", $user_bonus, $lim_num, "zhuxds_money", $unum_arr[$i]);

                    }
                }
            }}
        */
        for ($i = 0; $i < $unum_count; $i++) {
            foreach ($diff as $dk => $dval) {
                $dtemp = explode("|", $dval);
                if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "千位") !== false) {
                    /*
                    if(!in_array($lid,array(17,18,19))) {
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND wan_qian_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money',$user_bonus);
                    }*/
                    $model->where($_w." AND is_zhuxds=1 AND wan_qian_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "百位") !== false) {
                    /*
                    if(!in_array($lid,array(17,18,19))) {
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND wan_bai_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money',$user_bonus);
                    }*/
                    $model->where($_w." AND is_zhuxds=1 AND wan_bai_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "十位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND wan_shi_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND wan_shi_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "万位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND wan_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND wan_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "百位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND qian_bai_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND qian_bai_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "十位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND qian_shi_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND qian_shi_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "千位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND qian_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND qian_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "十位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND bai_shi_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND bai_shi_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "百位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND bai_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND bai_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }
                } else if (strpos($dtemp[0], "十位") !== false && strpos($dtemp[1], "个位") !== false) {
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_zhuxds=1 AND shi_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('zhuxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zhuxds=1 AND shi_ge_zhuxds_num=\"{$unum_arr[$i]}\"")->setInc('total_money',$user_bonus);
                    }

                }
            }
        }
    }

    //任二组选和值
    public function lim_renerzhuxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                array_push($diff,$positionArray[$i]."|".$positionArray[$j]);
            }
        }

        $unum = rtrim($unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_qian_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_qian_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(wan_qian_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_bai_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_bai_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(wan_bai_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_shi_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_shi_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(wan_shi_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_ge_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(wan_ge_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(wan_ge_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(qian_bai_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(qian_bai_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(qian_bai_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(qian_shi_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(qian_shi_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(qian_shi_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(qian_ge_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(qian_ge_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(qian_ge_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(bai_shi_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(bai_shi_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(bai_shi_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(bai_ge_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(bai_ge_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(bai_ge_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "十位")!==false && strpos($dtemp[1], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zhuxhz=1 AND FIND_IN_SET(shi_ge_zhuxhz_num,'$unum')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND is_zhuxhz=1 AND FIND_IN_SET(shi_ge_zhuxhz_num,'$unum')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zhuxhz=1 AND FIND_IN_SET(shi_ge_zhuxhz_num,'$unum')")->setInc('total_money',$user_bonus);
                }
            }
        }

    }

    //任三直选复式
    public function lim_rensanzxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //万位
        $wan = $unum_arr[0];
        $wan_len = strlen($wan);
        $wan_arr = array();
        for($i=0; $i<$wan_len;$i++){
            $wan_arr[$i] = $wan[$i];
        }
        $wan_str = implode(",", $wan_arr);

        //千位
        $qian = $unum_arr[1];
        $qian_len = strlen($qian);
        $qian_arr = array();
        for($i=0; $i<$qian_len;$i++){
            $qian_arr[$i] = $qian[$i];
        }
        $qian_str = implode(",", $qian_arr);

        //百位
        $bai = $unum_arr[2];
        $bai_len = strlen($bai);
        $bai_arr = array();
        for($i=0; $i<$bai_len;$i++){
            $bai_arr[$i] = $bai[$i];
        }
        $bai_str = implode(",", $bai_arr);

        //十位
        $shi = $unum_arr[3];
        $shi_len = strlen($shi);
        $shi_arr = array();
        for($i=0; $i<$shi_len;$i++){
            $shi_arr[$i] = $shi[$i];
        }
        $shi_str = implode(",", $shi_arr);

        //个位
        $ge = $unum_arr[4];
        $ge_len = strlen($ge);
        $ge_arr = array();
        for($i=0; $i<$ge_len;$i++){
            $ge_arr[$i] = $ge[$i];
        }
        $ge_str = implode(",", $ge_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND wan_qian_bai_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(hundred_num,'$bai_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND wan_qian_shi_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND wan_qian_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND wan_bai_shi_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND wan_bai_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND wan_shi_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(ten_thousand_num,'$wan_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND qian_bai_shi_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_num,'$shi_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND qian_bai_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money',$user_bonus);
                }

            }  else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND qian_shi_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    //$model->where($_w . " AND is_zxds=1 AND bai_shi_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxfs=1 AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(bit_num,'$ge_str')")->setInc('total_money',$user_bonus);
                }
            }
        }
    }

    //任三直选单式
    public function lim_rensanzxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            foreach ($unum_arr as $val){
                foreach ($diff as $dk=>$dval){
                    $dtemp = explode("|", $dval);
                    if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_qian_bai_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_qian_shi_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_qian_ge_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_bai_shi_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_bai_ge_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND wan_shi_ge_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND qian_bai_shi_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND qian_bai_ge_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    }  else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND qian_shi_ge_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);

                    } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {

                        self::getMaxBonus($model, $_w." AND is_zxds=1 AND bai_shi_ge_num='{$val}'", $user_bonus, $lim_num, "zxds_money",$val);
                    }
                }
            }}
        */
        foreach ($unum_arr as $val){
            foreach ($diff as $dk=>$dval){
                $dtemp = explode("|", $dval);
                if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND wan_qian_bai_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_qian_bai_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND wan_qian_shi_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_qian_shi_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND wan_qian_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_qian_ge_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND wan_bai_shi_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_bai_shi_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND wan_bai_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_bai_ge_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND wan_shi_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND wan_shi_ge_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND qian_bai_shi_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND qian_bai_shi_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND qian_bai_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND qian_bai_ge_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                }  else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND qian_shi_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND qian_shi_ge_num='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND is_zxds=1 AND bai_shi_ge_num='{$val}'")->setInc('zxds_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_zxds=1 AND bai_shi_ge_num='{$val}'")->setInc('total_money',$user_bonus);
                    }
                }
            }
        }

    }

    //任三直选和值
    public function lim_rensanzxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr =array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_bai_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_bai_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_qian_bai_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_shi_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_shi_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_qian_shi_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_ge_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_qian_ge_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_qian_ge_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_bai_shi_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_bai_shi_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_bai_shi_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_bai_ge_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_bai_ge_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_bai_ge_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_shi_ge_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(wan_shi_ge_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(wan_shi_ge_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_bai_shi_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_bai_shi_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(qian_bai_shi_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_bai_ge_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_bai_ge_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(qian_bai_ge_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_shi_ge_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(qian_shi_ge_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(qian_shi_ge_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zxhz=1 AND FIND_IN_SET(bai_shi_ge_zxhe_num,'$unum_str')", $user_bonus, $lim_num, "zxhz_money");
                    $model->where($_w . " AND is_zxhz=1 AND FIND_IN_SET(bai_shi_ge_zxhe_num,'$unum_str')")->setInc('zxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zxhz=1 AND FIND_IN_SET(bai_shi_ge_zxhe_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            }
        }
    }

    //组三复式
    public function lim_rensanz3fs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr =array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqb_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_wqb_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqb_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_wqg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_wqg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbs_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_wbs_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbs_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_wbg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wsg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_wsg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wsg_z3=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbs_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_qbs_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbs_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbg_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_qbg_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbg_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qsg_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_qsg_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qsg_z3=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_bsg_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z3_money");
                    $model->where($_w . " AND is_bsg_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z3_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_bsg_z3=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            }
        }

    }

    //组三单式
    public function lim_rensanz3ds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        if(!in_array($lid,array(17,18,19))) {
            foreach ($unum_arr as $val){
                foreach ($diff as $dk=>$dval){
                    $dtemp = explode("|", $dval);
                    if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wqb_z3=1 AND wqb_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wqs_z3=1 AND wqs_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wqg_z3=1 AND wqg_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wbs_z3=1 AND wbs_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wbg_z3=1 AND wbg_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wsg_z3=1 AND wsg_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND is_qbs_z3=1 AND qbs_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_qbg_z3=1 AND qbg_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_qsg_z3=1 AND qsg_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_bsg_z3=1 AND bsg_z3_number='{$val}'", $user_bonus, $lim_num, "z3_money",$val);

                    }
                }
            }}

        foreach ($unum_arr as $val){
            foreach ($diff as $dk=>$dval){
                $dtemp = explode("|", $dval);
                if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wqb_z3=1 AND wqb_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wqb_z3=1 AND wqb_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wqs_z3=1 AND wqs_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wqs_z3=1 AND wqs_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wqg_z3=1 AND wqg_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wqg_z3=1 AND wqg_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wbs_z3=1 AND wbs_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wbs_z3=1 AND wbs_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wbg_z3=1 AND wbg_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wbg_z3=1 AND wbg_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wsg_z3=1 AND wsg_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wsg_z3=1 AND wsg_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_qbs_z3=1 AND qbs_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_qbs_z3=1 AND qbs_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_qbg_z3=1 AND qbg_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_qbg_z3=1 AND qbg_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_qsg_z3=1 AND qsg_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_qsg_z3=1 AND qsg_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_bsg_z3=1 AND bsg_z3_number='{$val}'")->setInc('z3_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_bsg_z3=1 AND bsg_z3_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                }
            }
        }

    }

    //组六复式
    public function lim_rensanz6fs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr =array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        //$user_bonus = 283.33;

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqb_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_wqb_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqb_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_wqg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_wqg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbs_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_wbs_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbs_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_wbg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wsg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_wsg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wsg_z6=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbs_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_qbs_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbs_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbg_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_qbg_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbg_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qsg_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_qsg_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qsg_z6=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_bsg_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')", $user_bonus, $lim_num, "z6_money");
                    $model->where($_w . " AND is_bsg_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('z6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_bsg_z6=1 AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            }
        }
    }

    //组六单式
    public function lim_rensanz6ds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            foreach ($unum_arr as $val){
                foreach ($diff as $dk=>$dval){
                    $dtemp = explode("|", $dval);
                    if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wqb_z6=1 AND wqb_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wqs_z6=1 AND wqs_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wqg_z6=1 AND wqg_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wbs_z6=1 AND wbs_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wbg_z6=1 AND wbg_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_wsg_z6=1 AND wsg_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND is_qbs_z6=1 AND qbs_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_qbg_z6=1 AND qbg_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_qsg_z6=1 AND qsg_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND is_bsg_z6=1 AND bsg_z6_number='{$val}'", $user_bonus, $lim_num, "z6_money",$val);

                    }
                }
            }}
        */
        foreach ($unum_arr as $val){
            foreach ($diff as $dk=>$dval){
                $dtemp = explode("|", $dval);
                if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wqb_z6=1 AND wqb_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wqb_z6=1 AND wqb_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wqs_z6=1 AND wqs_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wqs_z6=1 AND wqs_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wqg_z6=1 AND wqg_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wqg_z6=1 AND wqg_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wbs_z6=1 AND wbs_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wbs_z6=1 AND wbs_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wbg_z6=1 AND wbg_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wbg_z6=1 AND wbg_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_wsg_z6=1 AND wsg_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_wsg_z6=1 AND wsg_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_qbs_z6=1 AND qbs_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_qbs_z6=1 AND qbs_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_qbg_z6=1 AND qbg_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_qbg_z6=1 AND qbg_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_qsg_z6=1 AND qsg_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_qsg_z6=1 AND qsg_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        $model->where($_w . " AND is_bsg_z6=1 AND bsg_z6_number='{$val}'")->setInc('z6_money', $user_bonus);
                    } else {
                        $model->where($_w." AND is_bsg_z6=1 AND bsg_z6_number='{$val}'")->setInc('total_money',$user_bonus);
                    }

                }
            }
        }

    }

    //混合组选
    public function lim_rensanhhzx($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);
        /*
        if(!in_array($lid,array(17,18,19))) {
            foreach ($unum_arr as $val){
                foreach ($diff as $dk=>$dval){
                    $dtemp = explode("|", $dval);
                    if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_wqb_z6=1 AND wqb_z6_number='{$val}') OR (is_wqb_z3=1 AND wqb_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_wqs_z6=1 AND wqs_z6_number='{$val}') OR (is_wqs_z3=1 AND wqs_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_wqg_z6=1 AND wqg_z6_number='{$val}') OR (is_wqg_z3=1 AND wqg_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_wbs_z6=1 AND wbs_z6_number='{$val}') OR (is_wbs_z3=1 AND wbs_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_wbg_z6=1 AND wbg_z6_number='{$val}') OR (is_wbg_z3=1 AND wbg_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_wsg_z6=1 AND wsg_z6_number='{$val}') OR (is_wsg_z3=1 AND wsg_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_qbs_z6=1 AND qbs_z6_number='{$val}') OR (is_qbs_z3=1 AND qbs_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_qbg_z6=1 AND qbg_z6_number='{$val}') OR (is_qbg_z3=1 AND qbg_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_qsg_z6=1 AND qsg_z6_number='{$val}') OR (is_qsg_z3=1 AND qsg_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){

                        self::getMaxBonus($model, $_w." AND ((is_bsg_z6=1 AND bsg_z6_number='{$val}') OR (is_bsg_z3=1 AND bsg_z3_number='{$val}'))", $user_bonus, $lim_num, "hh_money",$val);

                    }
                }
            }}
        */
        foreach ($unum_arr as $val){
            foreach ($diff as $dk=>$dval){
                $dtemp = explode("|", $dval);
                if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_wqb_z6=1 AND wqb_z6_number='{$val}') OR (is_wqb_z3=1 AND wqb_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_wqb_z6=1 AND wqb_z6_number='{$val}') OR (is_wqb_z3=1 AND wqb_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_wqs_z6=1 AND wqs_z6_number='{$val}') OR (is_wqs_z3=1 AND wqs_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_wqs_z6=1 AND wqs_z6_number='{$val}') OR (is_wqs_z3=1 AND wqs_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_wqg_z6=1 AND wqg_z6_number='{$val}') OR (is_wqg_z3=1 AND wqg_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_wqg_z6=1 AND wqg_z6_number='{$val}') OR (is_wqg_z3=1 AND wqg_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_wbs_z6=1 AND wbs_z6_number='{$val}') OR (is_wbs_z3=1 AND wbs_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_wbs_z6=1 AND wbs_z6_number='{$val}') OR (is_wbs_z3=1 AND wbs_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_wbg_z6=1 AND wbg_z6_number='{$val}') OR (is_wbg_z3=1 AND wbg_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_wbg_z6=1 AND wbg_z6_number='{$val}') OR (is_wbg_z3=1 AND wbg_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_wsg_z6=1 AND wsg_z6_number='{$val}') OR (is_wsg_z3=1 AND wsg_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_wsg_z6=1 AND wsg_z6_number='{$val}') OR (is_wsg_z3=1 AND wsg_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_qbs_z6=1 AND qbs_z6_number='{$val}') OR (is_qbs_z3=1 AND qbs_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_qbs_z6=1 AND qbs_z6_number='{$val}') OR (is_qbs_z3=1 AND qbs_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_qbg_z6=1 AND qbg_z6_number='{$val}') OR (is_qbg_z3=1 AND qbg_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_qbg_z6=1 AND qbg_z6_number='{$val}') OR (is_qbg_z3=1 AND qbg_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_qsg_z6=1 AND qsg_z6_number='{$val}') OR (is_qsg_z3=1 AND qsg_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_qsg_z6=1 AND qsg_z6_number='{$val}') OR (is_qsg_z3=1 AND qsg_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false){
                    if(!in_array($lid,array(17,18,19))) {
                        //$model->where($_w . " AND ((is_bsg_z6=1 AND bsg_z6_number='{$val}') OR (is_qsg_z3=1 AND qsg_z3_number='{$val}'))")->setInc('hh_money', $user_bonus);
                    } else {
                        $model->where($_w." AND ((is_bsg_z6=1 AND bsg_z6_number='{$val}') OR (is_qsg_z3=1 AND qsg_z3_number='{$val}'))")->setInc('total_money',$user_bonus);
                    }

                }
            }
        }

    }

    //组选和值
    public function lim_rensanzhuxhz($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$k])
                        array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]);
                }
            }
        }

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr =array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(wan_qian_bai_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(wan_qian_bai_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(wan_qian_bai_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(wan_qian_shi_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(wan_qian_shi_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(wan_qian_shi_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(wan_qian_ge_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(wan_qian_ge_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(wan_qian_ge_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(wan_bai_shi_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(wan_bai_shi_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(wan_bai_shi_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(wan_bai_ge_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(wan_bai_ge_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(wan_bai_ge_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(wan_shi_ge_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(wan_shi_ge_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(wan_shi_ge_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(qian_bai_shi_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(qian_bai_shi_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(qian_bai_shi_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(qian_bai_ge_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(qian_bai_ge_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(qian_bai_ge_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(qian_shi_ge_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(qian_shi_ge_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(qian_shi_ge_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            } else if(strpos($dtemp[0], "百位")!==false && strpos($dtemp[1], "十位")!==false && strpos($dtemp[2], "个位")!==false) {
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND FIND_IN_SET(bai_shi_ge_zhuxhz_num,'$unum_str')", $user_bonus, $lim_num, "zhuxhz_money");
                    $model->where($_w . " AND FIND_IN_SET(bai_shi_ge_zhuxhz_num,'$unum_str')")->setInc('zhuxhz_money', $user_bonus);
                } else {
                    $model->where($_w." AND FIND_IN_SET(bai_shi_ge_zhuxhz_num,'$unum_str')")->setInc('total_money',$user_bonus);
                }
            }
        }
    }

    //任四直选复式
    public function lim_rensizxfs($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        //万位
        $wan = $unum_arr[0];
        $wan_len = strlen($wan);
        $wan_arr = array();
        for($i=0; $i<$wan_len;$i++){
            $wan_arr[$i] = $wan[$i];
        }
        $wan_str = implode(",", $wan_arr);

        //千位
        $qian = $unum_arr[1];
        $qian_len = strlen($qian);
        $qian_arr = array();
        for($i=0; $i<$qian_len;$i++){
            $qian_arr[$i] = $qian[$i];
        }
        $qian_str = implode(",", $qian_arr);

        //百位
        $bai = $unum_arr[2];
        $bai_len = strlen($bai);
        $bai_arr = array();
        for($i=0; $i<$bai_len;$i++){
            $bai_arr[$i] = $bai[$i];
        }
        $bai_str = implode(",", $bai_arr);

        //十位
        $shi = $unum_arr[3];
        $shi_len = strlen($shi);
        $shi_arr = array();
        for($i=0; $i<$shi_len;$i++){
            $shi_arr[$i] = $shi[$i];
        }
        $shi_str = implode(",", $shi_arr);

        //个位
        $ge = $unum_arr[4];
        $ge_len = strlen($ge);
        $ge_arr = array();
        for($i=0; $i<$ge_len;$i++){
            $ge_arr[$i] = $ge[$i];
        }
        $ge_str = implode(",", $ge_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        $positionArray = explode("、", $position);

        $len = count($positionArray);
        if($lid==17 || $lid==18 || $lid==19) {
            if ($len == 4) {
                if (strpos($positionArray[0], "万位") !== false && strpos($positionArray[0], "千位") !== false && strpos($positionArray[0], "百位") !== false && strpos($positionArray[0], "十位") !== false) {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                } else if (strpos($positionArray[0], "万位") !== false && strpos($positionArray[1], "千位") !== false && strpos($positionArray[2], "百位") !== false && strpos($positionArray[3], "个位") !== false) {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                } else if (strpos($positionArray[0], "万位") !== false && strpos($positionArray[1], "千位") !== false && strpos($positionArray[2], "十位") !== false && strpos($positionArray[3], "个位") !== false) {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                } else if (strpos($positionArray[0], "万位") !== false && strpos($positionArray[1], "百位") !== false && strpos($positionArray[2], "十位") !== false && strpos($positionArray[3], "个位") !== false) {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                } else if (strpos($positionArray[0], "千位") !== false && strpos($positionArray[1], "百位") !== false && strpos($positionArray[2], "十位") !== false && strpos($positionArray[3], "个位") !== false) {
                    $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(thousand_num,'$qian_str')")->setInc('total_money', $user_bonus);
                }
            } else if ($len == 5) {
                $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(thousand_num,'$qian_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(ten_thousand_num,'$wan_str')")->setInc('total_money', $user_bonus);
                $model->where($_w . " AND is_zxfs=1 AND FIND_IN_SET(bit_num,'$ge_str') AND FIND_IN_SET(ten_num,'$shi_str') AND FIND_IN_SET(hundred_num,'$bai_str') AND FIND_IN_SET(thousand_num,'$qian_str')")->setInc('total_money', $user_bonus);
            }
        }
    }

    //任四直选单式
    public function lim_rensizxds($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);

        $len = count($positionArray);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        if($len==4){
            if(strpos($positionArray[0], "万位")!==false && strpos($positionArray[0], "千位")!==false && strpos($positionArray[0], "百位")!==false && strpos($positionArray[0], "十位")!==false){
                $model->where($_w." AND is_zxds=1 AND wqbs_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            } else if(strpos($positionArray[0], "万位")!==false && strpos($positionArray[1], "千位")!==false && strpos($positionArray[2], "百位")!==false && strpos($positionArray[3], "个位")!==false){
                $model->where($_w." AND is_zxds=1 AND wqbg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            } else if(strpos($positionArray[0], "万位")!==false && strpos($positionArray[1], "千位")!==false && strpos($positionArray[2], "十位")!==false && strpos($positionArray[3], "个位")!==false){
                $model->where($_w." AND is_zxds=1 AND wqsg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            } else if(strpos($positionArray[0], "万位")!==false && strpos($positionArray[1], "百位")!==false && strpos($positionArray[2], "十位")!==false && strpos($positionArray[3], "个位")!==false){
                $model->where($_w." AND is_zxds=1 AND wbsg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            } else if(strpos($positionArray[0], "千位")!==false && strpos($positionArray[1], "百位")!==false && strpos($positionArray[2], "十位")!==false && strpos($positionArray[3], "个位")!==false){
                $model->where($_w." AND is_zxds=1 AND qbsg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            }
        } else if($len==5){
            $model->where($_w." AND is_zxds=1 AND wqbs_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            $model->where($_w." AND is_zxds=1 AND wqbg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            $model->where($_w." AND is_zxds=1 AND wqsg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            $model->where($_w." AND is_zxds=1 AND wbsg_number IN (".$unum.")")->setInc('total_money',$user_bonus);
            $model->where($_w." AND is_zxds=1 AND qbsg_number IN (".$unum.")")->setInc('total_money',$user_bonus);

        }
    }

    //任四组选24
    public function lim_rensizx24($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    for($q=$k+1;$q<$len;$q++){
                        if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$i]!=$positionArray[$q] && $positionArray[$j]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$q] && $positionArray[$k]!=$positionArray[$q])
                            array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]."|".$positionArray[$q]);
                    }
                }
            }
        }
        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "十位")!==false){
                $w = " AND ten_thousand_num!=thousand_num AND ten_thousand_num!=hundred_num AND ten_thousand_num!=ten_num AND thousand_num!=hundred_num AND thousand_num!=ten_num AND hundred_num!=ten_num";
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') ".$w, $user_bonus, $lim_num, "zx24_money");
                    $model->where($_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') ".$w)->setInc('zx24_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') ".$w)->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "个位")!==false){
                $w = " AND ten_thousand_num!=thousand_num AND ten_thousand_num!=hundred_num AND ten_thousand_num!=bit_num AND thousand_num!=hundred_num AND thousand_num!=bit_num AND hundred_num!=bit_num";
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w, $user_bonus, $lim_num, "zx24_money");
                    $model->where($_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('zx24_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('total_money',$user_bonus);
                }


            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                $w = " AND ten_thousand_num!=thousand_num AND ten_thousand_num!=ten_num AND ten_thousand_num!=bit_num AND thousand_num!=ten_num AND thousand_num!=bit_num AND ten_num!=bit_num";
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w, $user_bonus, $lim_num, "zx24_money");
                    $model->where($_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('zx24_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('total_money',$user_bonus);
                }


            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                $w = " AND ten_thousand_num!=hundred_num AND ten_thousand_num!=ten_num AND ten_thousand_num!=bit_num AND hundred_num!=ten_num AND hundred_num!=bit_num AND ten_num!=bit_num";
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w, $user_bonus, $lim_num, "zx24_money");
                    $model->where($_w . " AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('zx24_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zx24=1 AND FIND_IN_SET(ten_thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('total_money',$user_bonus);
                }


            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                $w = " AND thousand_num!=hundred_num AND thousand_num!=ten_num AND thousand_num!=bit_num AND hundred_num!=ten_num AND hundred_num!=bit_num AND ten_num!=bit_num";
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_zx24=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w, $user_bonus, $lim_num, "zx24_money");
                    $model->where($_w . " AND is_zx24=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('zx24_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_zx24=1 AND FIND_IN_SET(thousand_num,'$unum_str') AND FIND_IN_SET(hundred_num,'$unum_str') AND FIND_IN_SET(ten_num,'$unum_str') AND FIND_IN_SET(bit_num,'$unum_str') ".$w)->setInc('total_money',$user_bonus);
                }
            }
        }
    }

    //任四组选12
    public function lim_rensizx12($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    for($q=$k+1;$q<$len;$q++){
                        if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$i]!=$positionArray[$q] && $positionArray[$j]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$q] && $positionArray[$k]!=$positionArray[$q])
                            array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]."|".$positionArray[$q]);
                    }
                }
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $ch = $unum_arr[0];
        $ch_len = strlen($ch);
        $ch_arr = array();
        for($i=0; $i<$ch_len; $i++) {
            $ch_arr[$i] = $ch[$i];
        }
        $ch_str = implode(",", $ch_arr);

        $dh = $unum_arr[1];
        $dh_len = strlen($dh);
        $dh_arr = array();
        for($i=0; $i<$dh_len; $i++) {
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqbs_zx12=1 AND FIND_IN_SET(wqbs_zx12_erch,'$ch_str') AND FIND_IN_SET(wqbs_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqbs_zx12_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx12_money");
                    $model->where($_w . " AND is_wqbs_zx12=1 AND FIND_IN_SET(wqbs_zx12_erch,'$ch_str') AND FIND_IN_SET(wqbs_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqbs_zx12_dh_two,'$dh_str')")->setInc('zx12_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqbs_zx12=1 AND FIND_IN_SET(wqbs_zx12_erch,'$ch_str') AND FIND_IN_SET(wqbs_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqbs_zx12_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqbg_zx12=1 AND FIND_IN_SET(wqbg_zx12_erch,'$ch_str') AND FIND_IN_SET(wqbg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqbg_zx12_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx12_money");
                    $model->where($_w . " AND is_wqbg_zx12=1 AND FIND_IN_SET(wqbg_zx12_erch,'$ch_str') AND FIND_IN_SET(wqbg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqbg_zx12_dh_two,'$dh_str')")->setInc('zx12_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqbg_zx12=1 AND FIND_IN_SET(wqbg_zx12_erch,'$ch_str') AND FIND_IN_SET(wqbg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqbg_zx12_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqsg_zx12=1 AND FIND_IN_SET(wqsg_zx12_erch,'$ch_str') AND FIND_IN_SET(wqsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqsg_zx12_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx12_money");
                    $model->where($_w . " AND is_wqsg_zx12=1 AND FIND_IN_SET(wqsg_zx12_erch,'$ch_str') AND FIND_IN_SET(wqsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqsg_zx12_dh_two,'$dh_str')")->setInc('zx12_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqsg_zx12=1 AND FIND_IN_SET(wqsg_zx12_erch,'$ch_str') AND FIND_IN_SET(wqsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wqsg_zx12_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbsg_zx12=1 AND FIND_IN_SET(wbsg_zx12_erch,'$ch_str') AND FIND_IN_SET(wbsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wbsg_zx12_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx12_money");
                    $model->where($_w . " AND is_wbsg_zx12=1 AND FIND_IN_SET(wbsg_zx12_erch,'$ch_str') AND FIND_IN_SET(wbsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wbsg_zx12_dh_two,'$dh_str')")->setInc('zx12_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbsg_zx12=1 AND FIND_IN_SET(wbsg_zx12_erch,'$ch_str') AND FIND_IN_SET(wbsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(wbsg_zx12_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbsg_zx12=1 AND FIND_IN_SET(qbsg_zx12_erch,'$ch_str') AND FIND_IN_SET(qbsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(qbsg_zx12_dh_two,'$dh_str')", $user_bonus, $lim_num, "zx12_money");
                    $model->where($_w . " AND is_qbsg_zx12=1 AND FIND_IN_SET(qbsg_zx12_erch,'$ch_str') AND FIND_IN_SET(qbsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(qbsg_zx12_dh_two,'$dh_str')")->setInc('zx12_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbsg_zx12=1 AND FIND_IN_SET(qbsg_zx12_erch,'$ch_str') AND FIND_IN_SET(qbsg_zx12_dh_one,'$dh_str') AND FIND_IN_SET(qbsg_zx12_dh_two,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            }
        }
    }

    //任四组选6
    public function lim_rensizx6($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    for($q=$k+1;$q<$len;$q++){
                        if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$i]!=$positionArray[$q] && $positionArray[$j]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$q] && $positionArray[$k]!=$positionArray[$q])
                            array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]."|".$positionArray[$q]);
                    }
                }
            }
        }

        $unum = rtrim($unum);
        $unum_len = strlen($unum);
        $unum_arr = array();
        for($i=0; $i<$unum_len;$i++){
            $unum_arr[$i] = $unum[$i];
        }
        $unum_str = implode(",", $unum_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqbs_zx6=1 AND FIND_IN_SET(wqbs_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqbs_zx6_erch_two,'$unum_str')", $user_bonus, $lim_num, "zx6_money");
                    $model->where($_w . " AND is_wqbs_zx6=1 AND FIND_IN_SET(wqbs_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqbs_zx6_erch_two,'$unum_str')")->setInc('zx6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqbs_zx6=1 AND FIND_IN_SET(wqbs_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqbs_zx6_erch_two,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqbg_zx6=1 AND FIND_IN_SET(wqbg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqbg_zx6_erch_two,'$unum_str')", $user_bonus, $lim_num, "zx6_money");
                    $model->where($_w . " AND is_wqbg_zx6=1 AND FIND_IN_SET(wqbg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqbg_zx6_erch_two,'$unum_str')")->setInc('zx6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqbg_zx6=1 AND FIND_IN_SET(wqbg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqbg_zx6_erch_two,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqsg_zx6=1 AND FIND_IN_SET(wqsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqsg_zx6_erch_two,'$unum_str')", $user_bonus, $lim_num, "zx6_money");
                    $model->where($_w . " AND is_wqsg_zx6=1 AND FIND_IN_SET(wqsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqsg_zx6_erch_two,'$unum_str')")->setInc('zx6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqsg_zx6=1 AND FIND_IN_SET(wqsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wqsg_zx6_erch_two,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbsg_zx6=1 AND FIND_IN_SET(wbsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wbsg_zx6_erch_two,'$unum_str')", $user_bonus, $lim_num, "zx6_money");
                    $model->where($_w . " AND is_wbsg_zx6=1 AND FIND_IN_SET(wbsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wbsg_zx6_erch_two,'$unum_str')")->setInc('zx6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbsg_zx6=1 AND FIND_IN_SET(wbsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(wbsg_zx6_erch_two,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbsg_zx6=1 AND FIND_IN_SET(qbsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(qbsg_zx6_erch_two,'$unum_str')", $user_bonus, $lim_num, "zx6_money");
                    $model->where($_w . " AND is_qbsg_zx6=1 AND FIND_IN_SET(qbsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(qbsg_zx6_erch_two,'$unum_str')")->setInc('zx6_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbsg_zx6=1 AND FIND_IN_SET(qbsg_zx6_erch_one,'$unum_str') AND FIND_IN_SET(qbsg_zx6_erch_two,'$unum_str')")->setInc('total_money',$user_bonus);
                }

            }
        }
    }

    //任四 组选4
    public function lim_rensizx4($uid, $unum, $lottery_number_id, $lid, $buy_type, $mul, $yuan, $bonus_type,$lim_num, $position,$_w) {
        $model = self::getModel($buy_type,$lid);

        $positionArray = explode("、", $position);
        $len = count($positionArray);
        $diff = array();
        for($i=0;$i<$len;$i++){
            for($j=$i+1;$j<$len;$j++){
                for($k=$j+1;$k<$len;$k++){
                    for($q=$k+1;$q<$len;$q++){
                        if($positionArray[$i]!=$positionArray[$j] && $positionArray[$i]!=$positionArray[$k] && $positionArray[$i]!=$positionArray[$q] && $positionArray[$j]!=$positionArray[$k] && $positionArray[$j]!=$positionArray[$q] && $positionArray[$k]!=$positionArray[$q])
                            array_push($diff,$positionArray[$i]."|".$positionArray[$j]."|".$positionArray[$k]."|".$positionArray[$q]);
                    }
                }
            }
        }

        $unum = rtrim($unum);
        $unum_arr = explode(",", $unum);

        $ch = $unum_arr[0];
        $ch_len = strlen($ch);
        $ch_arr = array();
        for($i=0; $i<$ch_len; $i++) {
            $ch_arr[$i] = $ch[$i];
        }
        $ch_str = implode(",", $ch_arr);

        $dh = $unum_arr[1];
        $dh_len = strlen($dh);
        $dh_arr = array();
        for($i=0; $i<$dh_len; $i++) {
            $dh_arr[$i] = $dh[$i];
        }
        $dh_str = implode(",", $dh_arr);

        $user_bonus = self::get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, 1);

        foreach ($diff as $dk=>$dval){
            $dtemp = explode("|", $dval);
            if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "十位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqbs_zx4=1 AND FIND_IN_SET(wqbs_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqbs_zx4_dh,'$dh_str')", $user_bonus, $lim_num, "zx4_money");
                    $model->where($_w . " AND is_wqbs_zx4=1 AND FIND_IN_SET(wqbs_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqbs_zx4_dh,'$dh_str')")->setInc('zx4_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqbs_zx4=1 AND FIND_IN_SET(wqbs_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqbs_zx4_dh,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "百位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqbg_zx4=1 AND FIND_IN_SET(wqbg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqbg_zx4_dh,'$dh_str')", $user_bonus, $lim_num, "zx4_money");
                    $model->where($_w . " AND is_wqbg_zx4=1 AND FIND_IN_SET(wqbg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqbg_zx4_dh,'$dh_str')")->setInc('zx4_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqbg_zx4=1 AND FIND_IN_SET(wqbg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqbg_zx4_dh,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "千位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wqsg_zx4=1 AND FIND_IN_SET(wqsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqsg_zx4_dh,'$dh_str')", $user_bonus, $lim_num, "zx4_money");
                    $model->where($_w . " AND is_wqsg_zx4=1 AND FIND_IN_SET(wqsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqsg_zx4_dh,'$dh_str')")->setInc('zx4_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wqsg_zx4=1 AND FIND_IN_SET(wqsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wqsg_zx4_dh,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "万位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_wbsg_zx4=1 AND FIND_IN_SET(wbsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wbsg_zx4_dh,'$dh_str')", $user_bonus, $lim_num, "zx4_money");
                    $model->where($_w . " AND is_wbsg_zx4=1 AND FIND_IN_SET(wbsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wbsg_zx4_dh,'$dh_str')")->setInc('zx4_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_wbsg_zx4=1 AND FIND_IN_SET(wbsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(wbsg_zx4_dh,'$dh_str')")->setInc('total_money',$user_bonus);
                }

            } else if(strpos($dtemp[0], "千位")!==false && strpos($dtemp[1], "百位")!==false && strpos($dtemp[2], "十位")!==false && strpos($dtemp[3], "个位")!==false){
                if(!in_array($lid,array(17,18,19))) {
                    self::getMaxBonus($model, $_w . " AND is_qbsg_zx4=1 AND FIND_IN_SET(qbsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(qbsg_zx4_dh,'$dh_str')", $user_bonus, $lim_num, "zx4_money");
                    $model->where($_w . " AND is_qbsg_zx4=1 AND FIND_IN_SET(qbsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(qbsg_zx4_dh,'$dh_str')")->setInc('zx4_money', $user_bonus);
                } else {
                    $model->where($_w." AND is_qbsg_zx4=1 AND FIND_IN_SET(qbsg_zx4_sanch,'$ch_str') AND FIND_IN_SET(qbsg_zx4_dh,'$dh_str')")->setInc('total_money',$user_bonus);
                }
            }
        }
    }

    //字符串转数组
    public static function StringToArray($str) {
        $result = array();
        $len = strlen($str);
        $i = 0;
        while($i < $len){
            $chr = ord($str[$i]);
            if($chr == 9 || $chr == 10 || (32 <= $chr && $chr <= 126)) {
                $result[] = substr($str,$i,1);
                $i +=1;
            }elseif(192 <= $chr && $chr <= 223){
                $result[] = substr($str,$i,2);
                $i +=2;
            }elseif(224 <= $chr && $chr <= 239){
                $result[] = substr($str,$i,3);
                $i +=3;
            }elseif(240 <= $chr && $chr <= 247){
                $result[] = substr($str,$i,4);
                $i +=4;
            }elseif(248 <= $chr && $chr <= 251){
                $result[] = substr($str,$i,5);
                $i +=5;
            }elseif(252 <= $chr && $chr <= 253){
                $result[] = substr($str,$i,6);
                $i +=6;
            }
        }
        return $result;
    }

    public static function getModel($buy_type,$lid){
        switch($buy_type){
            case '五星直选复式':
            case '五星直选单式':
            case '组选120':
            case '组选60':
            case '组选30':
            case '组选20':
            case '组选10':
            case '组选5':
            case '一帆风顺':
            case '好事成双':
            case '三星报喜':
            case '四季发财':
            case '定位胆':
            case '五星一码不定位':
            case '五星二码不定位':
            case '五星三码不定位':
                if($lid==1)
                    $model = M("lim_cqssc_wuxing");
                else if($lid==2)
                    $model = M("lim_jxssc_wuxing");
                else if($lid==3)
                    $model = M("lim_xjssc_wuxing");
                else if($lid==4)
                    $model = M("lim_tjssc_wuxing");
                else if($lid==17)
                    $model = M("lim_wfssc_wuxing");
                else if($lid==18)
                    $model = M("lim_efssc_wuxing");
                else if($lid==19)
                    $model = M("lim_yfssc_wuxing");
                break;
            case '前四直选复式' :
            case '前四直选单式' :
            case '后四直选复式' :
            case '后四直选单式' :
            case '组选24' :
            case '组选12' :
            case '组选6' :
            case '组选4' :
            case '前四一码不定位' :
            case '前四二码不定位' :
            case '后四一码不定位' :
            case '后四二码不定位' :
                if($lid==1)
                    $model = M("lim_cqssc_sixing");
                else if($lid==2)
                    $model = M("lim_jxssc_sixing");
                else if($lid==3)
                    $model = M("lim_xjssc_sixing");
                else if($lid==4)
                    $model = M("lim_tjssc_sixing");
                else if($lid==17)
                    $model = M("lim_wfssc_sixing");
                else if($lid==18)
                    $model = M("lim_efssc_sixing");
                else if($lid==19)
                    $model = M("lim_yfssc_sixing");
                break;
            case '前三直选复式' :
            case '前三直选单式' :
            case '前三组三' :
            case '前三组六' :
            case '前三组六胆拖' :
            case '前三混合组选' :
            case '前三一码不定位' :
            case '前三二码不定位' :
            case '前三大小单双' :
                if($lid==1)
                    $model = M("lim_cqssc_qiansanxing");
                else if($lid==2)
                    $model = M("lim_jxssc_qiansanxing");
                else if($lid==3)
                    $model = M("lim_xjssc_qiansanxing");
                else if($lid==4)
                    $model = M("lim_tjssc_qiansanxing");
                else if($lid==17)
                    $model = M("lim_wfssc_qiansanxing");
                else if($lid==18)
                    $model = M("lim_efssc_qiansanxing");
                else if($lid==19)
                    $model = M("lim_yfssc_qiansanxing");
                break;
            case '中三直选复式' :
            case '中三直选单式' :
            case '中三组三' :
            case '中三组六' :
            case '中三组六胆拖' :
            case '中三混合组选' :
                if($lid==1)
                    $model = M("lim_cqssc_zhongsanxing");
                else if($lid==2)
                    $model = M("lim_jxssc_zhongsanxing");
                else if($lid==3)
                    $model = M("lim_xjssc_zhongsanxing");
                else if($lid==4)
                    $model = M("lim_tjssc_zhongsanxing");
                else if($lid==17)
                    $model = M("lim_wfssc_zhongsanxing");
                else if($lid==18)
                    $model = M("lim_efssc_zhongsanxing");
                else if($lid==19)
                    $model = M("lim_yfssc_zhongsanxing");
                break;
            case '后三直选复式' :
            case '后三直选单式' :
            case '后三组三' :
            case '后三组六' :
            case '后三组六胆拖' :
            case '后三混合组选' :
            case '后三一码不定位' :
            case '后三二码不定位' :
            case '后三大小单双' :
                if($lid==1)
                    $model = M("lim_cqssc_housanxing");
                else if($lid==2)
                    $model = M("lim_jxssc_housanxing");
                else if($lid==3)
                    $model = M("lim_xjssc_housanxing");
                else if($lid==4)
                    $model = M("lim_tjssc_housanxing");
                else if($lid==17)
                    $model = M("lim_wfssc_housanxing");
                else if($lid==18)
                    $model = M("lim_efssc_housanxing");
                else if($lid==19)
                    $model = M("lim_yfssc_housanxing");
                break;
            case '前二直选复式' :
            case '前二直选单式' :
            case '前二直选和值' :
            case '前二组选复式' :
            case '前二组选单式' :
            case '前二组选胆拖' :
            case '前二组选和值' :
            case '前二大小单双' :
                if($lid==1)
                    $model = M("lim_cqssc_qianerxing");
                else if($lid==2)
                    $model = M("lim_jxssc_qianerxing");
                else if($lid==3)
                    $model = M("lim_xjssc_qianerxing");
                else if($lid==4)
                    $model = M("lim_tjssc_qianerxing");
                else if($lid==17)
                    $model = M("lim_wfssc_qianerxing");
                else if($lid==18)
                    $model = M("lim_efssc_qianerxing");
                else if($lid==19)
                    $model = M("lim_yfssc_qianerxing");
                break;
            case '后二直选复式' :
            case '后二直选单式' :
            case '后二直选和值' :
            case '后二组选复式' :
            case '后二组选单式' :
            case '后二组选胆拖' :
            case '后二组选和值' :
            case '后二大小单双' :
                if($lid==1)
                    $model = M("lim_cqssc_houerxing");
                else if($lid==2)
                    $model = M("lim_jxssc_houerxing");
                else if($lid==3)
                    $model = M("lim_xjssc_houerxing");
                else if($lid==4)
                    $model = M("lim_tjssc_houerxing");
                else if($lid==17)
                    $model = M("lim_wfssc_houerxing");
                else if($lid==18)
                    $model = M("lim_efssc_houerxing");
                else if($lid==19)
                    $model = M("lim_yfssc_houerxing");
                break;
            case '任二直选复式' :
            case '任二直选单式' :
            case '任二直选和值' :
            case '任二组选复式' :
            case '任二组选单式' :
            case '任二组选和值' :
                if($lid==1)
                    $model = M("lim_cqssc_renerxing");
                else if($lid==2)
                    $model = M("lim_jxssc_renerxing");
                else if($lid==3)
                    $model = M("lim_xjssc_renerxing");
                else if($lid==4)
                    $model = M("lim_tjssc_renerxing");
                else if($lid==17)
                    $model = M("lim_wfssc_renerxing");
                else if($lid==18)
                    $model = M("lim_efssc_renerxing");
                else if($lid==19)
                    $model = M("lim_yfssc_renerxing");
                break;
            case '任三直选复式' :
            case '任三直选单式' :
            case '任三直选和值' :
            case '组三复式' :
            case '组三单式' :
            case '组六复式' :
            case '组六单式' :
            case '任三混合组选' :
            case '任三组选和值' :
                if($lid==1)
                    $model = M("lim_cqssc_rensanxing");
                else if($lid==2)
                    $model = M("lim_jxssc_rensanxing");
                else if($lid==3)
                    $model = M("lim_xjssc_rensanxing");
                else if($lid==4)
                    $model = M("lim_tjssc_rensanxing");
                else if($lid==17)
                    $model = M("lim_wfssc_rensanxing");
                else if($lid==18)
                    $model = M("lim_efssc_rensanxing");
                else if($lid==19)
                    $model = M("lim_yfssc_rensanxing");
                break;
            case '任四直选复式' :
            case '任四直选单式' :
            case '任四组选24' :
            case '任四组选12' :
            case '任四组选6' :
            case '任四组选4' :
                if($lid==1)
                    $model = M("lim_cqssc_rensixing");
                else if($lid==2)
                    $model = M("lim_jxssc_rensixing");
                else if($lid==3)
                    $model = M("lim_xjssc_rensixing");
                else if($lid==4)
                    $model = M("lim_tjssc_rensixing");
                else if($lid==17)
                    $model = M("lim_wfssc_rensixing");
                else if($lid==18)
                    $model = M("lim_efssc_rensixing");
                else if($lid==19)
                    $model = M("lim_yfssc_rensixing");
                break;
        }
        return $model;
    }

    public static function getMaxBonus($model, $where, $user_bonus, $lim_num, $findMoney, $buy_num=""){
        if($user_bonus>$lim_num){
            if($buy_num!=""){
                echo json_encode(array("status"=>0,"info"=>"{$buy_num}号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
                exit();
            } else {
                echo json_encode(array("status" => 0, "info" => "该号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
                exit();
            }
        }
        $max_num = $model->where($where)->max($findMoney);
        if($max_num>$lim_num){
            if($buy_num!="") {
                echo json_encode(array("status" => 0, "info" => "{$buy_num}号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
                exit();
            } else {
                echo json_encode(array("status"=>0,"info"=>"该号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
                exit();
            }
        } else if(($max_num+$user_bonus)>$lim_num){
            if($buy_num!="") {
                echo json_encode(array("status" => 0, "info" => "{$buy_num}号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
                exit();
            } else {
                echo json_encode(array("status" => 0, "info" => "该号码累计中奖金额已达到最高赔付！请选择别的号码进行投注"));
                exit();
            }
        }
    }

    //获取用户的返点配置
    public static  function get_user_bonus_config($uid) {

        $rows=M("user_bonus")->where(array("userid"=>$uid))->find();
        return json_decode($rows['bonus_content'],true);
    }

    //获取用户的奖金计算

    public static function get_user_bouns($uid, $lid, $buy_type, $mul, $yuan, $bonus_type, $times){

        $_bonusTypeArr = explode("|",$bonus_type);
        $ssc_bonus = C("ssc_bonus");
        $ssc_bdw_bonus = C("ssc_bonus_bdw");

        //获取用户奖金配置
        $user_bonus_config=self::get_user_bonus_config($uid);

        //获取彩票ID
        $temp_lid=0;
        if(in_array($lid, array(1,2,3,4,17,18,19,20)))
            $temp_lid=1;
        else if(in_array($lid, array(5,6,7,8)))
            $temp_lid=5;
        else if(in_array($lid, array(9,10)))
            $temp_lid = 9;
        else if(in_array($lid, array(11,12)))
            $temp_lid = 11;
        else if($lid==13)
            $temp_lid = 13;
        else if($lid==14)
            $temp_lid = 14;
        else if(in_array($lid, array(15,16)))
            $temp_lid = 15;

        //定义用户奖金
        $user_bonus=0;
        //返点转奖金[设计的彩票,时时彩 11选5 快乐十分,福彩3d，排列三]
        if($_bonusTypeArr[2]==2 && in_array($lid,array(1,2,3,4,5,6,7,8,9,10,11,12,17,18,19,20))){
            $jj = $_bonusTypeArr[0];
            $user_bonus=$jj*$mul*$times;
        }else{
            if(strpos($buy_type,'不定位')!==false){
                $temp_bonus=$user_bonus_config[$temp_lid]['bdw_bonus'];
                $user_bonus=$ssc_bdw_bonus[$temp_bonus][$buy_type]*$mul*$times;
            }else{
                $temp_bonus=$user_bonus_config[$temp_lid]['common_bonus'];
                $user_bonus=$ssc_bonus[$temp_bonus][$buy_type]*$mul*$times;
            }
        }
        //角模式
        if($yuan==1){
            $user_bonus=$user_bonus/10;
        }elseif($yuan==2){
            $user_bonus=$user_bonus/100;
        }elseif($yuan==3){
            $user_bonus=$user_bonus/1000;
        }
        return $user_bonus;
    }
}