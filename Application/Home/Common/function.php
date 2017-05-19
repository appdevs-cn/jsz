<?php

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +ip地址转换为整数形式
// +
// +param string $ipStr
// +return int
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function ipton($ipStr) {
    return ip2long($ipStr);
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +ip整形地址转换为字符串形式
// +
// +param string $n
// +return int
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function ntoip($n) {
    return long2ip($n);
}

function check_lottery ($lottery_id) {
    if(!is_numeric($lottery_id)) {
        return false;
    } else {
        if(!in_array($lottery_id, array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35'))) {
            return false;
        }
    }
    return true;
}

function sourceURL ($url,$lottery_id) {;
    $var=array(1=>'Lotter/Ssc/Cqssc',2=>'Lotter/Ssc/Jxssc',3=>'Lotter/Ssc/Xjssc',4=>'Lotter/Ssc/Tjssc',5=>'/Lotter/11x5/Jsxw',6=>'/Lotter/11x5/Jxxw',7=>'/Lotter/11x5/Gdxw',8=>'/Lotter/11x5/Sdxw',11=>'/Lotter/Welfare/3d',12=>'/Lotter/Welfare/p3',10=>'/Lotter/Klsf/Cqklsf',9=>'/Lotter/Klsf/Gdklsf',15=>'/Lotter/K3/Jsk3',16=>'/Lotter/K3/Jlk3',13=>'/Lotter/K8/Bjk8', 14=>'/Lotter/Ssq/Ssq',17=>'Lotter/Ssc/Xy5ssc',18=>'Lotter/Ssc/Xy2ssc',19=>'Lotter/Ssc/Xy1ssc',20=>'Lotter/Ssc/Xlssc',21=>'Lotter/Bjpk10/Pk10',22=>'Lotter/Ssc/Hgssc',23=>'Lotter/Lucky/lucky28',24=>'Lotter/11x5/Sxxw',25=>'Lotter/11x5/Sxxxw',26=>'Lotter/Klsf/Sxklsf',27=>'Lotter/Klsf/Sxxklsf',28=>'Lotter/Lucky/Hglucky28',29=>'Lotter/Lucky/Jndlucky28',30=>'Lotter/Ssc/Bj5f',31=>'Lotter/Ssc/Jnd3f',32=>'Lotter/Ssc/Txssc',33=>'Lotter/Ssc/Qqssc',34=>'Lotter/Ssc/Twssc',35=>'/Lotter/K8/Twk8');
    if(strpos($url,$var[$lottery_id])!==false){

        return true;

    }else {

        return false;

    }

}

function remove_xss($val) {
    // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
    // this prevents some character re-spacing such as <java\0script>
    // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
    $val = preg_replace('/([\x00-\x08\x0b-\x0c\x0e-\x19])/', '', $val);

    // straight replacements, the user should never need these since they're normal characters
    // this prevents like <IMG SRC=@avascript:alert('XSS')>
    $search = 'abcdefghijklmnopqrstuvwxyz';
    $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $search .= '1234567890!@#$%^&*()';
    $search .= '~`";:?+/={}[]-_|\'\\';
    for ($i = 0; $i < strlen($search); $i++) {
        // ;? matches the ;, which is optional
        // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

        // @ @ search for the hex values
        $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
        // @ @ 0{0,7} matches '0' zero to seven times
        $val = preg_replace('/(�{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
    }

    // now the only remaining whitespace attacks are \t, \n, and \r
    $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
    $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    $ra = array_merge($ra1, $ra2);

    $found = true; // keep replacing as long as the previous round replaced something
    while ($found == true) {
        $val_before = $val;
        for ($i = 0; $i < sizeof($ra); $i++) {
            $pattern = '/';
            for ($j = 0; $j < strlen($ra[$i]); $j++) {
                if ($j > 0) {
                    $pattern .= '(';
                    $pattern .= '(&#[xX]0{0,8}([9ab]);)';
                    $pattern .= '|';
                    $pattern .= '|(�{0,8}([9|10|13]);)';
                    $pattern .= ')*';
                }
                $pattern .= $ra[$i][$j];
            }
            $pattern .= '/i';
            $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
            $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
            if ($val_before == $val) {
                // no replacements were made, so exit the loop
                $found = false;
            }
        }
    }
    return $val;
}

function checkNumber ( $number, $length='', $floatlength = 2, $type='int') {
    switch ( $type ) {
        case 'int' :
            if(!is_numeric($number) || $number == "")
                return false;
            if($length == '') {
                if(preg_match("/^\d+$/", $number))
                    return true;
                else
                    return false;
            } else {
                if(preg_match("/^(\d){1,$length}$/", $number))
                    return true;
                else
                    return false;
            }
            break;
        case 'float' :
            if(!is_numeric($number) || $number == "")
                return false;
            if($length == '') {
                if(preg_match("/^[0-9]+(\.[\d]{1,$floatlength})*$/", $number))
                    return true;
                else
                    return false;
            } else {
                if(preg_match("/^[0-9]{1,$length}(\.[\d]{1,$floatlength})*$/", $number))
                    return true;
                else
                    return false;
            }
            break;
        default :
            break;
    }
}

function isNumber($String)
{
    if(is_numeric($String)){
        if($String>=0)
            return true;
        else
            return false;
    }
}

function checkLotteryId ($lottery_id) {
    if(!is_numeric($lottery_id)) {
        return false;
    } else {
        if(!in_array($lottery_id, array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35'))) {
            return false;
        }
    }
    return true;
}

function getCurrentNumID ($id,$para) {
    $dateNow=time();

    $model=M('lottery_number_memory');

    if( $row=$model->field('series_number,starttime,endtime')->where('id='.$id)->find() )
    {
        //无论追号还是正常购买，期号ID符合下面也是符合要求。
        //echo $row['starttime']."-".$row['endtime']."-".$dateNow;
        if( $row['starttime'] < $dateNow && $row['endtime'] > $dateNow ){
            
            return $row['series_number'];

        }
        //如果是追号，第一期的期号还可以满足以下条件
        if($para==1)
        {
            if( ($row['starttime'] > $dateNow) or ($row['starttime'] < $dateNow and $row['endtime'] > $dateNow ))
            {


                return $row['series_number'];

            }else{

                return false;

            }
        }

        return false;

    }else{

        $model=M('lottery_number');
        $row=$model->field('series_number,starttime,endtime')->where('id='.$id)->find();
        if( $row['starttime'] < $dateNow && $row['endtime'] > $dateNow ){

            return $row['series_number'];

        }

        if($para==1)
        {
            if( ($row['starttime'] > $dateNow) or ($row['starttime'] < $dateNow and $row['endtime'] > $dateNow ))
            {

                return $row['series_number'];

            }else{

                return false;
            }
        }
        return false;
    }

    return false;

}

function getShortName($key){
    $array = C("LOTTERY");
    return $array[$key]['for_short'];
}

function doArray ($string) {
    $str1 = preg_replace('/<dl><dt>/', '||', $string);
    $str2 = preg_replace('/<\/dt><dt>/', '||', $str1);
    $str3 = strip_tags($str2);
    return preg_replace('/_(\d)+\.0/', '', $str3);
}

/*************************
PHP通用防注入安全代码
说明：
判断传递的变量中是否含有非法字符
如$_POST、$_GET
功能：
防注入
 **************************/

//是否存在数组中的值
function FunStringExist($StrFiltrate,$ArrFiltrate){
    foreach ($ArrFiltrate as $key=>$value){
        preg_match_all("#{$value}#",$StrFiltrate,$match);
        if (!empty($match[0])){
            return true;
        }
    }
    return false;
}


/**
+----------------------------------------------------------
 * 导航路径
+----------------------------------------------------------
 * @access public
+----------------------------------------------------------
 * @param $uid 【用户ID】
+----------------------------------------------------------
 * @author 
 * @return string
+----------------------------------------------------------
 * @throws ThinkExecption
+----------------------------------------------------------
 */
function usernavigate ( $uid ) {
    $html = "<div class='navigateCls'>";
    $html .= '<span><a href="javascript:void(0);" class="showChild" childid="">我的用户</a></span>';
    $_sid = session("SESSION_ID");
    $_parentid = session("SESSION_PARENTID");
    $user = M("user")->field("id,parent_path,username,parent_id")->where("FIND_IN_SET(".$_sid.",parent_path) AND id=".$uid)->find();
    if(!empty($user) && !empty($user["parent_path"])) {
        $parent_path = explode(",", $user["parent_path"]);
        if($uid==$_sid){
            return $html;
        } else {
            array_shift($parent_path);
            array_shift($parent_path);
            if(!empty($parent_path)) {
                foreach ($parent_path as $k => $v) {
                    if($v==$_parentid){
                        continue;
                    }
                    $row = M("user")->field('id,parent_path,username')->where('id=' . $v)->find();
                    $html .= '<img src=' . C('IM_DOMAIN') . '/Home/images/native.gif>';
                    $html .= '<a href="javascript:void(0);" class="showChild" childid="'.$uid.'"><font color=black>' . $row["username"] . '</font></a>';
                    unset($row);
                }
            } else {
                //$html .= '<img src=' . C('IM_DOMAIN') . '/Home/images/native.gif>';
            }
        }
    }
    if(!empty($user) && !empty($user["parent_path"])) {
        $html .= '<img src=' . C('IM_DOMAIN') . '/Home/images/native.gif>';
    }
    $html .= '<font color=red>'.$user["username"].'</font>';
    $html .= '</div>';
    return $html;
}

//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// +查询IP地址
// +
// +param string $n
// +return string
//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function convertip($ip) {
    $ip1num = 0;
    $ip2num = 0;
    $ipAddr1 = "";
    $ipAddr2 = "";
    $dat_path = APP_PATH . '/Data/qqwry.dat';
    if (! preg_match ( "/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip )) {
        return 'IP Address Error';
    }
    if (! $fd = @fopen ( $dat_path, 'rb' )) {
        return 'IP date file not exists or access denied';
    }
    $ip = explode ( '.', $ip );
    $ipNum = $ip [0] * 16777216 + $ip [1] * 65536 + $ip [2] * 256 + $ip [3];
    $DataBegin = fread ( $fd, 4 );
    $DataEnd = fread ( $fd, 4 );
    $ipbegin = implode ( '', unpack ( 'L', $DataBegin ) );
    if ($ipbegin < 0)
        $ipbegin += pow ( 2, 32 );
    $ipend = implode ( '', unpack ( 'L', $DataEnd ) );
    if ($ipend < 0)
        $ipend += pow ( 2, 32 );
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
    $BeginNum = 0;
    $EndNum = $ipAllNum;
    while ( $ip1num > $ipNum || $ip2num < $ipNum ) {
        $Middle = intval ( ($EndNum + $BeginNum) / 2 );
        fseek ( $fd, $ipbegin + 7 * $Middle );
        $ipData1 = fread ( $fd, 4 );
        if (strlen ( $ipData1 ) < 4) {
            fclose ( $fd );
            return 'System Error';
        }
        $ip1num = implode ( '', unpack ( 'L', $ipData1 ) );
        if ($ip1num < 0)
            $ip1num += pow ( 2, 32 );

        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }
        $DataSeek = fread ( $fd, 3 );
        if (strlen ( $DataSeek ) < 3) {
            fclose ( $fd );
            return 'System Error';
        }
        $DataSeek = implode ( '', unpack ( 'L', $DataSeek . chr ( 0 ) ) );
        fseek ( $fd, $DataSeek );
        $ipData2 = fread ( $fd, 4 );
        if (strlen ( $ipData2 ) < 4) {
            fclose ( $fd );
            return 'System Error';
        }
        $ip2num = implode ( '', unpack ( 'L', $ipData2 ) );
        if ($ip2num < 0)
            $ip2num += pow ( 2, 32 );
        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose ( $fd );
                return 'Unknown';
            }
            $BeginNum = $Middle;
        }
    }
    $ipFlag = fread ( $fd, 1 );
    if ($ipFlag == chr ( 1 )) {
        $ipSeek = fread ( $fd, 3 );
        if (strlen ( $ipSeek ) < 3) {
            fclose ( $fd );
            return 'System Error';
        }
        $ipSeek = implode ( '', unpack ( 'L', $ipSeek . chr ( 0 ) ) );
        fseek ( $fd, $ipSeek );
        $ipFlag = fread ( $fd, 1 );
    }
    if ($ipFlag == chr ( 2 )) {
        $AddrSeek = fread ( $fd, 3 );
        if (strlen ( $AddrSeek ) < 3) {
            fclose ( $fd );
            return 'System Error';
        }
        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek2 = fread ( $fd, 3 );
            if (strlen ( $AddrSeek2 ) < 3) {
                fclose ( $fd );
                return 'System Error';
            }
            $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek2 );
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
        }
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr2 .= $char;
        $AddrSeek = implode ( '', unpack ( 'L', $AddrSeek . chr ( 0 ) ) );
        fseek ( $fd, $AddrSeek );
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr1 .= $char;
    } else {
        fseek ( $fd, - 1, SEEK_CUR );
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) )
            $ipAddr1 .= $char;
        $ipFlag = fread ( $fd, 1 );
        if ($ipFlag == chr ( 2 )) {
            $AddrSeek2 = fread ( $fd, 3 );
            if (strlen ( $AddrSeek2 ) < 3) {
                fclose ( $fd );
                return 'System Error';
            }
            $AddrSeek2 = implode ( '', unpack ( 'L', $AddrSeek2 . chr ( 0 ) ) );
            fseek ( $fd, $AddrSeek2 );
        } else {
            fseek ( $fd, - 1, SEEK_CUR );
        }
        while ( ($char = fread ( $fd, 1 )) != chr ( 0 ) ) {
            $ipAddr2 .= $char;
        }
    }
    fclose ( $fd );
    if (preg_match ( '/http/i', $ipAddr2 )) {
        $ipAddr2 = '';
    }
    $ipaddr = "$ipAddr1 $ipAddr2";
    $ipaddr = preg_replace ( '/CZ88.NET/is', '', $ipaddr );
    $ipaddr = preg_replace ( '/^s*/is', '', $ipaddr );
    $ipaddr = preg_replace ( '/s*$/is', '', $ipaddr );
    if (preg_match ( '/http/i', $ipaddr ) || $ipaddr == '') {
        $ipaddr = 'Unknown';
    }
    return iconv ( 'gbk', 'utf-8', $ipaddr );
}

