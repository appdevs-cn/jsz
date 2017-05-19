<?php

namespace Think\Db\Driver;
use Think\Exception;
use Think\Log\Driver\Safe;
class DbSafe {

    protected static $checkcmd = array('SEL'=>1, 'UPD'=>1, 'INS'=>1, 'REP'=>1, 'DEL'=>1);
    protected static $config;

    public static function checkquery($sql) {
        if (C('QUERYSAFE_STATUS') && !is_array(C('QUERYSAFE_STATUS'))) {
            $check = 1;
            $cmd = strtoupper(substr(trim($sql), 0, 3));
            if(isset(self::$checkcmd[$cmd])) {
                $check = self::doQuerySql($sql);
            } elseif(substr($cmd, 0, 2) === '/*') {
                $check = -1;
            }
            if ($check < 1) {
                C('SAFE_LOG_POWER') && Safe::logFile(['sql' => $sql],  __FUNCTION__);
                throw new Exception('It is not safe to do this query'."\n".$sql, 0);
            }
        }
        return true;
    }

    public static function doQuerySql($sql) {
        $sql = str_replace(array('\\\\', '\\\'', '\\"', '\'\''), '', $sql);
        $mark = $clean = '';
        if (strpos($sql, '/') === false && strpos($sql, '#') === false && strpos($sql, '-- ') === false && strpos($sql, '@') === false && strpos($sql, '`') === false) {
            $clean = preg_replace("/'(.+?)'/s", '', $sql);
        } else {
            $len = strlen($sql);
            $mark = $clean = '';
            for ($i = 0; $i < $len; $i++) {
                $str = $sql[$i];
                switch ($str) {
                    case '`':
                        if(!$mark) {
                            $mark = '`';
                            $clean .= $str;
                        } elseif ($mark == '`') {
                            $mark = '';
                        }
                        break;
                    case '\'':
                        if (!$mark) {
                            $mark = '\'';
                            $clean .= $str;
                        } elseif ($mark == '\'') {
                            $mark = '';
                        }
                        break;
                    case '/':
                        if (empty($mark) && $sql[$i + 1] == '*') {
                            $mark = '/*';
                            $clean .= $mark;
                            $i++;
                        } elseif ($mark == '/*' && $sql[$i - 1] == '*') {
                            $mark = '';
                            $clean .= '*';
                        }
                        break;
                    case '#':
                        if (empty($mark)) {
                            $mark = $str;
                            $clean .= $str;
                        }
                        break;
                    case "\n":
                        if ($mark == '#' || $mark == '--') {
                            $mark = '';
                        }
                        break;
                    case '-':
                        if (empty($mark) && substr($sql, $i, 3) == '-- ') {
                            $mark = '-- ';
                            $clean .= $mark;
                        }
                        break;

                    default:

                        break;
                }
                $clean .= $mark ? '' : $str;
            }
        }

        if(strpos($clean, '@') !== false) {
            return '-3';
        }

        $clean = preg_replace("/[^a-z0-9_\-\(\)#\*\/\"]+/is", "", strtolower($clean));
        if (C('QUERYSAFE_AFULLNOTE')) {
            $clean = str_replace('/**/', '', $clean);
        }
        if (is_array(C('QUERYSAFE_DFUNCTION'))) {
            foreach (C('QUERYSAFE_DFUNCTION') as $fun) {
                if (strpos($clean, $fun . '(') !== false)
                    return '-1';
            }
        }

        if (is_array(C('QUERYSAFE_DACTION'))) {
            foreach (C('QUERYSAFE_DACTION') as $action) {
                if (strpos($clean, $action) !== false)
                    return '-3';
            }
        }

        if (C('QUERYSAFE_DLIKEHEX') && strpos($clean, 'like0x')) {
            return '-2';
        }

        if (is_array(C('QUERYSAFE_DNOTE'))) {
            foreach (C('QUERYSAFE_DNOTE') as $note) {
                if (strpos($clean, $note) !== false)
                    return '-4';
            }
        }

        return 1;
    }

    public static function setconfigstatus($data) {
        $status = $data ? 1 : 0;
        C('QUERYSAFE_STATUS', $status);
    }
}