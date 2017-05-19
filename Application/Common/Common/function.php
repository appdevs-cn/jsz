<?php

//
// 统计团队报表数据
// 参数说明  $_input 数组 $_input = array(“用户id”，“充值金额” ，“提现金额” ， “投注金额” ，“返点金额” ，“奖金金额” ，“广告金额”，“代理佣金”);
//
function _team_report($_input = array()) {

    $_uid = $_input[0];
    $_rechargeAmount = $_input[1];
    $_tixianAmount = $_input[2];
    $_touzhuAmount = $_input[3];
    $_fandianAmount = $_input[4];
    $_bonusAmount = $_input[5];
    $_privilegeAmount = $_input[6];
    $_commission = $_input[7];
    $_accordTime = time();

    $userModel = M("user");
    $accordingTimeModel = M("according_time");
    $_userInfo = $userModel->where(array("id" => $_uid, "status" => 1))->find();
    if (empty($_userInfo))
        return false;

    $_username = $_userInfo["username"];
    $_parentId = $_userInfo["parent_id"];
    $_parentPath = $_userInfo["parent_path"];



    //according_time 表数据对象
    $nowTime = time();
    $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y"))));
    $today3Time = strtotime(date("Y-m-d H:i:s", mktime(3, 0, 0, date("m"), date("d"), date("Y"))));

    if ($nowTime >= $today0Time && $nowTime < $today3Time) {
        $accordtime = mktime(3, 0, 0, date('m'), date('d') - 1, date('Y'));
    } else {
        $accordtime = mktime(3, 0, 0, date('m'), date('d'), date('Y'));
    }

    //开启事务
    $accordingTimeModel->startTrans();
    $_map["accordTime"] = $accordtime;
    $_map["userid"] = $_uid;
    //统计单用户单天的数据
    $_according_time_exists = $accordingTimeModel->db(0)->lock(true)->where($_map)->find();
    if (!empty($_according_time_exists)) {
        $_new_rechargeAmount = $_according_time_exists["rechargeAmount"] + $_rechargeAmount;
        $_new_tixianAmount = $_according_time_exists["tixianAmount"] + $_tixianAmount;
        $_new_touzhuAmount = $_according_time_exists["touzhuAmount"] + $_touzhuAmount;
        $_new_fandianAmount = $_according_time_exists["fandianAmount"] + $_fandianAmount;
        $_new_bonusAmount = $_according_time_exists["bonusAmount"] + $_bonusAmount;
        $_new_privilegeAmount = $_according_time_exists["privilegeAmount"] + $_privilegeAmount;
        $_new_commission = $_according_time_exists["commission"] + $_commission;
        $_new_gain = ($_new_bonusAmount + $_new_fandianAmount + $_new_commission + $_new_privilegeAmount) - $_new_touzhuAmount;

        $update = $accordingTimeModel->db(0)->where(array("id" => $_according_time_exists["id"]))->save(array(
            "rechargeAmount" => $_new_rechargeAmount,
            "tixianAmount" => $_new_tixianAmount,
            "touzhuAmount" => $_new_touzhuAmount,
            "fandianAmount" => $_new_fandianAmount,
            "bonusAmount" => $_new_bonusAmount,
            "privilegeAmount" => $_new_privilegeAmount,
            "commission" => $_new_commission,
            "accordTime" => $accordtime,
            "gain" => $_new_gain
        ));
        $accordingTimeModel->commit();
    } else {
        //不存在该条纪录
        $gain = ($_bonusAmount + $_fandianAmount + $_commission + $_privilegeAmount) - $_touzhuAmount;
        $_data_1 = array(
            "userid" => $_userInfo["id"],
            "username" => $_username,
            "parent_id" => $_parentId,
            "parent_path" => $_parentPath,
            "rechargeAmount" => $_rechargeAmount,
            "tixianAmount" => $_tixianAmount,
            "touzhuAmount" => $_touzhuAmount,
            "fandianAmount" => $_fandianAmount,
            "bonusAmount" => $_bonusAmount,
            "privilegeAmount" => $_privilegeAmount,
            "commission" => $_commission,
            "accordTime" => $accordtime,
            "gain" => $gain
        );
        $insertid = $accordingTimeModel->db(0)->data($_data_1)->add();
        $accordingTimeModel->commit();
    }
}