function getNomallNumber($id){
    import("Class.XDeode");
    $_xDe=new \XDeode();
    $id = $_xDe->decode($id);
    $val = M("buy_record")->where(array("id"=>$id))->field("buy_number,buy_type_id")->find();
    if ($val["buy_type_id"] == 51) { // 51 id  表示为定单双
        $numb = array("0单5双", "1单4双", "2单3双", "3单2双", "4单1双", "5单0双");
        $buy_number = explode(" ", $val["buy_number"]);
        $str = '';
        foreach ($buy_number as $number) {
            $str .= $numb[$number] . " ";
        }
        $val["buy_number"] = trim($str);
    }
    return $val["buy_number"];
}

function getAddNumber($id){
    import("Class.XDeode");
    $_xDe=new \XDeode();
    $id = $_xDe->decode($id);
    $val = M("buy_add_record")->where(array("id"=>$id))->field("buy_number,buy_type_id")->find();
    if ($val["buy_type_id"] == 51) { // 51 id  表示为定单双
        $numb = array("0单5双", "1单4双", "2单3双", "3单2双", "4单1双", "5单0双");
        $buy_number = explode(" ", $val["buy_number"]);
        $str = '';
        foreach ($buy_number as $number) {
            $str .= $numb[$number] . " ";
        }
        $val["buy_number"] = trim($str);
    }
    return $val["buy_number"];
}

