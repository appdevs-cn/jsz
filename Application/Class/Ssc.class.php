<?php

/**
 * Created by PhpStorm.
 * User: jason
 * Date: 15/1/1
 * Time: 下午4:18
 */
class Ssc
{
    /**
     * @param $unum <号码>
     * @param $sub_zhu <注数>
     * @param $gid <彩票ID>
     * @param $gtype <玩法>
     * @param $position <位置>
     * @param $lottery_number_id <期号ID>
     * @param $randkey <随机参数>
     */
    public function checknumformat(&$unum, $sub_zhu, $gid, $gtype, $position, $lottery_number_id)
    {
        switch ($gid) {
            case 1:
            case 2:
            case 3:
            case 4:
            case 17:
            case 18:
            case 19:
            case 30:
            case 31:
            case 32:
            case 33:
                $gid = 1;
                break;
            case 11:
            case 12:
                $gid = 11;
                break;
            case 15:
            case 16:
            case 22:
                $gid = 15;
                break;
        }
        switch ($gtype) {

            case '五星直选复式':
                self::wxzhxfsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选120':
                self::wxzx120_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选60':
                self::wxzx60_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选30':
                self::wxzx30_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选20':
                self::wxzx20_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选10':
                self::wxzx10_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选5':
                self::wxzx5_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '一帆风顺':
            case '好事成双':
            case '三星报喜':
            case '四季发财':
                self::special($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '五星直选单式':
                $unum = trim($unum);
                self::wxzhxdsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前四直选复式':
            case '后四直选复式':
                self::sxzhxfsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前四直选单式':
            case '后四直选单式':
                $unum = trim($unum);
                self::sxzhxdsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选24':
                self::wxzx24_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选12':
                self::wxzx12_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选6':
                self::wxzx6_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '组选4':
                self::wxzx4_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三直选复式':
            case '中三直选复式':
            case '后三直选复式':
            case '直选复式':
                self::shxzhxfsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三直选单式':
            case '中三直选单式':
            case '后三直选单式':
            case '直选单式':
                $unum = trim($unum);
                self::shxzhxdsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三组六':
            case '中三组六':
            case '后三组六':
            case '组六':
                self::sxzl_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三组三':
            case '中三组三':
            case '后三组三':
            case '组三':
                self::sxzs_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            //case '前三直选和值':
            //case '后三直选和值':
            case '直选和值':
                self::sxzhxhzh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
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
                self::sxhhzx_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;

            case '前二直选复式':
            case '后二直选复式':

                self::erxzhxfsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三组六胆拖':
            case '中三组六胆拖':
            case '后三组六胆拖':

                self::zldt_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前二直选单式':
            case '后二直选单式':
                $unum = trim($unum);
                self::erxzhxdsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前二直选和值':
            case '后二直选和值':

                self::erxzhxhzh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前二组选复式':
            case '后二组选复式':

                self::erxzxfsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前二组选胆拖':
            case '后二组选胆拖':

                self::qherzxdt_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前二组选单式':
            case '后二组选单式':
                $unum = trim($unum);
                self::erxzxdsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前二组选和值':
            case '后二组选和值':

                self::erxzxhzh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三大小单双':
            case '后三大小单双':
            case '前二大小单双':
            case '后二大小单双':
            case '三码大小单双':
                self::dxdsh_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '定位胆':
                self::dwd_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三一码不定位':
            case '后三一码不定位':
            case "后四一码不定位":
            case "前四一码不定位":
            case "五星一码不定位":
            case "一码不定位":
                self::ymbdw_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '前三二码不定位':
            case '后三二码不定位':
            case "后四二码不定位":
            case "前四二码不定位":
            case  "五星二码不定位":
            case  '前五星二码不定位':
            case  '二码不定位':

                self::ermbdw_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '五星三码不定位':
            case  '前五星三码不定位':
                self::smbdw_zhu($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '和值':
                self::ks_hezhi($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '和值大':
                self::ks_hezhida($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '和值小':
                self::ks_hezhixiao($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '和值单':
                self::ks_hezhidan($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '和值双':
                self::ks_hezhishuang($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '三同号通选':
                self::ks_sthtx($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '三同号单选':
                self::ks_sthdx($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '二同号复选':
                self::ks_erthfx($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '二同号单选':
                self::ks_erthdx($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '一不同号':
                self::ks_ybth($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '二不同号':
                self::ks_erbth($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '三不同号':
                self::ks_sanbth($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '三连号通选':
                self::ks_slhtx($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case  '三连号单选':
                self::ks_slhdx($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '任二直选复式':
                self::rener_zxfs($unum, $sub_zhu, $gtype, $gid, $lottery_number_id);
                break;
            case '任二直选单式':
                $unum = trim($unum);
                self::rener_zxds($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任二直选和值':
                self::rener_zxhz($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任二组选复式':
                self::rener_zhuxuanfs($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任二组选单式':
                $unum = trim($unum);
                self::rener_zhuxuands($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任二组选和值':
                self::rener_zhuxuanhz($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任三直选复式':
                self::rensan_zhixuanfs($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任三直选单式':
                $unum = trim($unum);
                self::rensan_zhixuands($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任三直选和值':
                self::rensan_zhixuanhz($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '组三复式':
                self::rensan_zhusanfs($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '组三单式':
                self::rensan_zhusands($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '组六复式':
                self::rensan_zhuliufs($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '组六单式':
                $unum = trim($unum);
                self::rensan_zhuliuds($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任三混合组选':
                $unum = trim($unum);
                self::rensan_hunhezx($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任三组选和值':
                self::rensan_zhuxuanhezhi($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任四直选复式':
                self::rensi_zhixuanfs($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任四直选单式':
                $unum = trim($unum);
                self::rensi_zhixuands($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任四组选24':
                self::rensi_zhixuan24($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任四组选12':
                self::rensi_zhixuan12($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任四组选6':
                self::rensi_zhixuan6($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '任四组选4':
                self::rensi_zhixuan4($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
            case '龙虎和万千':
            case '龙虎和万百':
            case '龙虎和万十':
            case '龙虎和万个':
            case '龙虎和千百':
            case '龙虎和千十':
            case '龙虎和千个':
            case '龙虎和百十':
            case '龙虎和百个':
            case '龙虎和十个':
                self::longhuhe($unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id);
                break;
        }
    }

    /*
     *
     * 记录非法操作日志到SeasLog
     *
     * */
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

    /**
     * 对于多行选号的判断
     *
     * @param $inputNumber <投注号码>
     * @param $len <号码位数>
     * @param $min <号码最小值>
     * @param $max <号码最大值>
     * @param $gid <彩票ID>
     * @param $lottery_number_id <期号ID>
     * @param $gtype <玩法>
     * @param $randkey <随机参数>
     */
    public static function _check_number($inputNumber, $len, $min, $max, $gid, $lottery_number_id, $gtype)
    {
        $temp_num_arr = explode(",", $inputNumber);
        if (count($temp_num_arr) != $len) {
            self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码个数不正确]");
            exit;
        }
        foreach ($temp_num_arr as $k => $v) {
            $len = strlen($v);
            $temp = array();
            for ($i = 0; $i < $len; $i++) {
                if (intval($v[$i]) < $min OR intval($v[$i]) > $max) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码不在正确区间范围]");
                    exit;
                }
                if (!preg_match("/^\d*$/", $v[$i])) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有非法字符]");
                    exit;
                }
                array_push($temp, $v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if ($count1 > $count2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有重复]");
                exit;
            }
        }
    }

    /**
     * 针对单行复选的选号判断
     *
     * @param $inputNumber <投注号码>
     * @param $len <号码位数>
     * @param $min <号码最小值>
     * @param $max <号码最大值>
     * @param $gid <彩票ID>
     * @param $lottery_number_id <期号ID>
     * @param $gtype <玩法>
     * @param $randkey <随机参数>
     */
    public static function  _check_number_2($inputNumber, $min, $max, $gid, $lottery_number_id, $gtype)
    {
        $len = strlen($inputNumber);
        $temp = array();
        for ($i = 0; $i < $len; $i++) {
            if (intval($inputNumber[$i]) < $min OR intval($inputNumber[$i]) > $max) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $inputNumber[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有非法字符]");
                exit;
            }
            array_push($temp, $inputNumber[$i]);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if ($count1 > $count2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有重复]");
            exit;
        }
    }


    /**
     * 前二后二和值号码检查
     *
     * @param $inputNumber
     * @param $min
     * @param $max
     * @param $len
     * @param $gid
     * @param $lottery_number_id
     * @param $gtype
     * @param $randkey
     */
    public static function _filter_hz($inputNumber, $min, $max, $len, $gid, $lottery_number_id, $gtype)
    {
        $temp_num = trim($inputNumber);
        $temp_num_arr = explode(",", $temp_num);
        if (count($temp_num_arr) > $len) {
            self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码个数不正确]");
            exit;
        }
        $temp = array();
        foreach ($temp_num_arr as $k => $v) {
            if ($v < $min OR $v > $max) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码取值区间不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $v)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有非法字符]");
                exit;
            }
            array_push($temp, $v);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if ($count1 > $count2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有重复号码]");
            exit;
        }
    }

    /**
     * 针对单式进行号码判断
     *
     * @param $inputNumber <投注号码>
     * @param $len <号码位数>
     * @param $min <号码最小值>
     * @param $max <号码最大值>
     * @param $gid <彩票ID>
     * @param $lottery_number_id <期号ID>
     * @param $gtype <玩法>
     * @param $randkey <随机参数>
     */
    public static function _check_number_danshi($inputNumber, $len, $gid, $lottery_number_id, $gtype)
    {
        if (strpos($inputNumber, ",") !== false) {
            $numArray = explode(",", $inputNumber);
        } elseif (strpos($inputNumber, ";") !== false) {
            $numArray = explode(";", $inputNumber);
        } elseif (strpos($inputNumber, " ") !== false) {
            $numArray = explode(" ", $inputNumber);
        } else {
            $numArray = array($inputNumber);
        }
        //验证开始
        foreach ($numArray as $key => $value) {
            $str_len = strlen($value);
            if ($str_len != $len) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有非法字符]");
                exit;
            }
        }
    }

    /**
     * 针对大小单双进行验证
     *
     * @param $inputNumber
     * @param $gid
     * @param $lottery_number_id
     * @param $gtype
     * @param $randkey
     */
    public static function _filter_two_row_ds($inputNumber, $gid, $lottery_number_id, $gtype)
    {
        $wordArray = array("大", "小", "单", "双");
        $buy_number_arr = explode(",", $inputNumber);
        if (count($buy_number_arr) != 2 && count($buy_number_arr) != 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码个数不正确]");
            exit;
        }
        foreach ($buy_number_arr as $item) {
            $itemarr = self::mb_str_split($item);
            $count = count($itemarr);
            $itemarrunique = array_unique($itemarr);
            $count2 = count($itemarrunique);
            if ($count != $count2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有重复号码]");
                exit();
            }
            foreach ($itemarr as $it) {
                if (!in_array($it, $wordArray)) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有非法字符]");
                    exit();
                }
            }
        }
    }

    /**
     * 针对定位胆进行验证
     *
     * @param $inputNumber
     * @param $min
     * @param $max
     * @param $gid
     * @param $lottery_number_id
     * @param $gtype
     * @param $randkey
     */
    public static function _filter_dingwei($inputNumber, $min, $max, $gid, $lottery_number_id, $gtype)
    {
        $buy_number_arr = explode(",", $inputNumber);
        if (count($buy_number_arr) > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码个数不正确]");
            exit;
        }
        $temp = array();
        foreach ($buy_number_arr as $k => $v) {
            if (!preg_match("/^\d*$/", $v)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有非法字符]");
                exit;
            }
            $len = strlen($v);
            for ($i = 0; $i < $len; $i++) {
                if (!empty($v[$i])) {
                    if ($v[$i] < $min OR $v[$i] > $max) {
                        self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码区间不正确]");
                        exit;
                    }
                    array_push($temp, $v[$i]);
                } else {
                    continue;
                }
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if ($count1 > $count2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $inputNumber . "[号码含有重复号码]");
                exit;
            }
            $temp = array();
        }
    }


    /**
     * 五星直选复式验证
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzhxfsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number($unum, 5, 0, 9, $gid, $lottery_number_id, $gtype);
        //过滤非法号码
        $temp_num_arr = explode(",", $unum);

        //先计算有多少个数组单位。少与5个是错误的。
        $temp_count = count($temp_num_arr);
        if ($temp_count != 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        $temp_zhu = 1;
        for ($i = 0; $i < 5; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
            $temp_zhu *= strlen($temp_num_arr[$i]);
        }

        if ($temp_zhu != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 组选120
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx120_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        //如果所选号码长度小于5位，那是错误的
        if ($temp_count < 5 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 5; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 5); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 五星组选60
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx60_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i = $counttwo; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 3; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($counttwo - 3); $j > 0; $j--) {
            $n *= $j;
        }
        $dh = $c / ($n * $m);

        for ($i = ($counttwo - 1); $i > 0; $i--) {
            $cc *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $mm *= $k;
        }
        for ($j = ($counttwo - 3); $j > 0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc / ($nn * $mm);

        $temp_zhu = $countone * $dh - $repeatcount * $dhdh;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 五星组选30
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx30_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 2 OR $counttwo < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit();
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i = $countone; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($countone - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $dh = $c / ($n * $m);

        for ($i = ($countone - 1); $i > 0; $i--) {
            $cc *= $i;
        }
        for ($k = 1; $k > 0; $k--) {
            $mm *= $k;
        }
        for ($j = ($countone - 2); $j > 0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc / ($nn * $mm);

        $temp_zhu = $counttwo * $dh - $repeatcount * $dhdh;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 五星组选20
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx20_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        $temp_is_digit = true;
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i = $counttwo; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($counttwo - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $dh = $c / ($n * $m);

        for ($i = ($counttwo - 1); $i > 0; $i--) {
            $cc *= $i;
        }
        for ($k = 1; $k > 0; $k--) {
            $mm *= $k;
        }
        for ($j = ($counttwo - 2); $j > 0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc / ($nn * $mm);

        $temp_zhu = $countone * $dh - $repeatcount * $dhdh;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 五星组选10
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx10_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone * $counttwo - $repeatcount;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 五星组选5
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx5_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone * $counttwo - $repeatcount;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 一帆风顺 好事成双 三星报喜 四季发财
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function special(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        //如果所选号码长度小于1位，那是错误的
        if ($temp_count < 1 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        $temp_zhu = $temp_count;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 拆分中文字符串
     *
     * @param $str
     * @param int $split_length
     * @param string $charset
     * @return array|bool
     */
    public static function mb_str_split($str, $split_length = 1, $charset = "UTF-8")
    {
        if (func_num_args() == 1) {
            return preg_split('/(?<!^)(?!$)/u', $str);
        }
        if ($split_length < 1) return false;
        $len = mb_strlen($str, $charset);
        $arr = array();
        for ($i = 0; $i < $len; $i += $split_length) {
            $s = mb_substr($str, $i, $split_length, $charset);
            $arr[] = $s;
        }
        return $arr;
    }

    /**
     * 龙虎和玩法验证
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function lfh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        if (preg_match("/^\d*$/", $unum)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit();
        }
        $arr = self::mb_str_split($unum);
        $arrunique = array_unique($arr);
        if (count($arr) != count($arrunique)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复]");
            exit();
        }
        if ($sub_zhu != count($arr)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($arr) . "]");
            exit();
        }
    }


    /**
     * 五星直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzhxdsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 5, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr[0] = $unum;
        }
        if (count($temp_num_arr) != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($temp_num_arr) . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前四直选复式 后四直选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function sxzhxfsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        //过滤非法号码
        self::_check_number($unum, 4, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);

        //先计算有多少个数组单位。少与4个是错误的。
        $temp_count = count($temp_num_arr);
        if ($temp_count < 4 OR $temp_count > 4) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }

        $temp_zhu = 1;
        for ($i = 0; $i < 4; $i++) {

            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
            $temp_zhu *= strlen($temp_num_arr[$i]);

        }

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前四直选单式 后四直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function sxzhxdsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        self::_check_number_danshi($unum, 4, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        if (count($temp_num_arr) != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($temp_num_arr) . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 四星组选24
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx24_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 4 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 4; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 4); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 四星组选12
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx12_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i = $counttwo; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($counttwo - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $dh = $c / ($n * $m);

        for ($i = ($counttwo - 1); $i > 0; $i--) {
            $cc *= $i;
        }
        for ($k = 1; $k > 0; $k--) {
            $mm *= $k;
        }
        for ($j = ($counttwo - 2); $j > 0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc / ($nn * $mm);

        $temp_zhu = $countone * $dh - $repeatcount * $dhdh;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 四星组选6
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx6_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 2 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 四星组选4
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function wxzx4_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count != 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
        }
        //计算注数
        $countone = $counttwo = 0;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone * $counttwo - $repeatcount;
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前三直选复式 中三直选复式  后三直选复式 福彩3d 直选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function shxzhxfsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 3, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);

        //先计算有多少个数组单位。少3个是错误的。
        $temp_count = count($temp_num_arr);
        if ($temp_count != 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }

        $temp_zhu = 1;
        for ($i = 0; $i < 3; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
            $temp_zhu *= strlen($temp_num_arr[$i]);
        }

        if ($temp_zhu != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前三直选单式 中三直选单式 后三直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function shxzhxdsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        self::_check_number_danshi($unum, 3, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        if (count($temp_num_arr) != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($temp_num_arr) . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前三组六 中三组六 后三组六
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function sxzl_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        $temp = array();
        for ($i = 0; $i < $temp_count; $i++) {
            if ($temp_num[$i] < 0 OR $temp_num[$i] > 9) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $temp_num[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
            array_push($temp, $temp_num[$i]);
        }
        $count1 = count($temp);
        $temp_unique = array_unique($temp);
        $count2 = count($temp_unique);
        if ($count1 > $count2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复]");
            exit;
        }

        if ($temp_count < 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;

        }
        if (!preg_match("/^\d*$/", $temp_num)) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }

        $z6c = 1;
        $z6n = 1;
        $z6m = 1;
        for ($i = $temp_count; $i > 0; $i--) {
            $z6c *= $i;
        }
        for ($k = 3; $k > 0; $k--) {
            $z6m *= $k;
        }
        for ($j = ($temp_count - 3); $j > 0; $j--) {
            $z6n *= $j;
        }
        $zhushu = $z6c / ($z6n * $z6m);
        if ($zhushu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $zhushu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 前三组三
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function sxzs_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;

        }
        if (!preg_match("/^\d*$/", $temp_num)) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        $zhushu = $temp_count * ($temp_count - 1);
        if ($zhushu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $zhushu . "]");
            exit;

        }
        $unum = $temp_num;
    }

    /**
     * 前三直选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function sxzhxhzh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        $temp_num_arr = explode(",", $unum);
        $is_digit = true;
        $total_zhu = 0;
        foreach ($temp_num_arr as $val) {
            if (!preg_match("/^\d*$/", $val)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;

            }
            for ($i = 0; $i <= 9; $i++) {
                for ($l = 0; $l <= 9; $l++) {
                    for ($loop = 0; $loop <= 9; $loop++) {
                        if (($i + $l + $loop) == $val) {
                            $total_zhu++;
                        }
                    }
                }
            }
        }


        if ($total_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $total_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前三混合组选  中三混合组选  后三混合组选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function sxhhzx_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 3, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach ($temp_num_arr as $key => $value) {
            $str_len = strlen($value);
            if ($str_len != 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
            $arr = array($value[0], $value[1], $value[2]);
            $arr_unique = array_unique($arr);
            if (count($arr_unique) == 1) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有豹子号]");
                exit;
            }
            $max = array_search(max($arr), $arr);
            $min = array_search(min($arr), $arr);
            if ($max > 9 || $min < 0) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码取值区间不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }

        $temp_count = count($temp_num_arr);

        if ($temp_count != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_count . "]");
            exit;
        }
        $unum = implode(',', $temp_num_arr);

    }

    /**
     * 前三组六胆拖 后三组六胆拖
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function zldt_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        $temp_num_arr = explode(",", $unum);
        if (count($temp_num_arr) != 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        $_s = $temp_num_arr[0];
        $_len = strlen($_s);
        if ($_len < 1 || $_len > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码胆码个数不正确]");
            exit;
        }
        $a1 = explode(" ", $temp_num_arr[0]);
        $a2 = explode(" ", $temp_num_arr[1]);
        $_arr = array_intersect($a1, $a2);
        if (count($_arr) != 0) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码胆码和托码含有相同号码]");
            exit;
        }
        foreach ($temp_num_arr as $k => $v) {
            $len = strlen($v);
            $temp = array();
            for ($i = 0; $i < $len; $i++) {
                if ($v[$i] < 0 OR $v[$i] > 9) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码取值范围不正确]");
                    exit;
                }
                if (!preg_match("/^\d$/", $v[$i])) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                    exit;
                }
                array_push($temp, $v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if ($count1 > $count2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
                exit;
            }
        }


        $strlen = strlen($temp_num_arr[0]);
        if ($strlen > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码胆码超过2个]");
            exit;

        }
        $temp_zhu = 1;

        if ($strlen == 2) {

            $temp_zhu = strlen(trim($temp_num_arr[1]));
            if ($temp_zhu < 1) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码拖码至少含有一个]");
                exit;
            }
        } elseif ($strlen == 1) {

            $sec_len = strlen(trim($temp_num_arr[1]));
            if ($sec_len < 2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码拖码低于2个]");
                exit;
            }
            $den = $sec_len * ($sec_len - 1);
            $ele = 1;
            for ($i = 2; $i >= 1; $i--) {

                $ele *= $i;
            }
            $temp_zhu = $den / $ele;
        }

        if ($temp_zhu != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前二直选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function erxzhxfsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);

        //先计算有多少个数组单位。少2个是错误的。
        $temp_count = count($temp_num_arr);
        if ($temp_count != 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }

        $temp_zhu = 1;
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
            $temp_zhu *= strlen($temp_num_arr[$i]);
        }

        if ($temp_zhu != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前二直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function erxzhxdsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        self::_check_number_danshi($unum, 2, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        if (count($temp_num_arr) != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($temp_num_arr) . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前二直选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function erxzhxhzh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        //过滤非法号码
        self::_filter_hz($unum, 0, 18, 19, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        $arr = explode(",", $unum);
        $total_zhu = 0;
        $config = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1);
        foreach ($arr as $val) {
            if (!preg_match("/^\d*$/", $val)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit();
            }
            $total_zhu = $total_zhu + $config[$val];
        }
        if ($total_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $total_zhu . "]");
            exit;
        }
        $unum = implode(",", $arr);
    }


    /**
     * 前二组选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function erxzxfsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);
        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;

        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        $z6c = $z6m = $z6n = 1;
        for ($i = $temp_count; $i > 0; $i--) {
            $z6c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $z6m *= $k;
        }
        for ($j = ($temp_count - 2); $j > 0; $j--) {
            $z6n *= $j;
        }
        $total_zhu = $z6c / ($z6n * $z6m);
        if ($total_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $total_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 前二组选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function erxzxdsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 2, $gid, $lottery_number_id, $gtype);
        if (strpos($unum, ",") !== false) {
            $numArray = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $numArray = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $numArray = explode(" ", $unum);
        } else {
            $numArray = array($unum);
        }
        //验证开始
        foreach ($numArray as $key => $value) {
            $_a = array($value[0], $value[1]);
            $_a_1 = array_unique($_a);
            if (count($_a_1) == 1) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有对子]");
                exit;
            }
            $str_len = strlen($value);
            if ($str_len != 2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
            $max = array_search(max($_a), $_a);
            $min = array_search(min($_a), $_a);
            if ($max > 9 || $min < 0) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码取值区间不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }

        if (count($numArray) != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($numArray) . "]");
            exit;
        }
        $unum = implode(",", $numArray);
    }


    /**
     * 前二组选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function erxzxhzh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_filter_hz($unum, 1, 17, 17, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        $config = array("", 1, 1, 2, 2, 3, 3, 4, 4, 5, 4, 4, 3, 3, 2, 2, 1, 1);
        $temp_num_arr = explode(",", $unum);
        $total_zhu = 0;
        foreach ($temp_num_arr as $val) {
            if (!preg_match("/^\d*$/", $val)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
            $total_zhu = $total_zhu + $config[$val];
        }
        if ($total_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $total_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 前三大小单双 后三大小单双 前二大小单双 后二大小单双
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function dxdsh_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        self::_filter_two_row_ds($unum, $gid, $lottery_number_id, $gtype);
        $temp_unum = trim($unum);
        $temp_unum_arr = explode(",", $temp_unum);
        $temp_count = count($temp_unum_arr);

        if ($temp_count != 2 && $temp_count != 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit();
        }
        $temp_zhu = 1;
        for ($i = 0; $i < $temp_count; $i++) {
            $numarr = self::mb_str_split($temp_unum_arr[$i]);
            $temp_zhu *= count($numarr);
        }

        if ($temp_zhu != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_unum_arr);
    }


    /**
     * 定位胆
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function dwd_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        self::_filter_dingwei($unum, 0, 9, $gid, $lottery_number_id, $gtype);
        $user_num_arr = explode(",", trim($unum));
        $total_ele = 0;
        $len_arr = count($user_num_arr);
        for ($i = 0; $i < $len_arr; $i++) {
            if ($user_num_arr[$i] != "") {
                $user_num_arr[$i] = trim($user_num_arr[$i]);
                $str_len = strlen($user_num_arr[$i]);
                $total_ele += $str_len;
            }
        }
        if ($total_ele != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $total_ele . "]");
            exit;

        }
    }

    /**
     * 一码不定位
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ymbdw_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);
        $temp_num_arr = trim($unum);
        $temp_count = strlen($temp_num_arr);
        if ($temp_count != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_count . "]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {

            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        $unum = $temp_num_arr;
    }

    /**
     * 二码不定位
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ermbdw_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);
        $temp_num_arr = trim($unum);
        $temp_count = strlen($temp_num_arr);
        if ($temp_count < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit();
        }
        $z6c = 1;
        $z6n = 1;
        $z6m = 1;
        for ($i = $temp_count; $i > 0; $i--) {
            $z6c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $z6m *= $k;
        }
        for ($j = ($temp_count - 2); $j > 0; $j--) {
            $z6n *= $j;
        }
        $temp_total = $z6c / ($z6n * $z6m);
        if ($temp_total != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_total . "]");
            exit;
        }
        $unum = $temp_num_arr;
    }

    /**
     * 三码不定位
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function smbdw_zhu(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);
        $temp_num_arr = trim($unum);
        $temp_count = strlen($temp_num_arr);
        if ($temp_count < 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit();
        }
        $z6c = 1;
        $z6n = 1;
        $z6m = 1;
        for ($i = $temp_count; $i > 0; $i--) {
            $z6c *= $i;
        }
        for ($k = 3; $k > 0; $k--) {
            $z6m *= $k;
        }
        for ($j = ($temp_count - 3); $j > 0; $j--) {
            $z6n *= $j;
        }
        $temp_total = $z6c / ($z6n * $z6m);
        if ($temp_total != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_total . "]");
            exit;
        }
        $unum = $temp_num_arr;
    }

    /**
     * 任二直选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rener_zxfs(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        $temp_num_arr = explode(",", $unum);
        if (count($temp_num_arr) < 2 || count($temp_num_arr) > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        foreach ($temp_num_arr as $k => $v) {
            $len = strlen($v);
            $temp = array();
            for ($i = 0; $i < $len; $i++) {
                if ($v[$i] < 0 OR $v[$i] > 9) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码区间不正确]");
                    exit;
                }
                if (!preg_match("/^\d*$/", $v[$i])) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                    exit;
                }
                array_push($temp, $v[$i]);
            }
            $count1 = count($temp);
            $temp_unique = array_unique($temp);
            $count2 = count($temp_unique);
            if ($count1 > $count2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
                exit;
            }
        }

        $temp_num_arr = explode(",", $unum);
        $count5 = $count4 = $count3 = $count2 = $count1 = 0;

        $count5 = ($temp_num_arr[0] == "") ? 0 : strlen($temp_num_arr[0]);
        $count4 = ($temp_num_arr[1] == "") ? 0 : strlen($temp_num_arr[1]);
        $count3 = ($temp_num_arr[2] == "") ? 0 : strlen($temp_num_arr[2]);
        $count2 = ($temp_num_arr[3] == "") ? 0 : strlen($temp_num_arr[3]);
        $count1 = ($temp_num_arr[4] == "") ? 0 : strlen($temp_num_arr[4]);
        //计算注数
        $temp_zhu = $count5 * ($count4 + $count3 + $count2 + $count1) + $count4 * ($count3 + $count2 + $count1) + $count3 * ($count2 + $count1) + $count2 * $count1;

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
    }


    /**
     * 任二直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rener_zxds(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 2, $gid, $lottery_number_id, $gtype);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
            if (strlen($temp_num_arr[$i]) != 2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置含有重复]");
            exit;
        }
        if ($temp_position_count < 2 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) / 2);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任二直选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rener_zxhz(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_filter_hz($unum, 0, 18, 19, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置有重复]");
            exit;
        }
        if ($temp_position_count < 2 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //任选二直选和值配置
        $zuconfig = array(0 => 1, 1 => 2, 2 => 3, 3 => 4, 4 => 5, 5 => 6, 6 => 7, 7 => 8, 8 => 9, 9 => 10, 10 => 9, 11 => 8, 12 => 7, 13 => 6, 14 => 5, 15 => 4, 16 => 3, 17 => 2, 18 => 1);
        $temp_zhu = 0;
        foreach ($temp_num_arr as $key => $value) {
            $temp_zhu += $zuconfig[$value];
        }
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) / 2);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任二组选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rener_zhuxuanfs(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 2 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 2 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置不正确]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) / 2);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 任二组选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rener_zhuxuands(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 2, $gid, $lottery_number_id, $gtype);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach ($temp_num_arr as $key => $value) {
            $_a = array($value[0], $value[1]);
            $_a_1 = array_unique($_a);
            if (count($_a_1) == 1) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码中含有对子]");
                exit;
            }
            $str_len = count($_a);
            if ($str_len != 2) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置有重复]");
            exit;
        }
        if ($temp_position_count < 2 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) / 2);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任二组选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rener_zhuxuanhz(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_filter_hz($unum, 1, 17, 17, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置有重复]");
            exit;
        }
        if ($temp_position_count < 2 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //任选二直选和值配置
        $zuconfig = array(1 => 1, 2 => 1, 3 => 2, 4 => 2, 5 => 3, 6 => 3, 7 => 4, 8 => 4, 9 => 5, 10 => 4, 11 => 4, 12 => 3, 13 => 3, 14 => 2, 15 => 2, 16 => 1, 17 => 1);
        $temp_zhu = 0;
        foreach ($temp_num_arr as $key => $value) {
            $temp_zhu += $zuconfig[$value];
        }
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) / 2);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任三直选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhixuanfs(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        $temp_num_arr = explode(",", $unum);
        if (count($temp_num_arr) < 3 || count($temp_num_arr) > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        foreach ($temp_num_arr as $k => $v) {
            if ($v != "") {
                $len = strlen($v);
                $temp = array();
                for ($i = 0; $i < $len; $i++) {
                    if ($v[$i] < 0 OR $v[$i] > 9) {
                        self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码取值范围不正确]");
                        exit;
                    }
                    if (!preg_match("/^\d*$/", $v[$i])) {
                        self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                        exit;
                    }
                    array_push($temp, $v[$i]);
                }
                $count1 = count($temp);
                $temp_unique = array_unique($temp);
                $count2 = count($temp_unique);
                if ($count1 > $count2) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
                    exit;
                }
            }
        }
        $a = $b = $c = $d = $e = 0;

        $a = ($temp_num_arr[0] == "") ? 0 : strlen($temp_num_arr[0]);
        $b = ($temp_num_arr[1] == "") ? 0 : strlen($temp_num_arr[1]);
        $c = ($temp_num_arr[2] == "") ? 0 : strlen($temp_num_arr[2]);
        $d = ($temp_num_arr[3] == "") ? 0 : strlen($temp_num_arr[3]);
        $e = ($temp_num_arr[4] == "") ? 0 : strlen($temp_num_arr[4]);

        //计算注数
        $temp_zhu = $a * ($b * $c + $b * $d + $b * $e + $c * $d + $c * $e + $d * $e) + $b * ($c * $d + $c * $e + $d * $e) + $c * $d * $e;

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
    }

    /**
     * 任三直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhixuands(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 3, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr[0] = $unum;
        }


        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            }
            if (strlen($temp_num_arr[$i]) != 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置不正确]");
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 任选三直选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhixuanhz(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_filter_hz($unum, 0, 27, 28, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //任选三直选和值配置
        $zuconfig = array(0 => 1, 1 => 3, 2 => 6, 3 => 10, 4 => 15, 5 => 21, 6 => 28, 7 => 36, 8 => 45, 9 => 55, 10 => 63, 11 => 69, 12 => 73, 13 => 75, 14 => 75, 15 => 73, 16 => 69, 17 => 63, 18 => 55, 19 => 45, 20 => 36, 21 => 28, 22 => 21, 23 => 15, 24 => 10, 25 => 6, 26 => 3, 27 => 1);
        $temp_zhu = 0;
        foreach ($temp_num_arr as $key => $value) {
            $temp_zhu += $zuconfig[$value];
        }
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 任选三 组三复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhusanfs(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 2 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        $temp_zhu = $temp_count * ($temp_count - 1);
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }


    /**
     * 任选三组三单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhusands(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 3, $gid, $lottery_number_id, $gtype);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach ($temp_num_arr as $key => $value) {
            $_a = array($value[0], $value[1], $value[2]);
            $_a_1 = array_unique($_a);
            if (count($_a_1) == 1) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码中含有豹子]");
                exit;
            }
            if (count($_a_1) == 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码中无对子号]");
                exit;
            }
            $str_len = strlen($value);
            if ($str_len != 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }

        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        $temp_count = count($temp_num_arr);
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任选三组六复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhuliufs(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 3 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 3; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 3); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }

    /**
     * 任选三组六单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhuliuds(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 3, $gid, $lottery_number_id, $gtype);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach ($temp_num_arr as $key => $value) {
            $_a = array($value[0], $value[1], $value[2]);
            $_a_1 = array_unique($_a);
            if (count($_a_1) != 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码中含有豹子或者对子]");
                exit;
            }
            $str_len = strlen($value);
            if ($str_len != 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码的个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        $temp_count = count($temp_num_arr);
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 任选三混合组选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_hunhezx(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 3, $gid, $lottery_number_id, $gtype);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr = array($unum);
        }
        //验证开始
        foreach ($temp_num_arr as $key => $value) {
            $_a = array($value[0], $value[1], $value[2]);
            $_a_1 = array_unique($_a);
            if (count($_a_1) == 1) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码中含有豹子]");
                exit;
            }
            $str_len = strlen($value);
            if ($str_len != 3) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码的个数不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $value)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        $temp_count = count($temp_num_arr);
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任选三组选和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensan_zhuxuanhezhi(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_filter_hz($unum, 1, 26, 26, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 3 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //任选三组选和值配置
        $zuconfig = array(1 => 1, 2 => 2, 3 => 2, 4 => 4, 5 => 5, 6 => 6, 7 => 8, 8 => 10, 9 => 11, 10 => 13, 11 => 14, 12 => 14, 13 => 15, 14 => 15, 15 => 14, 16 => 14, 17 => 13, 18 => 11, 19 => 10, 20 => 8, 21 => 6, 22 => 5, 23 => 4, 24 => 2, 25 => 2, 26 => 1);
        $temp_zhu = 0;
        foreach ($temp_num_arr as $key => $value) {
            $temp_zhu += $zuconfig[$value];
        }
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) / 6);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }

    /**
     * 任选四 直选复式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensi_zhixuanfs(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        $temp_num_arr = explode(",", $unum);
        if (count($temp_num_arr) < 4 || count($temp_num_arr) > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        foreach ($temp_num_arr as $k => $v) {
            if ($v != "") {
                $len = strlen($v);
                $temp = array();
                for ($i = 0; $i < $len; $i++) {
                    if ($v[$i] < 0 OR $v[$i] > 9) {
                        self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码区间不正确]");
                        exit;
                    }
                    if (!preg_match("/^\d*$/", $v[$i])) {
                        self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                        exit;
                    }
                    array_push($temp, $v[$i]);
                }
                $count1 = count($temp);
                $temp_unique = array_unique($temp);
                $count2 = count($temp_unique);
                if ($count1 > $count2) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
                    exit;
                }
            }
        }
        $a = $b = $c = $d = $e = 0;

        $a = ($temp_num_arr[0] == "") ? 0 : strlen($temp_num_arr[0]);
        $b = ($temp_num_arr[1] == "") ? 0 : strlen($temp_num_arr[1]);
        $c = ($temp_num_arr[2] == "") ? 0 : strlen($temp_num_arr[2]);
        $d = ($temp_num_arr[3] == "") ? 0 : strlen($temp_num_arr[3]);
        $e = ($temp_num_arr[4] == "") ? 0 : strlen($temp_num_arr[4]);

        $temp_count = count($temp_num_arr);
        //计算注数
        $temp_zhu = $a * $b * $c * $d + $a * $b * $c * $e + $a * $b * $d * $e + $a * $c * $d * $e + $b * $c * $d * $e;

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任选四直选单式
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensi_zhixuands(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        self::_check_number_danshi($unum, 4, $gid, $lottery_number_id, $gtype);
        $unum = trim($unum);
        if (strpos($unum, ",") !== false) {
            $temp_num_arr = explode(",", $unum);
        } elseif (strpos($unum, ";") !== false) {
            $temp_num_arr = explode(";", $unum);
        } elseif (strpos($unum, " ") !== false) {
            $temp_num_arr = explode(" ", $unum);
        } else {
            $temp_num_arr[0] = $unum;
        }
        $temp_count = count($temp_num_arr);
        if ($temp_count < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $temp_count; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 4 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //计算注数
        $temp_zhu = $temp_count * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) * ($temp_position_count - 3) / 24);

        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任选四组选24
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensi_zhixuan24(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 4 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 4 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 4; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 4); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) * ($temp_position_count - 3) / 24);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }


    /**
     * 任选四组选12
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensi_zhixuan12(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 4 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        //计算注数
        $countone = $counttwo = 0;
        $c = $m = $n = $cc = $mm = $nn = 1;
        $numberone = array();
        $numbertwo = array();
        //判断二重号与单号重复的个数
        $repeatcount = 0;
        $countone = strlen(trim($temp_num_arr[0]));
        $counttwo = strlen(trim($temp_num_arr[1]));
        if ($countone < 1 OR $counttwo < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        for ($i = $counttwo; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($counttwo - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $dh = $c / ($n * $m);

        for ($i = ($counttwo - 1); $i > 0; $i--) {
            $cc *= $i;
        }
        for ($k = 1; $k > 0; $k--) {
            $mm *= $k;
        }
        for ($j = ($counttwo - 2); $j > 0; $j--) {
            $nn *= $j;
        }
        $dhdh = $cc / ($nn * $mm);

        $temp_zhu = $countone * $dh - $repeatcount * $dhdh;
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) * ($temp_position_count - 3) / 24);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 任选四组选6
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensi_zhixuan6(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number_2($unum, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num = trim($unum);
        $temp_count = strlen($temp_num);
        if ($temp_count < 2 OR $temp_count > 10) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        if (!preg_match("/^\d*$/", $temp_num)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
            exit;
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 4 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
            exit;
        }
        $c = $m = $n = 1;
        //计算注数
        for ($i = $temp_count; $i > 0; $i--) {
            $c *= $i;
        }
        for ($k = 2; $k > 0; $k--) {
            $m *= $k;
        }
        for ($j = ($temp_count - 2); $j > 0; $j--) {
            $n *= $j;
        }
        $temp_zhu = $c / ($n * $m);
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) * ($temp_position_count - 3) / 24);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = $temp_num;
    }


    /**
     * 任选四组选4
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function rensi_zhixuan4(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        //过滤非法号码
        self::_check_number($unum, 2, 0, 9, $gid, $lottery_number_id, $gtype);

        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        //如果所选号码长度小于2位，那是错误的
        if ($temp_count < 2 OR $temp_count > 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < 2; $i++) {
            if (!preg_match("/^\d*$/", $temp_num_arr[$i])) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
        }
        //位置
        $temp_position_arr = explode("、", $position);
        $temp_position_count = count($temp_position_arr);
        //检查位置是否有重复
        $temp_position_arr_unicke = array_unique($temp_position_arr);
        if ($temp_position_count != count($temp_position_arr_unicke)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置重复]");
            exit;
        }
        if ($temp_position_count < 4 OR $temp_position_count > 5) {
            self::buy_log($gid, $lottery_number_id, $gtype, $position . "[任选位置个数不正确]");
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
        if ($countone < 1 OR $counttwo < 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        for ($i = 0; $i < $countone; $i++) {
            array_push($numberone, $temp_num_arr[0][$i]);
        }
        for ($i = 0; $i < $counttwo; $i++) {
            array_push($numbertwo, $temp_num_arr[1][$i]);
        }
        foreach ($numberone as $value) {
            if (in_array($value, $numbertwo))
                ++$repeatcount;
        }
        $temp_zhu = $countone * $counttwo - $repeatcount;
        $temp_zhu = $temp_zhu * ($temp_position_count * ($temp_position_count - 1) * ($temp_position_count - 2) * ($temp_position_count - 3) / 24);
        if ($temp_zhu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }
    
    /**
     * 龙虎和
     *
     * @param $unum
     * @param $sub_zhu
     * @param $position
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function longhuhe(&$unum, $sub_zhu, $position, $gtype, $gid, $lottery_number_id)
    {
        $temp_num_arr = explode(",", $unum);
        $temp_count = count($temp_num_arr);
        if ($temp_count != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $temp_zhu . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    //
    //==============================================时时彩玩法结束==================================================
    //
    //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


    /**
     * 快三和值
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ks_hezhi(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        self::_filter_hz($unum, 3, 18, 16, $gid, $lottery_number_id, $gtype);

        $unum = trim($unum);
        $temp_num_arr = explode(",", $unum);
        $tempcomp = array();
        foreach ($temp_num_arr as $vv) {
            if ($vv < 3 OR $vv > 18) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码区间不正确]");
                exit;
            }
            if (!preg_match("/^\d*$/", $vv)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;
            }
            array_push($tempcomp, $vv);
        }
        $count1 = count($tempcomp);
        $temp_unique = array_unique($tempcomp);
        $count2 = count($temp_unique);
        if ($count1 > $count2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
            exit;
        }

        if (count($temp_num_arr) != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($temp_num_arr) . "]");
            exit;
        }
        $unum = implode(",", $temp_num_arr);
    }


    /**
     * 快三和值大
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ks_hezhida(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        if ($unum != "和值大") {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
            exit;
        }
    }


    /**
     * 快三和值小
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ks_hezhixiao(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        if ($unum != "和值小") {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
            exit;
        }
    }


    /**
     * 快三和值单
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ks_hezhidan(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        if ($unum != "和值单") {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
            exit;
        }
    }


    /**
     * 快三和值双
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ks_hezhishuang(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        if ($unum != "和值双") {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
            exit;
        }
    }


    /**
     * 快三三同号通选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     * @param $randkey
     */
    public static function ks_sthtx(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        if ($unum != '三同号通选') {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
            exit;

        }
        if ($sub_zhu != 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $sub_zhu . "]");
            exit;

        }
        $unum = '1';
    }

    /**
     * 快三三同号单选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_sthdx(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {

        $unum = trim($unum);
        $temp_num_arr = explode(",", $unum);
        $_a = array_unique($temp_num_arr);
        if (count($temp_num_arr) > count($_a)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
            exit;
        }
        $temp_arr = array(111, 222, 333, 444, 555, 666);
        $new_arr = array();
        foreach ($temp_num_arr AS $val) {
            if (!preg_match("/^\d*$/", $val)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;

            }
            if (!in_array(trim($val), $temp_arr)) {

                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码取值不正确]");
                exit;
            }
            $new_arr[] = trim($val);

        }
        if (count($new_arr) != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($new_arr) . "]");
            exit;

        }
        $unum = implode(",", $new_arr);
    }


    /**
     * 快三二同号复选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_erthfx(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        $temp_num_arr = explode(",", $unum);
        $_a = array_unique($temp_num_arr);
        if (count($temp_num_arr) > count($_a)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
            exit;
        }
        $temp_arr = array("11-", "22-", "33-", "44-", "55-", "66-");
        $new_arr = array();
        foreach ($temp_num_arr AS $val) {
            if (!in_array(trim($val), $temp_arr)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码取值不正确]");
                exit;
            } else {
                $new_arr[] = trim($val);
            }

        }

        if (count($new_arr) != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($new_arr) . "]");
            exit;

        }
        $unum = implode(",", $new_arr);
    }


    /**
     * 快三二同号单选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_erthdx(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        $temp_num_arr = explode(",", $unum);
        $num1 = $temp_num_arr[0];
        $num1 = rtrim($num1);
        $num2 = $temp_num_arr[1];
        $num2 = rtrim($num2);
        $temp_arr = array();
        foreach ($num1 as $k => $v) {
            $temp_arr[] = substr($v, 0, 1);
        }
        $_a_1 = array_intersect($temp_arr, $num2);
        if (count($_a_1) > 0) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码同号和不同号含有相同的号码]");
            exit;
        }

        $num1_array = explode(" ", $num1);
        $temp1 = array();
        foreach ($num1_array as $k => $v) {
            if (!in_array($v, array("11", "22", "33", "44", "55", "66"))) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码同号的取值不正确]");
                exit;
            }
            array_push($temp1, $v);
        }
        $count1 = count($temp1);
        $tempArray = array_unique($temp1);
        $count2 = count($tempArray);
        if ($count1 > $count2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码同号含有重复号码]");
            exit;
        }

        $num2_array = explode(" ", $num2);
        $temp2 = array();
        foreach ($num2_array as $kk => $vv) {
            if (!in_array($vv, array("1", "2", "3", "4", "5", "6"))) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不同号取值不正确]");
                exit;
            }
            array_push($temp2, $vv);
        }
        $count11 = count($temp2);
        $tempArray1 = array_unique($temp2);
        $count22 = count($tempArray1);
        if ($count11 > $count22) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不同号含有重复号码]");
            exit;
        }
        $new_arr = array();

        if (count($temp_num_arr) != 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;

        }
        $first_arr = explode(" ", trim($temp_num_arr[0]));
        $temp_var = "";
        $first_arr_len = count($first_arr);
        $temp_var1 = trim($temp_num_arr[1]);
        $strlen = count(explode(" ", $temp_var1));
        if ($temp_var1 == "") {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不同号为空]");
            exit;

        }
        foreach ($first_arr AS $val) {
            $temp_var = trim($val);
            if (!preg_match("/^\d*$/", $temp_var)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;

            }
            if (strlen($temp_var) != 2) {

                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码同号单个号码个数不正确]");
                exit;
            }
            if ($temp_var[0] != $temp_var[1]) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码同号不正确]");
                exit;

            }
            for ($j = 0; $j < $strlen; $j++) {
                if ($temp_var[0] == $temp_var1[$j]) {
                    self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不同号不正确]");
                    exit;

                }
            }
            $new_arr[] = $temp_var;
        }
        if (($first_arr_len * $strlen) != $sub_zhu) {

            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . ($first_arr_len * $strlen) . "]");
            exit;

        }
        $temp_unum1 = implode(" ", $new_arr);
        $unum = $temp_unum1 . "," . str_replace(" ", "", $temp_var1);
    }


    /**
     * 快三一不同号
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_ybth(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $newnum = str_replace(",","",$unum);
        self::_check_number_2($newnum, 1, 6, $gid, $lottery_number_id, $gtype);
        $unum = explode(",", trim($unum));
        if (count($unum) != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . count($unum) . "]");
            exit;

        }
    }


    /**
     * 快三二不同号
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_erbth(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $newnum = str_replace(",","",$unum);
        self::_check_number_2($newnum, 1, 6, $gid, $lottery_number_id, $gtype);
        $unum = explode(",", trim($unum));
        $len = count($unum);
        if ($len < 2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数正确]");
            exit;
        }
        $zhushu = 0;
        switch ($len) {
            case 2:
                $zhushu = 1;
                break;
            case 3:
                $zhushu = 3;
                break;
            case 4:
                $zhushu = 6;
                break;
            case 5:
                $zhushu = 10;
                break;
            case 6:
                $zhushu = 15;
                break;
        }
        if ($zhushu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $zhushu . "]");
            exit;
        }
    }


    /**
     * 快三三不同号
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_sanbth(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $newnum = str_replace(",","",$unum);
        self::_check_number_2($newnum, 1, 6, $gid, $lottery_number_id, $gtype);
        $unum = explode(",", trim($unum));
        $len = count($unum);
        if ($len < 3) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码个数不正确]");
            exit;
        }
        $zhushu = 0;
        switch ($len) {

            case 3:
                $zhushu = 1;
                break;
            case 4:
                $zhushu = 4;
                break;
            case 5:
                $zhushu = 10;
                break;
            case 6:
                $zhushu = 20;
                break;
        }
        if ($zhushu != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数:" . $zhushu . "]");
            exit;
        }
    }


    /**
     * 快三 三连号通选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_slhtx(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $wordArray = array("三连号通选");
        $temp_num_arr = trim($unum);
        if (!in_array($temp_num_arr, $wordArray)) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
            exit;
        }
        $unum = trim($unum);
        if ($sub_zhu != 1) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数1]");
            exit;
        }
        $unum = '1';
    }

    /**
     * 快三三连号单选
     *
     * @param $unum
     * @param $sub_zhu
     * @param $gtype
     * @param $gid
     * @param $lottery_number_id
     */
    public static function ks_slhdx(&$unum, $sub_zhu, $gtype, $gid, $lottery_number_id)
    {
        $unum = trim($unum);
        $temp_num_arr = explode(",", $unum);
        $temp_arr = array(123, 234, 345, 456);
        $new_arr = array();
        foreach ($temp_num_arr AS $val) {
            if (!preg_match("/^\d*$/", $val)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有非法字符]");
                exit;

            }
            if (!in_array(trim($val), $temp_arr)) {
                self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码不正确]");
                exit;
            } else {
                $new_arr[] = trim($val);
            }
        }
        $count1 = count($temp_num_arr);
        $arrayUnike = array_unique($temp_num_arr);
        $count2 = count($arrayUnike);
        if ($count1 > $count2) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[号码含有重复号码]");
            exit;
        }
        if (count($new_arr) != $sub_zhu) {
            self::buy_log($gid, $lottery_number_id, $gtype, $unum . "[提交注数:" . $sub_zhu . ",实际注数" . count($new_arr) . "]");
            exit;

        }
        $unum = implode(",", $new_arr);
    }
}