//
// 统计团队报表数据[快乐彩]
// 参数说明  $_input 数组 $_input = array(“用户id”，“充值金额” ，“提现金额” ， “投注金额” ，“返点金额” ，“奖金金额” ，“广告金额”，“代理佣金”);
//
function _team_report_klc($_input = array()) {

    $_uid = $_input[0];
    $_rechargeAmount = $_input[1];
    $_tixianAmount = $_input[2];
    $_touzhuAmount = $_input[3];
    $_fandianAmount = $_input[4];
    $_bonusAmount = $_input[5];
    $_privilegeAmount = $_input[6];
    $_commission = $_input[7];
    $_accordTime = time();

    $userModel = M("user");
    $accordingTimeKlcModel = M("according_time_klc");

    import("Class.RedisObject");
    $redisObj = new \RedisObject();
    $result = $redisObj->_get("teamreportuser".$_uid);
    if(!empty($result))
    {
        $_userInfo = json_decode($redisObj->get("teamreportuser".$_uid),true);
    }
    else
    {
        $_userInfo = $userModel->where(array("id" => $_uid, "status" => 1))->find();
        $redisObj->_set("teamreportuser".$_uid,json_encode($_userInfo));
    }
    if (empty($_userInfo))
        return false;

    $_username = $_userInfo["username"];
    $_parentId = $_userInfo["parent_id"];
    $_parentPath = $_userInfo["parent_path"];



    //according_time 表数据对象
    $nowTime = time();
    $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y"))));
    $today3Time = strtotime(date("Y-m-d H:i:s", mktime(3, 0, 0, date("m"), date("d"), date("Y"))));

    if ($nowTime >= $today0Time && $nowTime < $today3Time) {
        $accordtime = mktime(3, 0, 0, date('m'), date('d') - 1, date('Y'));
    } else {
        $accordtime = mktime(3, 0, 0, date('m'), date('d'), date('Y'));
    }

    //开启事务
    $accordingTimeKlcModel->startTrans();
    $_map["accordTime"] = $accordtime;
    $_map["userid"] = $_uid;
    //统计单用户单天的数据
    $_according_time_exists = $accordingTimeKlcModel->db(0)->lock(true)->where($_map)->find();
    if (!empty($_according_time_exists)) {
        $_new_rechargeAmount = $_according_time_exists["rechargeAmount"] + $_rechargeAmount;
        $_new_tixianAmount = $_according_time_exists["tixianAmount"] + $_tixianAmount;
        $_new_touzhuAmount = $_according_time_exists["touzhuAmount"] + $_touzhuAmount;
        $_new_fandianAmount = $_according_time_exists["fandianAmount"] + $_fandianAmount;
        $_new_bonusAmount = $_according_time_exists["bonusAmount"] + $_bonusAmount;
        $_new_privilegeAmount = $_according_time_exists["privilegeAmount"] + $_privilegeAmount;
        $_new_commission = $_according_time_exists["commission"] + $_commission;
        $_new_gain = ($_new_bonusAmount + $_new_fandianAmount + $_new_commission + $_new_privilegeAmount) - $_new_touzhuAmount;

        $update = $accordingTimeKlcModel->db(0)->where(array("id" => $_according_time_exists["id"]))->save(array(
            "rechargeAmount" => $_new_rechargeAmount,
            "tixianAmount" => $_new_tixianAmount,
            "touzhuAmount" => $_new_touzhuAmount,
            "fandianAmount" => $_new_fandianAmount,
            "bonusAmount" => $_new_bonusAmount,
            "privilegeAmount" => $_new_privilegeAmount,
            "commission" => $_new_commission,
            "accordTime" => $accordtime,
            "gain" => $_new_gain
        ));
        $accordingTimeKlcModel->commit();
    } else {
        //不存在该条纪录
        $gain = ($_bonusAmount + $_fandianAmount + $_commission + $_privilegeAmount) - $_touzhuAmount;
        $_data_1 = array(
            "userid" => $_userInfo["id"],
            "username" => $_username,
            "parent_id" => $_parentId,
            "parent_path" => $_parentPath,
            "rechargeAmount" => $_rechargeAmount,
            "tixianAmount" => $_tixianAmount,
            "touzhuAmount" => $_touzhuAmount,
            "fandianAmount" => $_fandianAmount,
            "bonusAmount" => $_bonusAmount,
            "privilegeAmount" => $_privilegeAmount,
            "commission" => $_commission,
            "accordTime" => $accordtime,
            "gain" => $gain
        );
        $insertid = $accordingTimeKlcModel->db(0)->data($_data_1)->add();
        $accordingTimeKlcModel->commit();
    }
}