function getDisNomallNumber($id){
    $val = M("buy_record")->where(array("id"=>$id))->field("buy_number,buy_type_id")->find();
    if ($val["buy_type_id"] == 51) { // 51 id  表示为定单双
        $numb = array("0单5双", "1单4双", "2单3双", "3单2双", "4单1双", "5单0双");
        $buy_number = explode(" ", $val["buy_number"]);
        $str = '';
        foreach ($buy_number as $number) {
            $str .= $numb[$number] . " ";
        }
        $val["buy_number"] = trim($str);
    }
    return $val["buy_number"];
}

function getDisAddNumber($id){
    $val = M("buy_add_record")->where(array("id"=>$id))->field("buy_number,buy_type_id")->find();
    if ($val["buy_type_id"] == 51) { // 51 id  表示为定单双
        $numb = array("0单5双", "1单4双", "2单3双", "3单2双", "4单1双", "5单0双");
        $buy_number = explode(" ", $val["buy_number"]);
        $str = '';
        foreach ($buy_number as $number) {
            $str .= $numb[$number] . " ";
        }
        $val["buy_number"] = trim($str);
    }
    return $val["buy_number"];
}

//概率计算
function get_rand($proArr) {
    $result = '';

    //概率数组的总概率精度
    $proSum = array_sum($proArr);

    //概率数组循环
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(1, $proSum);
        if ($randNum <= $proCur) {
            $result = $key;
            break;
        } else {
            $proSum -= $proCur;
        }
    }
    unset ($proArr);

    return $result;
}

//查看会员在线人数
function getOnlinePerson($uid){
    import("Class.RedisObject");
    $redisObj = new \RedisObject();
    $userModel = M("user");
    $child = $userModel->field("id")->where("FIND_IN_SET(".$uid.",parent_path)")->select();
    $count = 0;
    foreach($child as $value){
        $keys = "dd_".$value["id"];
        if($redisObj->exists($keys)){
            $count++;
        } else {
            continue;
        }
    }
    return $count;
}

function trimall($str)
{
    $qian=array(" ","　","\t","\n","\r");
    $hou=array("","","","","");
    return str_replace($qian,$hou,$str);
}

function showShort($str,$len)
{
    $str = trimall($str);
    $tempstr = csubstr($str,0,$len);
    if ($str<>$tempstr)
    $tempstr .= "...";
    return $tempstr;
}


function csubstr($str,$start,$len)
{
    $strlen=mb_strlen($str,'utf-8');
    $clen=0;
    $tmpstr="";
    for($i=0;$i<$strlen;$i++,$clen++)
    {
        if ($clen>=$start+$len)
            break;
        if(ord(mb_substr($str,$i,1,'utf-8'))>0xa0)
        {
            if ($clen>=$start)
            $tmpstr.=mb_substr($str,$i,2,'utf-8');
            $i++;
        }
        else
        {
            if ($clen>=$start)
            $tmpstr.=mb_substr($str,$i,1,'utf-8');
        }
    }
    return $tmpstr;
}



        /////////////////////////////////////////////////////////////
                ////cms系统
        /////////////////////////////////////////////////////////////
        /******公共函数文件*******/
define('B_PIC', 1); // 图片
define('B_TOP', 2); // 头条 (置顶)
define('B_REC', 4); // 推荐
define('B_SREC', 8); // 特荐
define('B_SLIDE', 16); // 幻灯
define('B_JUMP', 32); // 跳转
define('B_OTHER', 64); // 其他

//order_status
define('OS_UNCONFIRMED', 0); // 未确认
define('OS_CONFIRMED', 1); // 已确认
define('OS_CANCELED', 2); // 已取消
define('OS_INVALID', 3); // 无效
define('OS_RETURNED', 4); // 退货

//distribution_status
define('DS_UNSHIPPED', 0); // 未发货
define('DS_SHIPPED', 1); // 已发货
define('DS_RECEIVED', 2); // 已收货
define('DS_PREPARING', 3); // 备货中

//pay_status
define('PS_UNPAYED', 0); // 未付款
define('PS_PAYING', 1); // 付款中
define('PS_PAYED', 2); // 已付款

//magic_quotes_gpc如果开启,去掉转义，不然加上TP入库时的转义，会出现两次反斜线转义
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
        array_map('stripslashes_deep', $value) :
        stripslashes($value); //去掉由addslashes添加的转义
        return $value;
    }
    $_POST    = array_map('stripslashes_deep', $_POST);
    $_GET     = array_map('stripslashes_deep', $_GET);
    $_COOKIE  = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

function p($array)
{

    //dump(数组参数,是否显示1/0,显示标签('<pre>'),模式[0为print_r])
    dump($array, 1, '', 0);
}

/**
 * 删除静态缓存文件
 * @param string $str 缓存路径
 * @param boolean $isdir 是否是目录
 * @param string $rules 缓存规则名
 * @return mixed
 */
function del_cache_html($str, $isdir = false, $rules = '')
{
    //为空，且不是目录
    $delflag = true;
    if (empty($str) && !$isdir) {
        return;
    }
    $str_array = array();

    //更新静态缓存
    $html_cache_rules = get_meta_value('HTML_CACHE_RULES_COMMON');
    if (get_meta_value('HOME_HTML_CACHE_ON')) {
        $str_array[] = HTML_PATH . 'Home/' . $str;
    }

    if (get_meta_value('MOBILE_HTML_CACHE_ON')) {
        $str_array[] = HTML_PATH . 'Mobile/' . $str;
    }

    if (!empty($rules) && !isset($html_cache_rules[$rules])) {
        $delflag = false; //指定规则，如不存在则不用清除
    } else {
        $delflag = true;
    }

    if ($delflag) {
        foreach ($str_array as $v) {
            if ($isdir && is_dir($v)) {
                del_dir_file($v, false);
            } else {
                $list = glob($v . '*');
                for ($i = 0; $i < count($list); $i++) {
                    if (is_file($list[$i])) {
                        unlink($list[$i]);
                    }
                }
            }

        }

    }

}

/**
 * 取出所有分类
 * @param string $status 显示部份(0|1|2)， 0显示全部(默认),1显示不隐藏的,2显示type为0(类型为内部模型非外链)全部
 * @param boolean $update 更新缓存(0|1)， 默认不更新[0]
 * @return array
 */
function get_category($status = 0, $update = 0)
{
        if ($status == 1) {
            $cate_arr = D('CategoryView')->nofield('content')->where(array('category.status' => 1))->order('category.sort,category.id')->select();
        } else if ($status == 2) {
//后台栏目专用
            $cate_arr = D('CategoryView')->nofield('content')->where(array('category.type' => 0))->order('category.sort,category.id')->select();
        } else {
            $cate_arr = D('CategoryView')->nofield('content')->order('category.sort,category.id')->select();
        }
        if (!isset($cate_arr)) {
            $cate_arr = array();
        }

        //S(缓存名称,缓存值,缓存有效时间[秒]);
        //S($cate_sname, $cate_arr, 48 * 60 * 60);
       // F($cate_sname, $cate_arr);
    return $cate_arr;
}

/**
 * 获取栏目或文档网址--[Home|Mobile]
 * @param array $cate 栏目数组
 * @param integer $id 文档id
 * @param boolean $jumpflag 是否跳转
 * @param string $jumpurl 跳转网址
 * @return string
 */
