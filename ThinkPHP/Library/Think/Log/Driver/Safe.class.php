<?php
/**
 * @controllerName: 控制器名称
 * @describe:
 * @project: codes
 * @author:  jason
 * @updateTime: 2017-04-22 00:37
 */


namespace Think\Log\Driver;

class Safe {

    private static $safeLevelMapping = ['securitymessage' => 1, '_xss_check' => 2, 'sql' => 3];

    public static function logFile($params = [], $type = '') {
        \SeasLog::setLogger("SAFELOG");
        $logInfoData[] = 'request_ip' .'-'.$_SERVER['REMOTE_ADDR'];
        $logInfoData[] = 'domain' .'-'. $_SERVER['HTTP_HOST'];
        $logInfoData[] = 'user_agent' .'-'.$_SERVER['HTTP_USER_AGENT'];
        $logInfoData[] = 'referer' .'-'. $_SERVER['HTTP_REFERER'];
        $logInfoData[] = 'request_time' .'-'. $_SERVER['REQUEST_TIME'];
        list($usec, $sec) = explode(" ", microtime());
        $microTime = ((float)$usec + (float)$sec);
        $logInfoData[] = 'microtime' .'-'. $microTime * 10000;
        $logInfoData[] = 'type' .'-'. self::$safeLevelMapping[$type];
        if($_REQUEST && is_array($_REQUEST) && self::$safeLevelMapping[$type] != 3) {
            foreach($_REQUEST as $rKey => $rVal) {
                $logStr[] =  $rKey.':'.$rVal;
            }
                self::$safeLevelMapping[$type] != 3 && $logInfoData[] = 'content' .'-'. implode(',', $logStr) ;
        }
        else
        {
            $logInfoData[] = 'content' .'-' ;
        }
        if(is_array($params) && !empty($params)) {
            $logInfoData = array_merge($logInfoData, $params);
        }
        $logInfoData[] = 'create_time' .'-'. time();
        if($_SERVER['QUERY_STRING']!="")
            $logInfoData[] = 'request_parameter' .'-'. 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
        else
            $logInfoData[] = 'request_parameter' .'-'. 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        return \SeasLog::info(implode('|', $logInfoData));
    }

//
//    /**
//     * @use  记录日志到数据库
//     * @param array $params 日志内容
//     * @param string $suffix (文件后缀，默认log)
//     * @param array $response 返回数据
//     * @return true
//     */
//    public static function logToDb($logInfo = '',  $type = '') {
//        $logInfoData['request_ip'] = $_SERVER['REMOTE_ADDR'];
//        $logInfoData['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
//        $logInfoData['referer'] = $_SERVER['HTTP_REFERER'];
//        $logInfoData['request_time'] = $_SERVER['REMOTE_ADDR'];
//        $logInfoData['type'] = self::$safeLevelMapping[$type];
//        $logInfoData['domain'] =$_SERVER['HTTP_HOST'];
//        $logInfoData['create_time'] = time();
//        if($_REQUEST && is_array($_REQUEST) && self::$safeLevelMapping[$type] != 3) {
//            foreach($_REQUEST as $rKey => $rVal) {
//                $logStr[] =  $rKey.':'.$rVal;
//            }
//            $logInfoData['content'] = ($logInfo &&  self::$safeLevelMapping[$type] != 3) ? $logInfo.'<br>'.implode('<br>', $logStr) : implode('<br>', $logStr) ;
//        }
//        if(!empty($logInfoData)) {
//            $logModel = new SecurityLogModel();
//            $addRst = $logModel->add($logInfoData);
//            var_dump($addRst);exit;
//        }
//    }

}