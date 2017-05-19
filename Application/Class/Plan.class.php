<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 15/1/1
 * Time: 下午4:18
 */
class Plan{
    public function checknumformat(&$unum,$sub_zhu,$gid,$gtype,$position,$lottery_number_id,$randkey){
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
            $gid = 5;break;
            case 9:
            case 10:
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

            case '五星直选复式':
            case '前五星直选复式':
                self::wxzhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选120':
                self::wxzx120_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选60':
                self::wxzx60_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选30':
                self::wxzx30_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选20':
                self::wxzx20_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选10':
                self::wxzx10_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选5':
                self::wxzx5_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '一帆风顺':
            case '好事成双':
            case '三星报喜':
            case '四季发财':
                self::special($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '五星直选单式 ':
                $unum = trim($unum);
                self::wxzhxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前四直选复式':
            case '后四直选复式':
                self::sxzhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前四直选单式':
            case '后四直选单式':
                $unum = trim($unum);
                self::sxzhxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选24':
                self::wxzx24_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选12':
                self::wxzx12_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选6':
                self::wxzx6_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组选4':
                self::wxzx4_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三直选复式':
            case '中三直选复式':
            case '后三直选复式':
            case '直选复式':
                self::shxzhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三直选单式':
            case '中三直选单式':
            case '后三直选单式':
            case '直选单式':
                $unum = trim($unum);
                self::shxzhxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三组六':
            case '中三组六':
            case '后三组六':
            case '组六':
                self::sxzl_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三组三':
            case '中三组三':
            case '后三组三':
            case '组三':
                self::sxzs_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            //case '前三直选和值':
            //case '后三直选和值':
            case '直选和值':
                self::sxzhxhzh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            //case '前三组选和值':
            //case '后三组选和值':
            //case '组选和值':
            //self::sxzxhzh_zhu($unum,$sub_zhu);
            //break;
            case '前三混合组选':
            case '中三混合组选':
            case '后三混合组选':
            case '混合组选':
                $unum = trim($unum);
                self::sxhhzx_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;

            case '前二直选复式':
            case '后二直选复式':

                self::erxzhxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三组六胆拖':
            case '中三组六胆拖':
            case '后三组六胆拖':

                self::zldt_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前二直选单式':
            case '后二直选单式':
                $unum = trim($unum);
                self::erxzhxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前二直选和值':
            case '后二直选和值':

                self::erxzhxhzh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前二组选复式':
            case '后二组选复式':

                self::erxzxfsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前二组选胆拖':
            case '后二组选胆拖':

                self::qherzxdt_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前二组选单式':
            case '后二组选单式':
                $unum = trim($unum);
                self::erxzxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前二组选和值':
            case '后二组选和值':

                self::erxzxhzh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三大小单双':
            case '后三大小单双':
            case '前二大小单双':
            case '后二大小单双':
            case '三码大小单双':
                self::dxdsh_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '定位胆':
                self::dwd_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三一码不定位':
            case '后三一码不定位':
            case "后四一码不定位":
            case "前四一码不定位":
            case "五星一码不定位":
            case "一码不定位":
                self::ymbdw_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '前三二码不定位':
            case '后三二码不定位':
            case "后四二码不定位":
            case "前四二码不定位":
            case  "五星二码不定位":
            case  '前五星二码不定位':
            case  '二码不定位':

                self::ermbdw_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '五星三码不定位':
            case  '前五星三码不定位':
                self::smbdw_zhu($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '和值':
                self::ks_hezhi($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '三同号通选':
                self::ks_sthtx($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '三同号单选':
                self::ks_sthdx($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '二同号复选':
                self::ks_erthfx($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '二同号单选':
                self::ks_erthdx($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '一不同号':
                self::ks_ybth($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '二不同号':
                self::ks_erbth($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '三不同号':
                self::ks_sanbth($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '三连号通选':
                self::ks_slhtx($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case  '三连号单选':
                self::ks_slhdx($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任二直选复式':
                self::rener_zxfs($unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任二直选单式':
                $unum = trim($unum);
                self::rener_zxds($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任二直选和值':
                self::rener_zxhz($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任二组选复式':
                self::rener_zhuxuanfs($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任二组选单式':
                $unum = trim($unum);
                self::rener_zhuxuands($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任二组选和值':
                self::rener_zhuxuanhz($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任三直选复式':
                self::rensan_zhixuanfs($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任三直选单式':
                $unum = trim($unum);
                self::rensan_zhixuands($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任三直选和值':
                self::rensan_zhixuanhz($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组三复式':
                self::rensan_zhusanfs($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组三单式':
                self::rensan_zhusands($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组六复式':
                self::rensan_zhuliufs($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '组六单式':
                $unum = trim($unum);
                self::rensan_zhuliuds($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任三混合组选':
                $unum = trim($unum);
                self::rensan_hunhezx($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任三组选和值':
                self::rensan_zhuxuanhezhi($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任四直选复式':
                self::rensi_zhixuanfs($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任四直选单式':
                $unum = trim($unum);
                self::rensi_zhixuands($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任四组选24':
                self::rensi_zhixuan24($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任四组选12':
                self::rensi_zhixuan12($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任四组选6':
                self::rensi_zhixuan6($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
            case '任四组选4':
                self::rensi_zhixuan4($unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey);
                break;
        }
    }

    /*
     *
     * 记录非法操作日志到数据库
     *
     * */
    public static function  buy_log($lottery_id,$lottery_number_id,$play_way,$msg){
        $lottery_array = C("LOTTERY");
        $lottery = $lottery_array[$lottery_id]["name"];
        $series_number = M("lottery_number_mid")->where(array("id"=>$lottery_number_id))->getField("series_number");
        $play_way = $play_way;
        $time = time();
        $username = session("SESSION_NAME");
        $content = $msg;
        $buy_lottery_log = M("buy_lottery_log");
        $buy_lottery_log->add(array(
            "username"=>$username,
            "lottery" => $lottery,
            "series_number" => $series_number,
            "play_way" => $play_way,
            "content" => $content,
            "time"  => $time
        ));
        //冻结该用户
        //M("user")->where(array("id"=>session("SESSION_ID")))->save(array("status"=>2));
    }

    /*
     *
     *对于多行选号的判断
     *
     * */

    public static function _check_number($inputNumber,$len,$min,$max,$gid,$lottery_number_id,$gtype,$randkey){
        $temp_num_arr=explode(",",$inputNumber);
        if(count($temp_num_arr)!=$len){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $len = strlen($v);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($v[$i]<$min OR $v[$i]>$max){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                if(!ctype_digit($v[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                array_push($temp,$v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
    }

    /*
     *
     * 针对单行复选的选号判断
     *
     * */
    public static function  _check_number_2($inputNumber,$min,$max,$gid,$lottery_number_id,$gtype,$randkey){
        $temp_num_arr = trim($inputNumber);
        $len = strlen($temp_num_arr);
        $temp = array();
        for($i=0;$i<$len;$i++){
            if($temp_num_arr[$i]<$min OR $temp_num_arr[$i]>$max){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($temp_num_arr[$i])){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            array_push($temp,$temp_num_arr[$i]);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
    }

    //前二后二组选和值号码检查
    public static function _filter_hz($inputNumber,$min,$max,$len,$gid,$lottery_number_id,$gtype,$randkey){
        $temp_num = trim($inputNumber);
        $temp_num_arr = explode(",", $temp_num);
        if(count($temp_num_arr)>$len){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp = array();
        foreach ($temp_num_arr as $k=>$v){
            if($v<$min OR $v>$max){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($v)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            array_push($temp,$v);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

    }

    /*
     *
     *针对单式进行号码判断
     *
     * */
    public static function _check_number_danshi($inputNumber,$len,$gid,$lottery_number_id,$gtype,$randkey){
        if(strpos($inputNumber,",")!==false){
            $numArray=explode(",",$inputNumber);
        }elseif(strpos($inputNumber,";")!==false){
            $numArray=explode(";",$inputNumber);
        }elseif(strpos($inputNumber," ")!==false){

            $numArray=explode(" ",$inputNumber);
        }
        //验证开始
        foreach($numArray as $key=>$value){
            $str_len = strlen($value);
            if($str_len!=$len){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = explode(" ",$value);
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
    }

    public static function _filter_two_row_ds($inputNumber,$gid,$lottery_number_id,$gtype,$randkey)
    {
        $wordArray = array("大","小","单","双");
        $buy_number_arr = explode(",", $inputNumber);
        if(count($buy_number_arr)!=2 && count($buy_number_arr)!=3){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法A！","key"=>$randkey));
            exit;
        }
        $num1 = $buy_number_arr[0];
        $num1_array = explode("", $num1);
        $temp1 = array();
        foreach($num1_array as $k=>$v){
            if(!in_array($v, $wordArray)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法B！","key"=>$randkey));
                exit;
            }
            array_push($temp1,$v);
        }
        $count1 = count($temp1);
        $temp_unique = array_unique($temp1);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法C！","key"=>$randkey));
            exit;
        }
        $num2 = $buy_number_arr[1];
        $num2_array = explode("", $num2);
        $temp2 = array();
        foreach($num2_array as $kk=>$vv){
            if(!in_array($vv, $wordArray)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法D！","key"=>$randkey));
                exit;
            }
            array_push($temp2,$vv);
        }
        $count11 = count($temp2);
        $temp_unique1 = array_unique($temp2);
        $count22 = count($temp_unique1);
        if($count11>$count22){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法E！","key"=>$randkey));
            exit;
        }
    }

    //过滤定位胆玩法
    public static function _filter_dingwei($inputNumber,$min,$max,$gid,$lottery_number_id,$gtype,$randkey){
        $buy_number_arr = explode(",", $inputNumber);
        if(count($buy_number_arr)>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp = array();
        foreach ($buy_number_arr as $k=>$v){
            $len = strlen($v);
            for ($i=0; $i<$len;$i++){
                if(!empty($v[$i])){
                    if($v[$i]<$min OR $v[$i]>$max){
                        self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                        echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                        exit;
                    }
                    if(!ctype_digit($v[$i])){
                        self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                        echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                        exit;
                    }
                    array_push($temp,$v[$i]);
                } else {
                    continue;
                }
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$inputNumber);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $temp = array();
        }
    }

    /*
     * 五星直选
     *
     * */
    public function wxzhxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        self::_check_number($unum,5,0,9,$gid,$lottery_number_id,$gtype,$randkey);
        //过滤非法号码
        $temp_num_arr=explode(",",$unum);

        //先计算有多少个数组单位。少与5个是错误的。
        $temp_count=count($temp_num_arr);
        if($temp_count<5 OR $temp_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $temp_is_digit=true;
        $temp_zhu=1;
        for($i=0;$i<5;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){

                $temp_is_digit=false;


            }
            if(empty($temp_num_arr[$i]) AND $temp_num_arr[$i]!=0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $temp_zhu*=strlen($temp_num_arr[$i]);

        }

        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res)){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    public function wxzx120_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        //如果所选号码长度小于5位，那是错误的
        if($temp_count<5 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=5; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-5); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //五星组选60
    public function wxzx60_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<3){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i=$counttwo; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=3; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($counttwo-3); $j>0; $j--) {
            $n *= $j;
        }
        $dh = $c/($n*$m);

        for ($i=($counttwo-1); $i>0; $i--) {
            $cc *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $mm *= $k;
        }
        for ($j=($counttwo-3); $j>0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc/($nn*$mm);

        $temp_zhu = $countone*$dh-$repeatcount*$dhdh;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //五星组选30
    public function wxzx30_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<2 OR $counttwo<1){
            echo json_encode(array('info'=>'数据格式不正确！'));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i=$countone; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($countone-2); $j>0; $j--) {
            $n *= $j;
        }
        $dh = $c/($n*$m);

        for ($i=($countone-1); $i>0; $i--) {
            $cc *= $i;
        }
        for ($k=1; $k>0; $k--) {
            $mm *= $k;
        }
        for ($j=($countone-2); $j>0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc/($nn*$mm);

        $temp_zhu = $counttwo*$dh-$repeatcount*$dhdh;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //五星组选20
    public function wxzx20_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i=$counttwo; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($counttwo-2); $j>0; $j--) {
            $n *= $j;
        }
        $dh = $c/($n*$m);

        for ($i=($counttwo-1); $i>0; $i--) {
            $cc *= $i;
        }
        for ($k=1; $k>0; $k--) {
            $mm *= $k;
        }
        for ($j=($counttwo-2); $j>0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc/($nn*$mm);

        $temp_zhu = $countone*$dh-$repeatcount*$dhdh;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //五星组选10
    public function wxzx10_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone*$counttwo-$repeatcount;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //五星组选5
    public function wxzx5_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone*$counttwo-$repeatcount;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    public function special(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        //如果所选号码长度小于1位，那是错误的
        if($temp_count<1 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_zhu = $temp_count;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }


    /*
     * 五星直选单式
    *
    */

    public function wxzhxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        self::_check_number_danshi($unum,5,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        }else{
            if(ctype_digit(``)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{
                $temp_num_arr[0]=$unum;
            }
        }
        if(count($temp_num_arr)!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    /*
      *
      * 前四直选复式
      * 后四直选复式:2,13,123,2:6:1:12
      */

    public function sxzhxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        //过滤非法号码
        self::_check_number($unum,4,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);

        //先计算有多少个数组单位。少与4个是错误的。
        $temp_count=count($temp_num_arr);
        if($temp_count<4 OR $temp_count>4){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $temp_is_digit=true;
        $temp_zhu=1;
        for($i=0;$i<4;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(empty($temp_num_arr[$i]) AND $temp_num_arr[$i]!=0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $temp_zhu*=strlen($temp_num_arr[$i]);

        }

        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    /*
     * 前四直选单式
     * 后四直选单式
     */

    public function sxzhxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_check_number_danshi($unum,4,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        }else{
            if(ctype_digit($unum)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{
                $temp_num_arr[0]=$unum;
            }
        }
        if(count($temp_num_arr)!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //四星组选24
    public function wxzx24_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {

        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<4 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=4; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-4); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //四星组选12
    public function wxzx12_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i=$counttwo; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($counttwo-2); $j>0; $j--) {
            $n *= $j;
        }
        $dh = $c/($n*$m);

        for ($i=($counttwo-1); $i>0; $i--) {
            $cc *= $i;
        }
        for ($k=1; $k>0; $k--) {
            $mm *= $k;
        }
        for ($j=($counttwo-2); $j>0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc/($nn*$mm);

        $temp_zhu = $countone*$dh-$repeatcount*$dhdh;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //四星组选6
    public function wxzx6_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<2 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-2); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //四星组选4
    public function wxzx4_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone*$counttwo-$repeatcount;
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    /*
      * 前三直选复式
      * 中三直选复式
     * 后三直选复式
     * 直选复式 F3D
      */
    public function shxzhxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        self::_check_number($unum,3,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);

        //先计算有多少个数组单位。少3个是错误的。
        $temp_count=count($temp_num_arr);
        if($temp_count<3 OR $temp_count>3){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }

        $temp_is_digit=true;
        $temp_zhu=1;
        for($i=0;$i<3;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){

                $temp_is_digit=false;


            }
            if(empty($temp_num_arr[$i]) AND $temp_num_arr[$i]!=0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $temp_zhu*=strlen($temp_num_arr[$i]);

        }

        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    /*
     * 前三直选单式
     * 中三直选单式
     * 后三直选单式
     */
    public function shxzhxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_check_number_danshi($unum,3,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        }else{
            if(ctype_digit($unum)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法A！","key"=>$randkey));
                exit;
            }else{
                $temp_num_arr[0]=$unum;
            }
        }
        if(count($temp_num_arr)!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法B！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    /*
     *
     * 前三组六
     * 中三组六
     * 后三组六
     */

    public function sxzl_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        $temp = array();
        for($i=0;$i<$temp_count;$i++){
            if($temp_num[$i]<0 OR $temp_num[$i]>9){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($temp_num[$i])){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            array_push($temp,$temp_num[$i]);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_count<3){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if(ctype_digit($temp_num)==false){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $temp_num_array=array();
        $temp_num_arr=array();
        $is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            $temp_num_array[]=$temp_num[$i];
            if(ctype_digit($temp_num[$i])==false){
                $is_digit=false;
            }
        }
        if($is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        self::zuhe1(3,$temp_num_array,$temp_num_arr);
        //$temp_count1=count($temp_num_arr);
        if(count($temp_num_arr)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=$temp_num;
    }

    /*
     *
     * 前三组三
     *
     *
     */
    public function sxzs_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if(ctype_digit($temp_num)==false){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $temp_num_array=array();
        $temp_num_arr=array();
        $is_digit=true;
        for($i=0;$i<$temp_count;$i++){

            $temp_num_array[]=$temp_num[$i];
            if(ctype_digit($temp_num[$i])==false){
                $is_digit=false;
            }
        }
        if($is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_count*($temp_count-1)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=$temp_num;
    }

    /*
     *
     * 前三直选和值
     *
     */
    public function sxzhxhzh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $is_digit=true;
        $total_zhu=0;
        foreach($temp_num_arr as $val){
            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }
            for($i=0;$i<=9;$i++){
                for($l=0;$l<=9;$l++){
                    for($loop=0;$loop<=9;$loop++){

                        if(($i+$l+$loop)==$val){

                            $total_zhu++;
                        }

                    }

                }

            }

        }


        if($total_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    /*
     * 前三混合组选
     * 中三混合组选
     * 后三混合组选
     *
     */
    public function sxhhzx_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        $unum=trim($unum);
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach($temp_num_arr as $key=>$value){
            $str_len = strlen($value);
            if($str_len!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = array($value[0],$value[1],$value[2]);
            $arr_unique = array_unique($arr);
            if(count($arr_unique)==1){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }

        $temp_count=count($temp_num_arr);

        if($temp_count!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(',',$temp_num_arr);

    }

    /*
     *
     * 前三组六胆拖
     * 后三组六胆拖
     *
     */

    public function zldt_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        $temp_num_arr=explode(",",$unum);
        if(count($temp_num_arr)!=2){
            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $_s = $temp_num_arr[0];
        $_len = strlen($_s);
        if($_len<1 || $_len>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $a1 = explode(" ",$temp_num_arr[0]);
        $a2 = explode(" ",$temp_num_arr[1]);
        $_arr = array_intersect($a1,$a2);
        if(count($_arr)!=0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $len = strlen($v);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($v[$i]<0 OR $v[$i]>9){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                if(!ctype_digit($v[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                array_push($temp,$v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }


        $strlen=strlen($temp_num_arr[0]);
        //先计算有多少个数组单位。少2个是错误的。
        $temp_count=count($temp_num_arr);
        if($temp_count<2 OR $temp_count>2){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if($strlen>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $temp_is_digit=true;
        $temp_zhu=1;
        for($i=0;$i<2;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){

                $temp_is_digit=false;


            }

            if(empty($temp_num_arr[$i]) AND $temp_num_arr[$i]!=0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }


        }

        if($strlen==2){

            $temp_zhu=strlen(trim($temp_num_arr[1]));
            if($temp_zhu<1){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }elseif($strlen==1){

            $sec_len=strlen(trim($temp_num_arr[1]));
            if($sec_len<2){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }


            $den=$sec_len*($sec_len-1);

            $ele=1;
            for($i=2;$i>=1;$i--){

                $ele*=$i;
            }
            $temp_zhu=$den/$ele;

        }

        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);

    }

    /*
     *
     * 前二直选复式
     *
     */

    public function erxzhxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);

        //先计算有多少个数组单位。少2个是错误的。
        $temp_count=count($temp_num_arr);
        if($temp_count<2 OR $temp_count>2){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }

        $temp_is_digit=true;
        $temp_zhu=1;
        for($i=0;$i<2;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){

                $temp_is_digit=false;


            }
            if(empty($temp_num_arr[$i]) AND $temp_num_arr[$i]!=0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $temp_zhu*=strlen($temp_num_arr[$i]);

        }

        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);

    }

    /*
     * 前二直选单式
    *
    *
    */
    public function erxzhxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_check_number_danshi($unum,2,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        }else{
            if(ctype_digit($unum)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{
                $temp_num_arr[0]=$unum;
            }
        }
        if(count($temp_num_arr)!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);


    }

    /*
     *
    * 前二直选和值
    *_filter_hz($inputNumber,$min,$max,$len,$gid,$lottery_number_id,$gtype)
    */
    public function erxzhxhzh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        //过滤非法号码
        self::_filter_hz($unum,0,18,19,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $is_digit=true;
        $total_zhu=0;
        foreach($temp_num_arr as $val){
            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            for($i=0;$i<=9;$i++){
                for($l=0;$l<=9;$l++){
                    if(($i+$l)==$val){
                        $total_zhu++;
                    }
                }

            }

        }


        if($total_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);


    }

    /*
     * 前二组选复式
     *
     */
    public function erxzxfsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);

        if($temp_count<2 || $temp_count>7){
            echo json_encode(array("status"=>0,"info"=>"本玩法请选择2-7个号码进行投注！","key"=>$randkey));
            exit;

        }

        if($temp_count<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if(ctype_digit($temp_num)==false){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $temp_num_array=array();
        $temp_num_arr=array();
        $is_digit=true;
        for($i=0;$i<$temp_count;$i++){

            $temp_num_array[]=$temp_num[$i];
            if(ctype_digit($temp_num[$i])==false){
                $is_digit=false;
            }
        }
        if($is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        self::zuhe1(2,$temp_num_array,$temp_num_arr);
        //$temp_count1=count($temp_num_arr);
        if(count($temp_num_arr)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=$temp_num;


    }

    /*
     * 前二组选单式
    *
    */
    public function erxzxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        if(strpos($unum,",")!==false){
            $numArray=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $numArray=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $numArray=explode(" ",$unum);
        }
        //验证开始
        foreach($numArray as $key=>$value){
            $_a = explode("",$value);
            $_a_1 = array_unique($_a);
            if(count($_a_1)==1){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $str_len = strlen($value);
            if($str_len!=2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = explode(" ",$value);
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }

        $unum=trim($unum);
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        }else{
            if(ctype_digit($unum)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{
                $temp_num_arr[0]=$unum;
            }
        }
        if(count($temp_num_arr)!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach($temp_num_arr AS $val){

            if(strlen($val)>2 OR strlen($val)<2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }
            if($val[0]==$val[1]){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }
            if(ctype_digit($val)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(empty($val) and $val!='00'){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);


    }

    /*
        前二后二组选胆拖
    */

    public function qherzxdt_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        //过滤非法号码
        $temp_num_arr=explode(",",$unum);
        if(count($temp_num_arr)!=2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(strlen($temp_num_arr[0])!=1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $a1 = explode(" ",$temp_num_arr[0]);
        $a2 = explode(" ",$temp_num_arr[1]);
        $_arr = array_intersect($a1,$a2);
        if(count($_arr)!=0){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $len = strlen($v);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($v[$i]<0 OR $v[$i]>9){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                if(!ctype_digit($v[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                array_push($temp,$v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }

        $temp_num_arr=explode(",",$unum);
        $strlen=strlen($temp_num_arr[0]);
        //先计算有多少个数组单位。少2个是错误的。
        $temp_count=count($temp_num_arr);
        if($temp_count<2 OR $temp_count>2){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if($strlen>1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $temp_is_digit=true;
        $temp_zhu=1;
        for($i=0;$i<2;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){

                $temp_is_digit=false;


            }

            if(empty($temp_num_arr[$i]) AND $temp_num_arr[$i]!=0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }


        }



        $sec_len=strlen(trim($temp_num_arr[1]));




        $temp_zhu=$sec_len;



        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);


    }

    /*
     *
    * 前二组选和值
    *
    */
    public function erxzxhzh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        self::_filter_hz($unum,1,17,17,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $is_digit=true;
        $total_zhu=0;
        $temp_array=array();
        $temp_val=array();
        foreach($temp_num_arr as $val){

            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }

            for($i=0;$i<=9;$i++){

                for($l=0;$l<=9;$l++){
                    if(($i+$l)==$val){
                        if($i==$l){
                            continue;
                        }else{
                            $temp_array=array($i,$l);

                            sort($temp_array);

                            $temp_str=implode("",$temp_array);

                            if(in_array($temp_str,$temp_val)){
                                continue;
                            }else{
                                $temp_val[]=$temp_str;
                            }

                            $total_zhu++;
                        }
                    }else{
                        continue;
                    }
                }

            }

        }


        if($total_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);


    }

    /*
     * 大小单双
     *          case '前三大小单双':
            case '后三大小单双':
            case '前二大小单双':
            case '后二大小单双':_filter_two_row_ds($inputNumber,$gid,$lottery_number_id,$gtype)
     */
    public function dxdsh_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_filter_two_row_ds($unum,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_unum=trim($unum);
        $temp_unum_arr=explode(",",$temp_unum);
        $temp_count=count($temp_unum_arr);

        if($temp_count==2){

            if(strpos($gtype,"二")===false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }

        }elseif($temp_count==3){

            if(strpos($gtype,"三")===false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }

        }

        $temp_zhu=1;
        for($i=0;$i<$temp_count;$i++){

            $temp_zhu*=mb_strlen($temp_unum_arr[$i],"utf-8");

        }

        if($temp_zhu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_unum_arr);
    }

    /*
     *
     * 定位胆_filter_dingwei($inputNumber,$min,$max,$gid,$lottery_number_id,$gtype)
     *
     */
    public function dwd_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_filter_dingwei($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $user_num_arr=explode(",",trim($unum));
        $mark=false;
        $total_ele=0;
        $len_arr=count($user_num_arr);
        for($i=0;$i<$len_arr;$i++){
            $user_num_arr[$i]=trim($user_num_arr[$i]);
            $str_len=strlen($user_num_arr[$i]);
            if($str_len>8){
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit();
            }
            $total_ele+=$str_len;
        }

        if($total_ele!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$user_num_arr);

    }

    /*
     * 一码不定位
     *
     *
     */
    public function ymbdw_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num_arr=trim($unum);
        $temp_count=strlen($temp_num_arr);
        if($temp_count!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$temp_count;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=$temp_num_arr;

    }

    /*
     * 二码不定位
    *
    *
    */
    public function ermbdw_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num_arr=trim($unum);
        $temp_count=strlen($temp_num_arr);
        $temp_total=1;
        for($i=$temp_count;$i>($temp_count-2);$i--){

            $temp_total*=$i;

        }


        //self::zuhe1(2,$temp_num_arr,&$temp_return_arr);
        $temp_count1=$temp_total/2;
        if($temp_count1!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$temp_count;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=$temp_num_arr;

    }

    /*
     * 三码不定位
    *
    *
    */
    public function smbdw_zhu(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num_arr=trim($unum);
        $temp_count=strlen($temp_num_arr);
        $temp = array();
        for($i=0;$i<$temp_count;$i++){
            array_push($temp,$temp_num_arr[$i]);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_total=1;
        for($i=$temp_count;$i>($temp_count-3);$i--){

            $temp_total*=$i;

        }


        //self::zuhe1(2,$temp_num_arr,&$temp_return_arr);
        $temp_count1=$temp_total/6;
        if($temp_count1!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$temp_count;$i++){

            if(ctype_digit($temp_num_arr[$i])==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=$temp_num_arr;

    }

    //任二直选复式
    public function rener_zxfs(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        $temp_num_arr=explode(",",$unum);
        if(count($temp_num_arr)<2 || count($temp_num_arr)>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $len = strlen($v);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($v[$i]<0 OR $v[$i]>9){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                if(!ctype_digit($v[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                array_push($temp,$v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }

        $temp_num_arr=explode(",",$unum);
        $count5 = $count4 = $count3 = $count2 = $count1 = 0;

        $count5 = ($temp_num_arr[0]=="") ? 0 : strlen($temp_num_arr[0]);
        $count4 = ($temp_num_arr[1]=="") ? 0 : strlen($temp_num_arr[1]);
        $count3 = ($temp_num_arr[2]=="") ? 0 : strlen($temp_num_arr[2]);
        $count2 = ($temp_num_arr[3]=="") ? 0 : strlen($temp_num_arr[3]);
        $count1 = ($temp_num_arr[4]=="") ? 0 : strlen($temp_num_arr[4]);

        $temp_count=count($temp_num_arr);
        if($temp_count<2 OR $temp_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false && !empty($temp_num_arr[$i])){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $count5*($count4+$count3+$count2+$count1)+$count4*($count3+$count2+$count1)+$count3*($count2+$count1)+$count2*$count1;

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任二直选单式_check_number_danshi($inputNumber,$len,$gid,$lottery_number_id,$gtype)
    public function rener_zxds(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        self::_check_number_danshi($unum,2,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(strlen($temp_num_arr[$i])!=2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<2 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)/2);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }
    //任二直选和值
    public function rener_zxhz(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_filter_hz($unum,0,18,19,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<2 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //任选二直选和值配置
        $zuconfig = array(0=>1,1=>2,2=>3,3=>4,4=>5,5=>6,6=>7,7=>8,8=>9,9=>10,10=>9,11=>8,12=>7,13=>6,14=>5,15=>4,16=>3,17=>2,18=>1);
        $temp_zhu=0;
        foreach ($temp_num_arr as $key=>$value){
            $temp_zhu+=$zuconfig[$value];
        }
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)/2);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任二组选复式
    public function rener_zhuxuanfs(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<2 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            echo json_encode(array('info'=>'数据必须为数字！'));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<2 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-2); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)/2);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //任二组选单式
    public function rener_zhuxuands(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach($temp_num_arr as $key=>$value){
            $_a = array($value[0],$value[1]);
            $_a_1 = array_unique($_a);
            if(count($_a_1)==1){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $str_len = count($_a);
            if($str_len!=2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = explode(" ",$value);
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            $_aa = array($temp_num_arr[$i][0],$temp_num_arr[$i][1]);
            if(count($_aa)!=2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<2 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)/2);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任二组选和值
    public function rener_zhuxuanhz(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_filter_hz($unum,1,17,17,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<2 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //任选二直选和值配置
        $zuconfig = array(1=>1,2=>1,3=>2,4=>2,5=>3,6=>3,7=>4,8=>4,9=>5,10=>4,11=>4,12=>3,13=>3,14=>2,15=>2,16=>1,17=>1);
        $temp_zhu=0;
        foreach ($temp_num_arr as $key=>$value){
            $temp_zhu+=$zuconfig[$value];
        }
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)/2);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任三直选复式
    public function rensan_zhixuanfs(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        $temp_num_arr=explode(",",$unum);
        if(count($temp_num_arr)<3 || count($temp_num_arr)>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $len = strlen($v);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($v[$i]<0 OR $v[$i]>9){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                if(!ctype_digit($v[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                array_push($temp,$v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }

        $temp_num_arr=explode(",",$unum);
        $a = $b = $c = $d = $e = 0;

        $a = ($temp_num_arr[0]=="") ? 0 : strlen($temp_num_arr[0]);
        $b = ($temp_num_arr[1]=="") ? 0 : strlen($temp_num_arr[1]);
        $c = ($temp_num_arr[2]=="") ? 0 : strlen($temp_num_arr[2]);
        $d = ($temp_num_arr[3]=="") ? 0 : strlen($temp_num_arr[3]);
        $e = ($temp_num_arr[4]=="") ? 0 : strlen($temp_num_arr[4]);

        $temp_count=count($temp_num_arr);
        if($temp_count<3 OR $temp_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false && !empty($temp_num_arr[$i])){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $a*($b*$c+$b*$d+$b*$e+$c*$d+$c*$e+$d*$e)+$b*($c*$d+$c*$e+$d*$e)+$c*$d*$e;

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任三直选单式
    public function rensan_zhixuands(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        self::_check_number_danshi($unum,3,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(strlen($temp_num_arr[$i])!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选三直选和值
    public function rensan_zhixuanhz(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_filter_hz($unum,0,27,28,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //任选三直选和值配置
        $zuconfig = array(0=>1,1=>3,2=>6,3=>10,4=>15,5=>21,6=>28,7=>36,8=>45,9=>55,10=>63,11=>69,12=>73,13=>75,14=>75,15=>73,16=>69,17=>63,18=>55,19=>45,20=>36,21=>28,22=>21,23=>15,24=>10,25=>6,26=>3,27=>1);
        $temp_zhu=0;
        foreach ($temp_num_arr as $key=>$value){
            $temp_zhu+=$zuconfig[$value];
        }
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选三 组三复式
    public function rensan_zhusanfs(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<2 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_zhu = $temp_count*($temp_count-1);
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //任选 任选三组三单式
    public function rensan_zhusands(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach($temp_num_arr as $key=>$value){
            $_a = array($value[0],$value[1],$value[2]);
            $_a_1 = array_unique($_a);
            if(count($_a_1)==1){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(count($_a_1)==3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $str_len = strlen($value);
            if($str_len!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = explode(" ",$value);
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(strlen($temp_num_arr[$i])!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选三组六复式
    public function rensan_zhuliufs(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<3 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=3; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-3); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //任选 任选三组六单式
    public function rensan_zhuliuds(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach($temp_num_arr as $key=>$value){
            $_a = array($value[0],$value[1],$value[2]);
            $_a_1 = array_unique($_a);
            if(count($_a_1)!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $str_len = strlen($value);
            if($str_len!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = explode(" ",$value);
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(strlen($temp_num_arr[$i])!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选三混合组选
    public function rensan_hunhezx(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        if(strpos($unum,",")!==false){
            $temp_num_arr=explode(",",$unum);
        }elseif(strpos($unum,";")!==false){
            $temp_num_arr=explode(";",$unum);
        }elseif(strpos($unum," ")!==false){

            $temp_num_arr=explode(" ",$unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach($temp_num_arr as $key=>$value){
            $_a = array($value[0],$value[1],$value[2]);
            $_a_1 = array_unique($_a);
            if($value[0]==$value[1] && $value[0]==$value[2] && $value[1]==$value[2]){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $str_len = strlen($value);
            if($str_len!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            $arr = explode(" ",$value);
            $max = array_search(max($arr),$arr);
            $min = array_search(min($arr),$arr);
            if($max>9 || $min<0){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($value)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(strlen($temp_num_arr[$i])!=3){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选三组选和值
    public function rensan_zhuxuanhezhi(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_filter_hz($unum,1,26,26,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<3 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //任选三组选和值配置
        $zuconfig = array(1=>1,2=>2,3=>2,4=>4,5=>5,6=>6,7=>8,8=>10,9=>11,10=>13,11=>14,12=>14,13=>15,14=>15,15=>14,16=>14,17=>13,18=>11,19=>10,20=>8,21=>6,22=>5,23=>4,24=>2,25=>2,26=>1);
        $temp_zhu=0;
        foreach ($temp_num_arr as $key=>$value){
            $temp_zhu+=$zuconfig[$value];
        }
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)/6);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选四 直选复式
    public function rensi_zhixuanfs(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        //过滤非法号码
        $temp_num_arr=explode(",",$unum);
        if(count($temp_num_arr)<4 || count($temp_num_arr)>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        foreach ($temp_num_arr as $k=>$v){
            $len = strlen($v);
            $temp = array();
            for($i=0;$i<$len;$i++){
                if($v[$i]<0 OR $v[$i]>9){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                if(!ctype_digit($v[$i])){
                    self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                    exit;
                }
                array_push($temp,$v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if($count1>$count2){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        $a = $b = $c = $d = $e = 0;

        $a = ($temp_num_arr[0]=="") ? 0 : strlen($temp_num_arr[0]);
        $b = ($temp_num_arr[1]=="") ? 0 : strlen($temp_num_arr[1]);
        $c = ($temp_num_arr[2]=="") ? 0 : strlen($temp_num_arr[2]);
        $d = ($temp_num_arr[3]=="") ? 0 : strlen($temp_num_arr[3]);
        $e = ($temp_num_arr[4]=="") ? 0 : strlen($temp_num_arr[4]);

        $temp_count=count($temp_num_arr);
        if($temp_count<4 OR $temp_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false && !empty($temp_num_arr[$i])){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $a*$b*$c*$d+$a*$b*$c*$e+$a*$b*$d*$e+$a*$c*$d*$e+$b*$c*$d*$e;

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选四直选单式
    public function rensi_zhixuands(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        self::_check_number_danshi($unum,4,$gid,$lottery_number_id,$gtype,$randkey);
        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        if($temp_count<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<$temp_count;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
            if(strlen($temp_num_arr[$i])!=4){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<4 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)*($temp_position_count-3)/24);

        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选四组选24
    public function rensi_zhixuan24(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<4 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<4 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=4; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-4); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)*($temp_position_count-3)/24);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //任选 任选四组选12
    public function rensi_zhixuan12(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<4 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c=$m=$n=$cc=$mm=$nn=1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i=$counttwo; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($counttwo-2); $j>0; $j--) {
            $n *= $j;
        }
        $dh = $c/($n*$m);

        for ($i=($counttwo-1); $i>0; $i--) {
            $cc *= $i;
        }
        for ($k=1; $k>0; $k--) {
            $mm *= $k;
        }
        for ($j=($counttwo-2); $j>0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc/($nn*$mm);

        $temp_zhu = $countone*$dh-$repeatcount*$dhdh;
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)*($temp_position_count-3)/24);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    //任选 任选四组选6
    public function rensi_zhixuan6(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number_2($unum,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num=trim($unum);
        $temp_count=strlen($temp_num);
        if($temp_count<2 OR $temp_count>10){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(ctype_digit($temp_num)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<4 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i=$temp_count; $i>0; $i--) {
            $c *= $i;
        }
        for ($k=2; $k>0; $k--) {
            $m *= $k;
        }
        for ($j=($temp_count-2); $j>0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c/($n*$m);
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)*($temp_position_count-3)/24);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum = $temp_num;
    }

    //任选 任选四组选4
    public function rensi_zhixuan4(&$unum,$sub_zhu,$position,$gtype,$gid,$lottery_number_id,$randkey)
    {
        //过滤非法号码
        self::_check_number($unum,2,0,9,$gid,$lottery_number_id,$gtype,$randkey);

        $temp_num_arr=explode(",",$unum);
        $temp_count=count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if($temp_count<2 OR $temp_count>2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_is_digit=true;
        for($i=0;$i<2;$i++){
            if(ctype_digit($temp_num_arr[$i])==false){
                $temp_is_digit=false;
            }
        }
        if($temp_is_digit==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //位置
        $temp_position_arr = explode("、",$position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if($temp_position_count!=count($temp_position_arr_unicke)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"错误","key"=>$randkey));
            exit;
        }
        if($temp_position_count<4 OR $temp_position_count>5){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if($countone<1 OR $counttwo<1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        for($i=0;$i<$countone;$i++){
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for($i=0;$i<$counttwo;$i++){
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value){
            if(in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone*$counttwo-$repeatcount;
        $temp_zhu = $temp_zhu*($temp_position_count*($temp_position_count-1)*($temp_position_count-2)*($temp_position_count-3)/24);
        if($temp_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$temp_num_arr);
    }

    public function ks_hezhi(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_filter_hz($unum,3,18,16,$gid,$lottery_number_id,$gtype,$randkey);

        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $tempcomp = array();
        foreach($temp_num_arr as $vv){
            if($vv<3 OR $vv>18){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            if(!ctype_digit($vv)){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }
            array_push($tempcomp,$vv);
        }
        $count1 = count($tempcomp);
        $temp_unique = array_unique($tempcomp);
        $count2 = count($temp_unique);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $is_digit=true;
        $new_arr=array();
        $total_zhu=0;
        foreach($temp_num_arr as $val){
            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }
            $total_zhu++;
            $new_arr[]=trim($val);
        }


        if($total_zhu!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$new_arr);


    }

    public function ks_sthtx(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        $wordArray = array("三同号通选");
        $temp_num_arr = trim($unum);
        if(!in_array($temp_num_arr, $wordArray)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $unum=trim($unum);

        if($unum!='三同号通选'){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if($sub_zhu!=1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum='1';

    }

    public function ks_sthdx(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $_a = array_unique($temp_num_arr);
        if(count($temp_num_arr)>count($_a)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_arr=array(111,222,333,444,555,666);
        $new_arr=array();
        foreach($temp_num_arr AS $val){
            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }
            if(in_array(trim($val),$temp_arr)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{

                $new_arr[]=trim($val);

            }

        }
        $count1 = count($temp_num_arr);
        $arrayUnike = array_unique($new_arr);
        $count2 = count($arrayUnike);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(count($new_arr)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$new_arr);
    }

    public function ks_erthfx(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){


        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $_a = array_unique($temp_num_arr);
        if(count($temp_num_arr)>count($_a)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $temp_arr=array("11*","22*","33*","44*","55*","66*");
        $new_arr=array();
        foreach($temp_num_arr AS $val){

            if(in_array(trim($val),$temp_arr)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{

                $new_arr[]=trim($val);

            }

        }

        if(count($new_arr)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$new_arr);
    }

    public function ks_erthdx(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){


        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $num1 = $temp_num_arr[0];
        $num1 = trim($num1);
        $num2 = $temp_num_arr[1];
        $num2 = trim($num2);
        $temp_arr = array();
        foreach($num1 as $k=>$v){
            $temp_arr[] = substr($v,0,1);
        }
        $_a_1 = array_intersect($temp_arr,$num2);
        if(count($_a_1)>0){
            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法1！","key"=>$randkey));
            exit;
        }

        $num1_array = explode(" ", $num1);
        $temp1 = array();
        foreach ($num1_array as $k=>$v){
            if(!in_array($v, array("11","22","33","44","55","66"))){
                //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法2！","key"=>$randkey));
                exit;
            }
            array_push($temp1, $v);
        }
        $count1 = count($temp1);
        $tempArray = array_unique($temp1);
        $count2 = count($tempArray);
        if($count1>$count2){
            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法3！","key"=>$randkey));
            exit;
        }

        $num2_array = explode("", $num2);
        $temp2 = array();
        foreach ($num2_array as $kk=>$vv){
            if(!in_array($vv, array("1","2","3","4","5","6"))){
                //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法4！","key"=>$randkey));
                exit;
            }
            array_push($temp2, $vv);
        }
        $count11 = count($temp2);
        $tempArray1 = array_unique($temp2);
        $count22 = count($tempArray1);
        if($count11>$count22){
            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法5！","key"=>$randkey));
            exit;
        }




        $new_arr=array();

        if(count($temp_num_arr)!=2){
            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法6！","key"=>$randkey));
            exit;

        }
        $first_arr=explode(" ",trim($temp_num_arr[0]));
        $temp_var="";
        $first_arr_len=count($first_arr);

        $temp_var1=trim($temp_num_arr[1]);
        $strlen=strlen($temp_var1);
        if(ctype_digit($temp_var1)==false){
            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法7！","key"=>$randkey));
            exit;

        }
        foreach($first_arr AS $val){

            $temp_var=trim($val);
            if(ctype_digit($temp_var)==false){
                //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法8！","key"=>$randkey));
                exit;

            }
            if(strlen($temp_var)!=2){

                //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法9！","key"=>$randkey));
                exit;
            }
            if($temp_var[0]!=$temp_var[1]){
                //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法10！","key"=>$randkey));
                exit;

            }
            for($j=0;$j<$strlen;$j++){

                if($temp_var[0]==$temp_var1[$j]){
                    //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                    echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法11！","key"=>$randkey));
                    exit;

                }


            }

            $new_arr[]=$temp_var;

        }
        if(($first_arr_len*$strlen)!=$sub_zhu){

            //self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法12！","key"=>$randkey));
            exit;

        }

        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $temp_unum1=implode(" ",$new_arr);
        $unum=$temp_unum1.",".$temp_var1;


    }

    public function ks_ybth(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_check_number_2($unum,1,6,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        if(strlen($unum)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if(ctype_digit($unum)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }

    }

    public function ks_erbth(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_check_number_2($unum,1,6,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        $len=strlen($unum);

        if(ctype_digit($unum)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }

        if($len<2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $zhushu=0;
        switch($len){
            case 2:
                $zhushu=1;
                break;
            case 3:
                $zhushu=3;
                break;
            case 4:
                $zhushu=6;
                break;
            case 5:
                $zhushu=10;
                break;
            case 6:
                $zhushu=15;
                break;
        }
        if($zhushu==0 OR $zhushu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }

    }

    public function ks_sanbth(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){

        self::_check_number_2($unum,1,6,$gid,$lottery_number_id,$gtype,$randkey);
        $unum=trim($unum);
        $len=strlen($unum);

        if(ctype_digit($unum)==false){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }

        if($len<3){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $zhushu=0;
        switch($len){

            case 3:
                $zhushu=1;
                break;
            case 4:
                $zhushu=4;
                break;
            case 5:
                $zhushu=10;
                break;
            case 6:
                $zhushu=20;
                break;
        }
        if($zhushu==0 OR $zhushu!=$sub_zhu){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }

        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }

    }

    public function ks_slhtx(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){
        $wordArray = array("三连号通选");
        $temp_num_arr = trim($unum);
        if(!in_array($temp_num_arr, $wordArray)){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        $unum=trim($unum);

        if($unum!='三连号通选'){

            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        if($sub_zhu!=1){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum='3';

    }

    public function ks_slhdx(&$unum,$sub_zhu,$gtype,$gid,$lottery_number_id,$randkey){


        $unum=trim($unum);
        $temp_num_arr=explode(",",$unum);
        $temp_arr=array(123,234,345,456);
        $new_arr=array();
        foreach($temp_num_arr AS $val){
            if(ctype_digit($val)==false){
                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;

            }
            if(in_array(trim($val),$temp_arr)==false){

                self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
                echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
                exit;
            }else{

                $new_arr[]=trim($val);

            }

        }
        $count1 = count($temp_num_arr);
        $arrayUnike = array_unique($new_arr);
        $count2 = count($new_arr);
        if($count1>$count2){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;
        }
        if(count($new_arr)!=$sub_zhu){
            self::buy_log($gid,$lottery_number_id,$gtype,"试图提交异常号码:".$unum."注数:".$sub_zhu);
            echo json_encode(array("status"=>0,"info"=>"发布合买注数不合法！","key"=>$randkey));
            exit;

        }
        $check_zhushu_res = M("plan_zhushu_set")->where(array("playway"=>$gtype,"status"=>1,"lottery_id"=>$gid))->find();$activegx = M("active_bdsdgx")->where(array("userid"=>session("SESSION_ID")))->find();
        if(!empty($check_zhushu_res) ){
            if($sub_zhu>$check_zhushu_res["zhushu"]){
                echo json_encode(array("status"=>0,"info"=>"本玩法的最大投注注数为:".$check_zhushu_res["zhushu"]."注","key"=>$randkey));
                exit;
            }
        }
        $unum=implode(",",$new_arr);


    }


    public function zuhe1($n,$arr,&$return,$tmp_array = array()){
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
                self::zuhe1($m, $arr2, $return,$tmp);
            }
        }
    }
}