function get_url($cate, $id = 0, $params = [], $jumpflag = false, $jumpurl = '')
{
    $url = '';
    //如果是跳转，直接就返回跳转网址
    if ($jumpflag && !empty($jumpurl)) {
        return $jumpurl;
    }
    if (empty($cate)) {
        return $url;
    }

    if (in_array(MODULE_NAME, array('Home'))) {
        $module = '';
    } else {
        $module = '/';
    }
    $ename = $cate['ename'];
    if ($cate['type'] == 1) {
        $firstChar = substr($ename, 0, 1);
        if ($firstChar == '@') {
            $ename     = ucfirst(substr($ename, 1)); //
            $firstChar = substr($ename, 0, 1);
            $url       = ($firstChar != '/') ? U($module . $ename, '') : U('' . $ename, '');
        } else {
            $url = $ename; //http://
        }

    } else {
        //开启路由
        if (C('URL_ROUTER_ON') == true) {
            $url = $id > 0 ? U($module . '' . $ename . '/' . $id, '') : U('/' . $ename, '', '');
        } else {
            $url = U($module . 'List/index', array('pid' => $cate['pid'], 'cid' => $cate['id']));
            if ($id > 0) {
                $url = U($module . 'Show/index', array('cid' => $cate['cid'], 'id' => $cate['id']));
            }

        }

    }

    return $url;

}

/**
 * 获取文档内容页网址--[Home|Mobile]
 * @param integer $id 文档id
 * @param integer $cid 栏目id
 * @param string $ename 栏目英文名称
 * @param boolean $jumpflag 是否跳转
 * @param string $jumpurl 跳转网址
 * @return string
 */
function get_content_url($id, $cid, $ename, $jumpflag = false, $jumpurl = '')
{
    $url = '';
    //如果是跳转，直接就返回跳转网址
    if ($jumpflag && !empty($jumpurl)) {
        return $jumpurl;
    }
    if (empty($id) || empty($cid) || empty($ename)) {
        return $url;
    }

    //修正不能跨模块，判断当前MODULE_NAME
    if (in_array(MODULE_NAME, array('Home', 'Mobile'))) {
        $module = '';
    } else {
        $module = '/'; //'Home/';
    }

    //开启路由
    if (C('URL_ROUTER_ON') == true) {
        $url = $id > 0 ? U($module . '' . $ename . '/' . $id, '') : U('/' . $ename, '', '');
    } else {
        $url = U($module . 'Show/index', array('cid' => $cid, 'id' => $id));
    }

    return $url;
}

/**
 * 当前位置
 * @param integer $typeid 栏目id
 * @param string $sname 指定子名称
 * @param string $surl 指定子网址
 * @param boolean $ismobile 是否手机版
 * @param string $delimiter 分割符
 * @return string
 */
function get_position($typeid = 0, $sname = '', $surl = '', $ismobile = false, $delimiter = '&gt;&gt;')
{
    if ($delimiter == '') {
        $delimiter = '&gt;&gt;';
    }
    //$url      = $ismobile ? U(MODULE_NAME . '/Index/index/') : C('CFG_WEBURL');
    $position = '<a href="' . $url . '">首页</a>';

    //Parents of Category
    if (!empty($typeid)) {
        $cate       = get_category(0); //ALL
        $getParents = \Common\Lib\Category::getParents($cate, $typeid);
        if (is_array($getParents)) {
            foreach ($getParents as $v) {
                $position .= $delimiter . '<a href="' . get_url($v) . '">' . $v['name'] . '</a>';
            }
        }
    }

    if (!empty($sname)) {
        if (empty($surl)) {
            $position .= $delimiter . $sname;
        } else {
            $position .= $delimiter . '<a href="' . $surl . '">' . $sname . '</a>';
        }
    }

    return $position;
}

/**
 * 获取联动(字典)项的值
 * @param string $group 联动组名
 * @param integer $value 联动值
 * @return string
 */
function get_item_value($group, $value = 0)
{
    //return $value.'--<br>';
    ${'item_' . $group} = get_item($group);
    if (isset(${'item_' . $group}[$value])) {
        return ${'item_' . $group}[$value];
    } else {
        return "保密";
    }
}

/**
 * 获取对应组的联动列表
 * @param string $group 联动组名
 * @param integer $value 联动值
 * @return array
 */
function get_item($group = 'animal', $update = 0)
{

    //S方法的缓存名都带's'
    $itme_arr = S('sItem_' . $group);
    if ($update || !$itme_arr) {
        $itme_arr = array();
        $temp     = M('iteminfo')->where(array('group' => $group))->order('sort,id')->select();
        foreach ($temp as $key => $v) {
            $itme_arr[$v['value']] = $v['name'];

        }

        //S(缓存名称,缓存值,缓存有效时间[秒]);
        S('sItem_' . $group, $itme_arr, 48 * 60 * 60);
    }
    return $itme_arr;
}

/**
 * 获取自由块内容
 * @param string $name 自由块名
 * @param boolean $update 是否更新
 * @return array
 */
function get_block($name, $update = 0)
{
    $block_sname = 'fBlock/' . md5($name);
    $_block      = F($block_sname);
    if ($update || !$_block) {

        $_block = M('block')->where(array('name' => "$name"))->find();
        if (!isset($_block)) {
            $_block = null;
            if (!$update) {
                return null;
            }

        }
        //F(缓存名称,缓存值);
        F($block_sname, $_block);
    }
    return $_block;
}

/**
 * 获取点击次数(同时点击数增加1)
 * @param integer $id 文档id
 * @param string $tablename 表名
 * @return integer
 */
function get_click($id, $tablename)
{

    $id = intval($id);
    if (empty($id) || empty($tablename)) {
        return '--';
    }
    $num = M($tablename)->where(array('id' => $id))->getField('click');
    M($tablename)->where(array('id' => $id))->setInc('click');
    return "$num";
}

/**
 * 获取上传最大值(字节数), KB转字节
 * @param integer $size 默认大小值
 * @param string $cfg 配置项值
 * @return integer
 */
function get_upload_maxsize($size = 2048, $cfg = 'CFG_UPLOAD_MAXSIZE')
{
    $maxsize = get_cfg_value($cfg);
    if (empty($maxsize)) {
        $maxsize = $size;
    }
    return $maxsize * 1024;
}

/**
 * 广告
 * @param integer $id 广告id
 * @param boolean $flag 是否js方式输出(0|1), 默认html
 * @return string
 */
function get_abc($id, $flag = 0)
{

    $id = intval($id);
    if (empty($id)) {
        return '';
    }
    $setting = '';
    $abc     = M('abc')->find($id);
    if ($abc) {
        $where = array('aid' => $id,
            'status'             => 1,
            'starttime'          => array('lt', time()),
            'endtime'            => array('gt', time()),
        );
        $detail = M('abcDetail')->where($where)->order('sort')->limit($abc['num'])->select();
        if (!$detail) {
            $detail = array();
        }

        $setting = $abc['setting'];
        $pattern = '/<loop>(.*?)<\/loop>/is';
        preg_match_all($pattern, $setting, $mat);

        if (!empty($mat[1])) {
            $rep = array();
            foreach ($mat[1] as $k => $v) {
                $rep[$k] = '';
                foreach ($detail as $k2 => $v2) {
                    $search = array('{$id}', '{$title}', '{$content}', '{$url}', '{$sort}',
                        '{$width}', '{$height}', '{$autoindex}', '{$autoindex+1}', '{$autoindex+2}');
                    $replace = array($v2['id'], $v2['title'], $v2['content'], $v2['url'], $v2['sort'],
                        $abc['width'], $abc['height'], $k2, $k2 + 1, $k2 + 2);

                    $rep[$k] .= str_replace($search, $replace, $v);
                }
            }
            $setting = str_replace($mat[0], $rep, $setting);
        }

    }

    //js输出
    if ($flag) {
        $setting = 'document.write("' . str_replace(array('"', "\r\n"), array('\"', ''), $setting) . '");';
    }
    return $setting;
}