/**
  +----------------------------------------------------------
 * 发布站内信
  +----------------------------------------------------------
 * @access public
  +----------------------------------------------------------
 * @param $content 【内容：array('uid','tid','roleid','title','content', 'type')】
 * uid 发送者ID  tid接受者ID  title 邮件标题  content 邮件内容
 * type == 0 向下级所有人发送  type==1 执行人发送  type==3 向上级发送
  +----------------------------------------------------------
 * 
 * @return string $name
  +----------------------------------------------------------
 * @throws ThinkExecption
  +----------------------------------------------------------
 */
function sendMail($content) {
    $data = array();
    $udata = array();
    if (is_array($content) && !empty($content)) {
        list($uid, $tid, $roleid, $title, $cont, $type) = $content;
    }
    if ($roleid == 1 && $tid == "") {
        unset($data);
        $data["title"] = $title;
        $data["content"] = $cont;
        $data["isadmin"] = 1;
        $data["sendtime"] = time();
        $mid = M("mail")->data($data)->add();   #超级管理员发布广播
    } else if ($tid != "") {
        unset($data);
        $data["title"] = $title;
        $data["content"] = $cont;
        $data["isadmin"] = 0;
        $data["sendtime"] = time();
        $mid = M("mail")->data($data)->add();
        if (!empty($mid)) {
            switch ($roleid) {
                case 1:
                    if ($type == 1) {
                        $child = array(array("id" => $tid)); //指定会员发送信件
                    }
                    break;
                case 2 :
                    if ($type == 0)          # 向下级所有人发送
                        $child = M("user")->field("id")->where(array("parent_id" => $uid))->select();
                    else if ($type == 1)     # 向指定人发送
                        $child = array(array("id" => $tid));
                    break;
                case 3 :
                case 4 :
                    if ($type == 0) {
                        $child = M("user")->field("id")->where("FIND_IN_SET($uid,parent_path)")->select();
                    } else if ($type == 1) {
                        $child = array(array("id" => $tid));
                    }
                    break;
                case 5 :
                    $child = array(array("id" => $tid));
                    break;
                default :
                    break;
            }
            unset($udata);
            foreach ($child as $tid) {
                $tmp["mid"] = $mid;
                $tmp["uid"] = $uid;
                $tmp["tid"] = $tid["id"];
                $tmp["status"] = 0;   #标记消息为未读

                $udata[] = $tmp;
            }
            M("mailToUser")->addAll($udata);
        }
    }
}

function getIntervalCount($i, $userid) {
    $name = "interval_" . $i;
    return M("user_interval")->where(array("userid" => $userid))->getField($name);
}

function getParserByDrawId($id) {
    return $count = M("user_draw")->where(array("id" => array("lt", $id), "state" => 0))->count();
}

//获得token
function getToken() {
    $tokenName = "yashang";
    $tokenType = "md5";
    if (!isset($_SESSION[$tokenName])) {
        $_SESSION[$tokenName] = array();
    }
    // 标识当前页面唯一性
    $tokenKey = md5($_SERVER['REQUEST_URI']);
    if (isset($_SESSION[$tokenName][$tokenKey])) {// 相同页面不重复生成session
        $tokenValue = $_SESSION[$tokenName][$tokenKey];
    } else {
        $tokenValue = $tokenType(microtime(TRUE));
        $_SESSION[$tokenName][$tokenKey] = $tokenValue;
    }
    return $tokenKey . '_' . $tokenValue;
}