/**
 * 取出存档分类
 * @param integer $modelid 模型id
 * @param integer $update 更新缓存(0|1|2)， 默认0不更新,1更新，2是删除
 * @return array
 */
function get_datelist($modelid = 1, $update = 0)
{
//
    $modelid = intval($modelid);
    $arr     = array();
    //为[0]或page模型[2]
    if ($modelid == 0 || $modelid == 2) {
        return $arr;
    }
    $format = '%Y-%m';
    $sname  = 'fDateList_' . $modelid;
    //删除，直抒返回
    if ($update == 2) {
        F($sname, null);
        return $arr;
    }
    $arr = F($sname);
    if ($update || !$arr) {
        $tablename = M('model')->where(array('id' => $modelid))->getField('tablename');
        if ($tablename) {
            $arr = M($tablename)->field("count(*) as arc_num, FROM_UNIXTIME(publishtime,'%Y') as arc_year, FROM_UNIXTIME(publishtime,'%m') as arc_month")->group("FROM_UNIXTIME(publishtime,'" . $format . "')")->order('publishtime desc')->select();
        } else {
            $arr = array();
        }

        if (!isset($arr)) {
            $arr = array();
        }

        F($sname, $arr);
    }
    return $arr;
}

/**
 * 生成省市联动js
 * @return boolean
 */
function get_js_city()
{

    $str = <<<str
function setcity() {
    var SelP=document.getElementsByName(arguments[0])[0];
    var SelC=document.getElementsByName(arguments[1])[0];
    var DefP=arguments[2];
    var DefC=arguments[3];
str;

    $province = M('area')->where(array('pid' => 0))->order('sort,id')->select();
    //Province
    $pcount = count($province) - 1; //$key 是从0开始的
    $str .= "var provinceOptions = new Array(";
    $str .= '"请选择省份",0';
    foreach ($province as $k => $v) {
        $str .= ',"' . $v['sname'] . '",' . $v['id'] . '';
    }
    $str .= " );\n";

    $str .= <<<str
    SelP.options.length = 0;
    for(var i = 0; i < provinceOptions.length/2; i++) {
        SelP.options[i]=new Option(provinceOptions[i*2],provinceOptions[i*2+1]);
        if(SelP.options[i].value==DefP) {
            SelP.selectedIndex = i;
        }
    }

    SelP.onchange = function(){
        switch (SelP.value) {
str;

    foreach ($province as $v) {
        $str .= 'case "' . $v['id'] . '" :' . "\n";
        //$str .= 'case "'.$v['sname'].'" :'."\n";
        $str .= "var cityOptions = new Array(";
        $city  = M('area')->where(array('pid' => $v['id']))->order('sort,id')->select();
        $count = count($city) - 1; //$key 是从0开始的
        foreach ($city as $key => $value) {
            $str .= '"' . $value['sname'] . '",' . $value['id'] . '';
            if ($key != $count) {
                $str .= ","; //不为最后一个元素，就加上","
            }
        }

        $str .= " );\n";
        $str .= " break;\n";
    }

    $str .= <<<str
        default:
            var cityOptions = new Array("");
            break;
        }

        SelC.options.length = 0;
        for(var i = 0; i < cityOptions.length/2; i++) {
            SelC.options[i]=new Option(cityOptions[i*2],cityOptions[i*2+1]);
            if (SelC.options[i].value==DefC) {
                SelC.selectedIndex = i;
            }
        }
    }

    if (DefP) {
        if(SelP.fireEvent) {
        SelP.fireEvent('onchange');
        //alert('ok');
        }else {
            SelP.onchange();
        }
    }

}
str;

    //echo $str;
    if (file_put_contents('./Data/resource/js/city.js', $str)) {
        return true;
    } else {
        return false;
    }

}

/**
 * 获取文件目录列表
 * @param string $pathname 路径
 * @param integer $fileFlag 文件列表 0所有文件列表,1只读文件夹,2是只读文件(不包含文件夹)
 * @param string $pathname 路径
 * @return array
 */
function get_file_folder_List($pathname, $fileFlag = 0, $pattern = '*')
{
    $fileArray = array();
    $pathname  = rtrim($pathname, '/') . '/';
    $list      = glob($pathname . $pattern);
    foreach ($list as $i => $file) {
        switch ($fileFlag) {
            case 0:
                $fileArray[] = basename($file);
                break;
            case 1:
                if (is_dir($file)) {
                    $fileArray[] = basename($file);
                }
                break;

            case 2:
                if (is_file($file)) {
                    $fileArray[] = basename($file);
                }
                break;

            default:
                break;
        }
    }

    if (empty($fileArray)) {
        $fileArray = null;
    }

    return $fileArray;
}

/**
 * 循环删除目录和文件函数
 * @param string $dirName 路径
 * @param boolean $fileFlag 是否删除目录
 * @return void
 */
function del_dir_file($dirName, $bFlag = false)
{
    if ($handle = opendir("$dirName")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dirName/$item")) {
                    del_dir_file("$dirName/$item", $bFlag);
                } else {
                    unlink("$dirName/$item");
                }
            }
        }
        closedir($handle);
        if ($bFlag) {
            rmdir($dirName);
        }

    }
}

/**
 * 计算年龄
 * @param string $birth 日期 如1981-1-1
 * @return integer
 */
function birthday2age($birth)
{
    list($byear, $bmonth, $bday) = explode('-', $birth);
    $age                         = date('Y') - $byear - 1;
    $tmonth                      = date('n');
    $tday                        = date('j');
    if ($tmonth > $bmonth || $tmonth == $bmonth && $tday > $bday) {
        $age++;
    }

    return $age;
}

/**
 * 替换字符串为指定的字符
 * @param string $str 字符串
 * @param integer $num 替换个数
 * @param string $sp 替换后的字符
 * @return string
 */
function str2symbol($str, $num = 1, $sp = '*')
{
    if ($str == '' || $num <= 0) {
        return $str;
    }
    $num    = mb_strlen($str, 'utf-8') > $num ? $num : mb_strlen($str, 'utf-8');
    $newstr = '';
    for ($i = 0; $i < $num; $i++) {
        $newstr .= '*';
    }
    $newstr .= mb_substr($str, $num, mb_strlen($str, 'utf-8') - $num, 'utf-8'); //substr中国会乱码

    return $newstr;

}

/**
 * 截取指定长度的字符串
 * @param string $str 字符串
 * @param integer $num 截取长度
 * @param boolean $flag 是否显示省略符
 * @param string $sp 省略符
 * @return string
 */
function str2sub($str, $num, $flag = 0, $sp = '...')
{
    if ($str == '' || $num <= 0) {
        return $str;
    }
    $strlen = mb_strlen($str, 'utf-8');
    $newstr = '';
    $newstr .= mb_substr($str, 0, $num, 'utf-8'); //substr中国会乱码
    if ($num < $strlen && $flag) {
        $newstr .= $sp;
    }

    return $newstr;
}

/**
 * 字符串过滤
 * @param string $str 字符串
 * @param string $delimiter 分割符
 * @param boolean $flag 是否检测成员为数字
 * @return string
 */