//验证token
function checkToken($data) {
    $name = "yashang";
    if (!isset($data) || !isset($_SESSION[$name])) { // 令牌数据无效
        return false;
    }
    // 令牌验证
    list($key, $value) = explode('_', $data);
    if ($value && $_SESSION[$name][$key] === $value) { // 防止重复提交
        unset($_SESSION[$name][$key]); // 验证完成销毁session
        return true;
    }
    // 开启TOKEN重置
    unset($_SESSION[$name][$key]);
    return false;
}

function dowith_sql($str)
{
   $str = str_replace("execute","",$str);
   $str = str_replace("update","",$str);
   $str = str_replace("chr","",$str);
   $str = str_replace("mid","",$str);
   $str = str_replace("master","",$str);
   $str = str_replace("truncate","",$str);
   //$str = str_replace("char","",$str);
   $str = str_replace("declare","",$str);
   $str = str_replace("select","",$str);
   $str = str_replace("create","",$str);
   $str = str_replace("delete","",$str);
   $str = str_replace("insert","",$str);
   $str = str_replace("union","",$str);
   $str = str_replace("\"","",$str);
   $str = str_replace("Btype","",$str);
   $str = str_replace("%20","",$str);
   return $str;
}

/** 
* Respose A Http Request 
* 
* @param string $url 
* @param array $post 
* @param string $method 
* @param bool $returnHeader 
* @param string $cookie 
* @param bool $bysocket 
* @param string $ip 
* @param integer $timeout 
* @param bool $block 
* @return string Response 
*/  
function httpRequest($url,$post='',$method='GET',$limit=0,$returnHeader=FALSE,$cookie='',$bysocket=FALSE,$ip='',$timeout=15,$block=TRUE) {  
    $return = '';  
    $matches = parse_url($url);  
    !isset($matches['host']) && $matches['host'] = '';  
    !isset($matches['path']) && $matches['path'] = '';  
    !isset($matches['query']) && $matches['query'] = '';  
    !isset($matches['port']) && $matches['port'] = '';  
    $host = $matches['host'];  
    $path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
    $port = !empty($matches['port']) ? $matches['port'] : 80;  
    if(strtolower($method) == 'post') {  
        $post = (is_array($post) and !empty($post)) ? http_build_query($post) : $post;  
        $out = "POST $path HTTP/1.0\r\n";  
        $out .= "Accept: */*\r\n";  
        //$out .= "Referer: $boardurl\r\n";  
        $out .= "Accept-Language: zh-cn\r\n";  
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";  
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";  
        $out .= "Host: $host\r\n";  
        $out .= 'Content-Length: '.strlen($post)."\r\n";  
        $out .= "Connection: Close\r\n";  
        $out .= "Cache-Control: no-cache\r\n";  
        $out .= "Cookie: $cookie\r\n\r\n";  
        $out .= $post;  
    } else {  
        $out = "GET $path HTTP/1.0\r\n";  
        $out .= "Accept: */*\r\n";  
        //$out .= "Referer: $boardurl\r\n";  
        $out .= "Accept-Language: zh-cn\r\n";  
        $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";  
        $out .= "Host: $host\r\n";  
        $out .= "Connection: Close\r\n";  
        $out .= "Cookie: $cookie\r\n\r\n";
    }  
    $fp = fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout); 
    if(!$fp) return ''; else {  
        $header = $content = '';  
        stream_set_blocking($fp, $block);  
        stream_set_timeout($fp, $timeout);  
        fwrite($fp, $out);
        $status = stream_get_meta_data($fp);  
        if(!$status['timed_out']) {//未超时  
            while (!feof($fp)) {  
                $header .= $h = fgets($fp);  
                if($h && ($h == "\r\n" ||  $h == "\n")) break;  
            }
            $stop = false;  
            while(!feof($fp) && !$stop) {
                $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit)); 
                $content .= $data;  
                if($limit) {  
                    $limit -= strlen($data);  
                    $stop = $limit <= 0;  
                }  
            }
        }  
    fclose($fp);  
        return $returnHeader ? array($header,$content) : $content;  
    }  
} 

?>