function string2filter($str, $delimiter = ',', $flag = false)
{
    if (empty($str)) {
        return '';
    }

    $tmp_arr  = array_filter(explode($delimiter, $str)); //去除空数组'',0,再使用sort()重建索引
    $tmp_arr2 = array();

    //检验是不是数字
    if ($flag) {
        foreach ($tmp_arr as $v) {
            if (is_numeric($v)) {
                $tmp_arr2[] = $v;
            }
        }
    } else {
        $tmp_arr2 = $tmp_arr;
    }

    return implode($delimiter, $tmp_arr2);

}

//flag相加,返回数值，用于查询
function flag2sum($str, $delimiter = ',')
{
    if (empty($str)) {
        return 0;
    }
    $tmp_arr = array_filter(explode($delimiter, $str)); //去除空数组'',0,再使用sort()重建索引
    if (empty($tmp_arr)) {
        return 0;
    }

    $arr = array('a' => B_PIC, 'b' => B_TOP, 'c' => B_REC, 'd' => B_SREC, 'e' => B_SLIDE, 'f' => B_JUMP, 'g' => B_OTHER);
    $sum = 0;
    foreach ($arr as $k => $v) {
        if (in_array($k, $tmp_arr)) {
            $sum += $v;
        }
    }

    return $sum;

}

function check_badword($content)
{
    //定义处理违法关键字的方法
    $badword = C('CFG_BADWORD'); //定义敏感词

    if (empty($badword)) {
        return false;
    }
    $keyword = explode('|', $badword);
    $m       = 0;
    for ($i = 0; $i < count($keyword); $i++) {
        //根据数组元素数量执行for循环
        //应用substr_count检测文章的标题和内容中是否包含敏感词
        if (substr_count($content, $keyword[$i]) > 0) {
            //$m ++;
            return true;
        }
    }
    //return $m;              //返回变量值，根据变量值判断是否存在敏感词
    return false;
}

/**
 * 对用户的密码进行加密
 * @param $password
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function get_password($password, $encrypt = '')
{
    $pwd             = array();
    $pwd['encrypt']  = $encrypt ? $encrypt : get_randomstr();
    $pwd['password'] = md5(md5(trim($password)) . $pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 对用户的已经加密的密码进行二次加密--new
 * @param $password_md5 已经加密的密码
 * @param $encrypt //传入加密串，在修改密码时做认证
 * @return array/password
 */
function get_password_md5($password_md5, $encrypt = '') {
    $pwd             = array();
    $pwd['encrypt']  = $encrypt ? $encrypt : get_randomstr();
    $pwd['password'] = md5($password_md5 . $pwd['encrypt']);
    return $encrypt ? $pwd['password'] : $pwd;
}

/**
 * 生成随机字符串
 * @param string $lenth 长度
 * @return string 字符串
 */
function get_randomstr($lenth = 6)
{
    return get_random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
}

/**
 * 产生随机字符串
 *
 * @param    int        $length  输出长度
 * @param    string     $chars   可选的 ，默认为 0123456789
 * @return   string     字符串
 */
function get_random($length, $chars = '0123456789')
{
    $hash = '';
    $max  = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

/**
 * 得到指定cookie的值
 *
 * @param string $name
 */
//function get_cookie($name, $key = '@^%$y5fbl') {
function get_cookie($name, $key = '')
{

    if (!isset($_COOKIE[$name])) {
        return null;
    }
    $key = empty($key) ? C('CFG_COOKIE_ENCODE') : $key;

    $value = $_COOKIE[$name];
    $key   = md5($key);
    $sc    = new \Common\Lib\SysCrypt($key);
    $value = $sc->php_decrypt($value);
    return unserialize($value);
}

/**
 * 设置cookie
 *
 * @param array $args
 * @return boolean
 */
//使用时修改密钥$key 涉及金额结算请重新设计cookie存储格式
//function set_cookie($args , $key = '@^%$y5fbl') {
function set_cookie($args, $key = '')
{
    $key = empty($key) ? C('CFG_COOKIE_ENCODE') : $key;

    $name   = $args['name'];
    $expire = isset($args['expire']) ? $args['expire'] : null;
    $path   = isset($args['path']) ? $args['path'] : '/';
    $domain = isset($args['domain']) ? $args['domain'] : null;
    $secure = isset($args['secure']) ? $args['secure'] : 0;
    $value  = serialize($args['value']);

    $key   = md5($key);
    $sc    = new \Common\Lib\SysCrypt($key);
    $value = $sc->php_encrypt($value);
    //setcookie($cookieName ,$cookie, time()+3600,'/','',false);
    return setcookie($name, $value, $expire, $path, $domain, $secure); //失效时间   0关闭浏览器即失效
}

/**
 * 删除cookie
 * @param array $args
 * @return boolean
 */
function del_cookie($args)
{
    $name   = $args['name'];
    $domain = isset($args['domain']) ? $args['domain'] : null;
    return isset($_COOKIE[$name]) ? setcookie($name, '', time() - 86400, '/', $domain) : true;
}

/**
 * 获取指定大小的头像[必须系统有的]
 * @param string $str 头像地址
 * @param integer $size 尺寸长宽
 * @param boolean $rnd 是否显示随机码
 * @return string
 */
function get_avatar($str, $size = 160, $rnd = false)
{

    $ext = 'jpg';
    if (!empty($str)) {
        $ext = explode('.', $str);
        $ext = end($ext);
    }

    if (empty($ext) || !in_array(strtolower($ext), array('jpg', 'gif', 'png', 'jpeg'))) {
        $str = '';
    }
    if (empty($str)) {
        $str = __ROOT__ . '/avatar/system/0.jpg';
        $ext = 'jpg';
        if ($size > 160 || $size < 30) {
            $size = 160;
        }
    }

    if ($size == 0) {
        return $str;
    }
    $rndstr = $rnd ? '?random=' . time() : '';
    return $str . '!' . $size . 'X' . $size . '.' . $ext . $rndstr;
}

/**
 * 获取指定长宽的图片[尺寸见后台设置]
 * @param string $str 图片地址
 * @param integer $width 长度
 * @param integer $height 高度
 * @param boolean $rnd 是否显示随机码
 * @return string
 */
function get_picture($str, $width = 0, $height = 0, $rnd = false)
{

    //$ext = end(explode('.', $str));
    $ext      = 'jpg'; //原文件后缀
    $ext_dest = 'jpg'; //生成缩略图格式
    $height   = $height == 0 ? '' : $height;
    if (!empty($str)) {
        $str = preg_replace('/!(\d+)X(\d+)\.' . $ext_dest . '$/i', '', $str); //清除缩略图的!200X200.jpg后缀

        $ext = explode('.', $str);
        $ext = end($ext);
    }
    if (empty($ext) || !in_array(strtolower($ext), array('jpg', 'gif', 'png', 'jpeg'))) {
        $str = '';
    }
    if (empty($str)) {
        $str      = __ROOT__ . '/uploads/system/nopic.png';
        $ext      = 'png';
        $ext_dest = 'png';
        $width    = 0;
    }
    if ($width == 0) {
        return $str;
    }

    $rndstr = $rnd ? '?random=' . time() : '';
    return $str . '!' . $width . 'X' . $height . '.' . $ext_dest . $rndstr;
}

/**
 * 功能：计算文件大小
 * @param int $bytes
 * @return string 转换后的字符串
 */
function get_byte($bytes)
{
    if (empty($bytes)) {
        return '--';
    }
    $sizetext = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $sizetext[$i];
}

/**
 * 获取拼音信息
 * @param     string  $str  字符串
 * @param     int  $ishead  是否为首字母
 * @param     int  $isclose  解析后是否释放资源
 * @param     int  $lang  语言
 * @return    string
 * 用法：$data['EnglishName'] = $this->get_pinyin(iconv('utf-8','gbk//ignore',$utfstr),0);
 */
function get_pinyin($str, $ishead = 0, $isclose = 1, $lang = 'zh-cn')
{
    //global $pinyins;
    $pinyins = array();
    $restr   = '';
    $str     = trim($str);
    $slen    = strlen($str);
    //$str=iconv("UTF-8","gb2312",$str);
    //echo $str;
    if ($slen < 2) {
        return $str;
    }
    $file = './Data/pinyin-' . $lang . '.dat';
    if (!file_exists($file)) {
        $file = './Data/pinyin-zh-cn.dat';
    }
    if (count($pinyins) == 0) {
        $fp = fopen($file, 'r');
        if (false == $fp) {
            return '';
        }
        while (!feof($fp)) {
            $line                         = trim(fgets($fp));
            $pinyins[$line[0] . $line[1]] = substr($line, 3, strlen($line) - 3);
        }
        fclose($fp);
    }

    for ($i = 0; $i < $slen; $i++) {
        if (ord($str[$i]) > 0x80) {
            $c = $str[$i] . $str[$i + 1];
            $i++;
            if (isset($pinyins[$c])) {
                if ($ishead == 0) {
                    $restr .= $pinyins[$c];
                } else {
                    $restr .= $pinyins[$c][0];
                }
            } else {
                $restr .= "x"; //$restr .= "_";
            }
        } else if (preg_match("/[a-z0-9]/i", $str[$i])) {
            $restr .= $str[$i];
        } else {
            $restr .= "x"; //$restr .= "_";
        }
    }
    if ($isclose == 0) {
        unset($pinyins);
    }
    return $restr;
}

/*
 * 获取模板地址
 * @param string $tpl 模板文件名
 * @param string $style 样式
 * @return string
 */
function get_tpl($tpl = '', $style = '')
{
    $tplPath = './Public/' . MODULE_NAME . '/';
    $tplPath .= empty($style) ? C('CFG_THEMESTYLE') . '/' : $style . '/';
    if (trim($tpl) == '') {
        $tplPath .= CONTROLLER_NAME . C('TMPL_FILE_DEPR') . ACTION_NAME . C('TMPL_TEMPLATE_SUFFIX');
    } elseif (strpos($tpl, '.') > 0) {
        $tplPath .= $tpl;
    } else {
        $tplPath .= $tpl . C('TMPL_TEMPLATE_SUFFIX');
    }
    return $tplPath;
}

/**
 * 返回数据元值表(Key-Value)
 * @param string|integer $key 标识名
 * @return mixed
 */
function get_meta_value($key)
{
    $sname = 'config/meta';
    if ($key == '') {
        return '';
    }
    $array = F($sname);
    if (!$array) {
        $data  = M('meta')->field('name,value')->select();
        $array = array();
        if ($data) {
            foreach ($data as $value) {
                $array[$value['name']] = $value['value'];
            }
        }

        //静态缓存规则
        $html_cache_rules = array();
        if (isset($array['HTML_CACHE_INDEX_ON']) && $array['HTML_CACHE_INDEX_ON'] == 1) {
            $html_cache_rules['index:index'] = array('{:module}/Index_{:action}', intval($array['HTML_CACHE_INDEX_TIME']));
        }
        if (isset($array['HTML_CACHE_LIST_ON']) && $array['HTML_CACHE_LIST_ON'] == 1) {
            $html_cache_rules['list:index'] = array('{:module}/List/{:action}_{e}{cid|intval}_{p|intval}', intval($array['HTML_CACHE_LIST_TIME']));
        }
        if (isset($array['HTML_CACHE_SHOW_ON']) && $array['HTML_CACHE_SHOW_ON'] == 1) {
            $html_cache_rules['show:index'] = array('{:module}/Show/{:action}_{e}{cid|intval}_{id|intval}', intval($array['HTML_CACHE_SHOW_TIME']));
        }
        if (isset($array['HTML_CACHE_SPECIAL_ON']) && $array['HTML_CACHE_SPECIAL_ON'] == 1) {
            $html_cache_rules['special:index'] = array('{:module}/Special/{:action}_{cid|intval}_{p|intval}', intval($array['HTML_CACHE_SPECIAL_TIME'])); //首页
            $html_cache_rules['special:shows'] = array('{:module}/Special/{:action}_{id|intval}', intval($array['HTML_CACHE_SPECIAL_TIME'])); //页面
        }
        $array['HTML_CACHE_RULES_COMMON'] = $html_cache_rules; //公共静态缓存规则

        //路由
        $tmp = explode("\n", str_replace(array("\r\n", "\t"), array("\n", ""), trim($array['HOME_URL_ROUTE_RULES'], "\r\n")));

        $url_route_rules = array();
        foreach ($tmp as $v) {
            $temparr = explode('=>', $v);
            if (empty($temparr[0]) || empty($temparr[1])) {
                continue;
            }
            $url_route_rules[$temparr[0]] = $temparr[1];
        }
        $array['HOME_URL_ROUTE_RULES'] = $url_route_rules; //公共静态缓存规则

        F($sname, $array);
    }

    $value = isset($array[$key]) ? $array[$key] : '';
    return $value;

}

/**
 * 返回配置项数组或对应值(快速缓存)
 * @param string|integer $key 标识名,标识为空则返回所有配置项数组
 * @param string|integer $name 缓存名称
 * @return mixed
 */
function get_cfg_value($key = '', $name = 'site')
{
    if (empty($name)) {
        return array();
    }
    $data  = M('bc_config')->field('name,value,typeid')->select();

    $array = array();
    if ($data) {
        foreach ($data as $value) {
            $array[$value['name']] = $value['value'];
        }
    }
    if ($key == '') {
        return $array;
    } else {
        $value = isset($array[$key]) ? $array[$key] : '';
        return $value;
    }

}

/**
 * 获取文件storage访问地址(SAE)
 * @param string $domain 域名名称
 * @param string $filename 文件名称(路径)
 * @return string
 */
function get_sae_storage_url($domain = 'uploads', $filename = '')
{
    if (empty($domain)) {
        return '';
    }

    static $_storage = array();
    $name            = $domain . ':' . $filename;
    if (isset($_storage[$name])) {
        return $_storage[$name];
    }
    $storage         = new \SaeStorage();
    $file_url        = $storage->getUrl($domain, $filename);
    $_storage[$name] = $filename;
    return $file_url;
}

/**
 * 返回从根目录开始的地址
 * @param string $path 子目录地址
 * @param boolean $domain 是否显示域名
 * @param string $path_root 网站根目录地址
 * @return mixed
 */
function get_url_path($path, $domain = false, $path_root = __ROOT__)
{

    $baseurl = ''; //域名地址
    if ($domain) {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $baseurl = 'http://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseurl = rtrim("http://" . $_SERVER['SERVER_NAME'], '/');
        }

    }

    $path_sub = explode('/', $path);

    if ($path_sub[0] == '') {
        $baseurl = $baseurl . implode('/', $path_sub);
    } elseif (empty($path_root)) {
        foreach ($path_sub as $k => $v) {
            if ($v == '.' || $v == '..') {
                unset($path_sub[$k]);
            }
        }
        $baseurl .= '/' . implode('/', $path_sub);
    } else {
        $path_root_tmp   = explode('/', $path_root);
        $path_root_count = count($path_root_tmp);
        foreach ($path_sub as $k => $v) {
            if ($v == '.') {
                unset($path_sub[$k]);
            }
            if ($v == '..') {
                if ($path_root_count > 0) {
                    unset($path_root_tmp[$path_root_count - 1]);
                    --$path_root_count;
                }
                unset($path_sub[$k]);
            }
        }
        $baseurl .= implode('/', $path_root_tmp) . '/' . implode('/', $path_sub);
    }
    $baseurl = rtrim($baseurl, '/') . '/';
    return $baseurl;
}

/**
 * 图片字符串 转图片数组
 */
function get_picture_array($str_pictureurls)
{
    $pictureurls_arr = empty($str_pictureurls) ? array() : explode('|||', $str_pictureurls);

    $pictureurls = array();
    foreach ($pictureurls_arr as $v) {
        $temp_arr = explode('$$$', $v);
        if (!empty($temp_arr[0])) {
            $pictureurls[] = array(
                'url' => $temp_arr[0],
                'alt' => $temp_arr[1],
            );
        }
    }
    return $pictureurls;
}

/**
 * 检测验证码
 */
function check_verify($code, $id = 1)
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**goto mobile*/
function go_mobile()
{
    $mobileAuto = C('CFG_MOBILE_AUTO');
    if ($mobileAuto == 1) {
        $wap2web = I('wap2web', 0, 'intval'); //手机访问电脑版
        $agent   = $_SERVER['HTTP_USER_AGENT'];
        if ($wap2web != 1) {
            if (strpos($agent, "comFront") || strpos($agent, "iPhone") || strpos($agent, "MIDP-2.0") || strpos($agent, "Opera Mini") || strpos($agent, "UCWEB") || strpos($agent, "Android") || strpos($agent, "Windows Phone") || strpos($agent, "Windows CE") || strpos($agent, "SymbianOS")) {
                header('Location:' . U('Mobile/Index/index') . '');
            }
        }
    }

}

/**
 * 转换网址
 * @param string $weburl 网址或者U方法的参数
 * @param boolean $rnd 是否添加随机数
 * @param boolean $flag 是否转换index.php
 * @return string
 */
function go_link($weburl = 'http://www.xyhcms.com/', $rnd = 0, $flag = 1)
{
    if (strpos($weburl, 'http://') === 0 || strpos($weburl, 'https://') === 0 || strpos($weburl, 'ftp://') === 0) {
        $weburl = U(C('DEFAULT_MODULE') . '/Go/link', array('url' => base64_encode($weburl)));
    } else {
        $weburl = U($weburl);
    }
    if ($flag) {
        $search  = $_SERVER['SCRIPT_NAME']; //$_SERVER['PHP_SELF'];
        $replace = rtrim(dirname($search), "\\/") . '/index.php';
        $weburl  = str_replace($search, $replace, $weburl);
    }
    //随机数
    if ($rnd) {
        $weburl .= '#' . rand(1000, time());
    }

    return $weburl;
}

/**
 * D2是D方法的扩展20140919
 * D2函数用于实例化Model 格式 项目://分组/模块
 * @param string $name Model资源地址
 * @param string $tableName 数据表名
 * @param string $layer 业务层名称
 * @return Model
 */
function D2($name = '', $tableName = '', $layer = '')
{
    if (empty($name)) {
        return new \Think\Model;
    }

    static $_model = array();
    $layer         = $layer ?: C('DEFAULT_M_LAYER');
    if (isset($_model[$name . $layer . '\\' . $tableName])) {
        return $_model[$name . $layer . '\\' . $tableName];
    }

    $class = parse_res_name($name, $layer);
    if (class_exists($class)) {
        //$model      =   new $class(basename($name));
        $model = empty($tableName) ? new $class(basename($name)) : new $class(basename($tableName), $tableName);
    } elseif (false === strpos($name, '/')) {
        // 自动加载公共模块下面的模型
        if (!C('APP_USE_NAMESPACE')) {
            import('Common/' . $layer . '/' . $class);
        } else {
            $class = '\\Common\\' . $layer . '\\' . $name . $layer;
        }
        $model = class_exists($class) ? (empty($tableName) ? new $class(basename($name)) : new $class(basename($tableName), $tableName)) : new Think\Model($name);
    } else {
        Think\Log::record('D方法实例化没找到模型类' . $class, Think\Log::NOTICE);
        $model = new \Think\Model(basename($name));
    }
    $_model[$name . $layer . '\\' . $tableName] = $model;
    return $model;
}

/**
 * 提示信息
 * @param string $msg 提示内容
 * @param string $title 标题
 * @return void
 */
function exit_msg($msg = '', $title = '提示')
{
    $msg = nl2br($msg);
    $str = <<<str
<!DOCTYPE html><html><head><meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no'/>
<title>{$title}</title>
<style type="text/css">
body{background:#fff;font-family: 'Microsoft YaHei'; color: #333;}
.info{width:90%;font-size:100%; line-height:150%; margin:20px auto; padding:10px;border:solid 1px #ccc;}
</style>
</head>
<body>
<div class="info">{$msg}</div>
</body>
</html>
str;
    echo $str;
    exit();
}

/**
 *发送邮件
 * @param    string   $address       地址
 * @param    string    $title 标题
 * @param    string    $message 邮件内容
 * @param    string $attachment 附件列表
 * @return   boolean
 */
function send_mail($address, $title, $message, $attachment = null)
{
    Vendor('PHPMailer.class#phpmailer');

    $mail = new PHPMailer;
    //$mail->Priority = 3;
    // 设置PHPMailer使用SMTP服务器发送Email
    $mail->IsSMTP();
    // 设置邮件的字符编码，若不指定，则为'UTF-8'
    $mail->CharSet   = 'UTF-8';
    $mail->SMTPDebug = 0; // 关闭SMTP调试功能
    $mail->SMTPAuth  = true; // 启用 SMTP 验证功能
    // $mail->SMTPSecure = 'ssl';  // 使用安全协议
    $mail->IsHTML(true); //body is html

    // 设置SMTP服务器。
    $mail->Host = C('CFG_EMAIL_HOST');
    $mail->Port = C('CFG_EMAIL_PORT') ? C('CFG_EMAIL_PORT') : 25; // SMTP服务器的端口号

    // 设置用户名和密码。
    $mail->Username = C('CFG_EMAIL_LOGINNAME');
    $mail->Password = C('CFG_EMAIL_PASSWORD');

    // 设置邮件头的From字段
    $mail->From = C('CFG_EMAIL_FROM');
    // 设置发件人名字
    $mail->FromName = C('CFG_EMAIL_FROM_NAME');

    // 设置邮件标题
    $mail->Subject = $title;
    // 添加收件人地址，可以多次使用来添加多个收件人
    $mail->AddAddress($address);
    // 设置邮件正文
    $mail->Body = $message;
    // 添加附件
    if (is_array($attachment)) {
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }

    // 发送邮件。
    //return($mail->Send());
    return $mail->Send() ? true : $mail->ErrorInfo;
}

function check_date($str, $format = "Y-m-d")
{
    $unixTime_1 = strtotime($str); //strtotime 成功则返回时间戳，否则返回 FALSE。在 PHP 5.1.0 之前本函数在失败时返回 -1
    if (!is_numeric($unixTime_1) || $unixTime_1 == -1) {
        return false;
    }

    $checkDate  = date($format, $unixTime_1);
    $unixTime_2 = strtotime($checkDate);
    if ($unixTime_1 == $unixTime_2) {
        return true;
    } else {
        return false;
    }
}

/**
 *将字符串转换为数组
 *@param string $data 字符串
 */
function string2array($data)
{
    if ($data == '') {
        return array();
    }

    @eval("\$array = $data;");
    return $array;
}

/**
 *将数组转换为字符串
 *@param    array   $data       数组
 *@param    bool    $isformdata 如果为0，则不使用new_stripslashes处理，可选参数，默
 */
function array2string($data, $isformdata = 1)
{
    if ($data == '') {
        return '';
    }

    if ($isformdata) {
        $data = new_stripslashes($data);
    }

    return addslashes(var_export($data, true));
}

function new_stripslashes($string)
{
    if (!is_array($string)) {
        return stripslashes($string);
    }

    foreach ($string as $key => $val) {
        $string[$key] = new_stripslashes($val);
    }

    return $string;

}

?>