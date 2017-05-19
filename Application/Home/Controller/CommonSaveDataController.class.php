<?php
namespace Home\Controller;
use Think\Controller;

Class CommonSaveDataController extends Controller {
    public static $RedisObject = NULL;
    public static $saveDataObject = NULL;
    public function __construct() {
        import("Class.RedisObject");
        self::$RedisObject = new \RedisObject();
    }
    public static function getInstance() {
        if (self::$saveDataObject == NULL) {
            self::$saveDataObject = new CommonSaveDataAction ();
        }
        return self::$saveDataObject;
    }

    /**
     *
     * @abstract 根据用户ID获取用户信息以及用户奖金表信息
     * @access public
     * 
     * @param int $uid
     *        	【用户ID】
     * @param int $expireTime
     *        	【Redis过期时间】
     * @param string $type
     *        	【default 查询   save 更新 del 删除】
     * 
     * @return mixed
     */
    public static function UserInfoById_Redis($uid, $expireTime, $type = "select") {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["USERINFO"]["BOLE_REDIS_USERINFO"];
        if(strtolower($REDISKEYARRAY["USERINFO"]["BOLE_REDIS_USERINFO_SWITCH"]) == "open") {//删除缓存开关是否开启
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        switch ($type) {
            case 'select' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_get ( "{$key}{$uid}" );
                } else {
                    self::$RedisObject->_set ( "{$key}{$uid}", self::getDataFunc ( $uid ), $expireTime );
                    return self::$RedisObject->_get ( "{$key}{$uid}" );
                }
                ;
                break;
            case 'save' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    self::$RedisObject->_delete ( "{$key}{$uid}" );
                    self::$RedisObject->_set ( "{$key}{$uid}", self::getDataFunc ( $uid ), $expireTime );
                    return true;
                }
                ;
                break;
            case 'del' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_delete ( "{$key}{$uid}" );
                } else {
                    return false;
                }
                ;
                break;
            default :
                break;
        }
    }

    /**
     *
     * @abstract 根据用户ID获取奖金表用bonus键表示
     * @access public
     * 
     * @param int $uid
     *        	【用户ID】
     * @param int $expireTime
     *        	【Redis过期时间】
     * @param string $type
     *        	【default 查询 add 添加 save 更新 del 删除】
     * @param array $user
     *        	【添加的数据】
     * 
     * @return mixed
     */
    public static function UserBonusById_Redis ($uid, $expireTime, $type = "select") {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["USERBONUS"]["BOLE_REDIS_USERBONUS"];
        if(strtolower($REDISKEYARRAY["USERBONUS"]["BOLE_REDIS_USERBONUS_SWITCH"]) == "open") {//删除缓存开关是否开启
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        switch ($type) {
            case 'select' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_get ( "{$key}{$uid}" );
                } else {
                    if(self::getUserBonus ( $uid ) !== false) {
                        self::$RedisObject->_set ( "{$key}{$uid}", self::getUserBonus ( $uid ), $expireTime );
                        return self::$RedisObject->_get ( "{$key}{$uid}" );
                    }
                }
                ;
                break;
            case 'save' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    self::$RedisObject->_delete ( "{$key}{$uid}" );
                    if(self::getUserBonus ( $uid ) !== false) {
                        self::$RedisObject->_set ( "{$key}{$uid}", self::getUserBonus ( $uid ), $expireTime );
                    }
                    return true;
                }
                ;
                break;
            case 'del' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_delete ( "{$key}{$uid}" );
                } else {
                    return false;
                }
                ;
                break;
            default :
                break;
        }
    }

    /**
     *
     * @abstract 根据uid查询用户表中的数据
     * @access public
     * 
     * @param int $uid
     * @return multitype:
     */
    private static function getDataFunc($uid) {
        $user = M ( "user" )->field ( array (
            "id",
            "username",
            "group_id",
            "parent_id",
            "parent_path",
            "rechargeType",
            "monitor",
            "status"
        ) )->where ( "id = {$uid}" )->find();
        if (! empty ( $user )) {
            return $user;
        } else {
            return false;
        }
    }


    /**
     *
     * @abstract 根据uid查询用户的奖金与返点表中的数据
     * @access public
     * 
     * @param int $uid
     * @return multitype:
     */
    private static  function getUserBonus ($uid) {
        $user = M("user")->where(array("id" => $uid))->find();
        if ($user ["group_id"] == 1 || $user ["group_id"] == 2 || $user ["group_id"] == 6 || $user ["group_id"] == 7 || $user ["group_id"] == 8 || $user ["group_id"] == 9) {
            return false;
        } else {
            $bonus_content = M ( "userBonus" )->where ( array ("userid" => $uid) )->getField("bonus_content");
            if($bonus_content!=""){
                $bonus = (array)json_decode($bonus_content);
                if (! empty ( $bonus )) {
                    foreach ( $bonus as $k=>$v ) {
                        $temp ["bonus"] [$k] = (array)$v;
                    }
                }
                return $temp;
            }
        }
    }

    /**
     *
     * @abstract 根据彩票ID获取彩票基本信息【名称，缩略名，状态】
     * @access public
     * 
     * @param string $lotteryid
     * @param int $expireTime
     * @return mixed
     */
    public static function getLotteryNameById_Redis($lotteryid, $expireTime) {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["LOTTERY"]["BOLE_REDIS_LOTTERY"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["LOTTERY"]["BOLE_REDIS_LOTTERY_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        if (self::$RedisObject->exists ( "{$key}{$lotteryid}" )) {
            return self::$RedisObject->_get ( "{$key}{$lotteryid}" );
        } else {
            $lotteryArray = C ( "LOTTERYADMIN" );

            foreach ( $lotteryArray as $key => $value ) {
                foreach ($value as $k=>$v) {
                    if ($k == $lotteryid) {
                        self::$RedisObject->_set ( "{$key}{$lotteryid}", $v, $expireTime );
                    }
                }
            }
            return self::$RedisObject->_get ( "{$key}{$lotteryid}" );
        }
    }

    /**
     *
     * @abstract 根据彩票期号 获取开奖号码
     * @access public
     * 
     * @param string $lotteryNumberId
     * @param int $lotteryId
     * @param int $expireTime
     * @return mixed
     */
    public static function getOpenLotteryNumber_Redis($lotteryNumberId, $lotteryId, $expireTime) {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["OPENNUM"]["BOLE_REDIS_OPENNUM"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["OPENNUM"]["BOLE_REDIS_OPENNUM_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        if (self::$RedisObject->exists ( "{$key}" . $lotteryNumberId . "_" . $lotteryId )) {
            return self::$RedisObject->_get ( "{$key}" . $lotteryNumberId . "_" . $lotteryId );
        } else {
            $data = M ( "lotteryNumber" )->where ( array ("id" => $lotteryNumberId,"lottery_number"=>array("neq",0)) )->find ();
            if (! empty ( $data )) {
                self::$RedisObject->_set ( "{$key}" . $lotteryNumberId . "_" . $lotteryId, $data, $expireTime );
                return self::$RedisObject->_get ( "{$key}" . $lotteryNumberId . "_" . $lotteryId );
            }
            return false;
        }
    }

    /**
     *
     * @abstract 根据彩票ID获取改彩票的所有玩法
     * @access public
     * 
     * @param int $lotteryid
     * @param int $expireTime
     * @return boolean
     */
    public static function getPlayWayByLotteryId_Redis($lotteryid, $expireTime) {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["ALLPLAYWAY"]["BOLE_REDIS_ALLPLAYWAY"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["ALLPLAYWAY"]["BOLE_REDIS_ALLPLAYWAY_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        if (self::$RedisObject->exists ( "{$key}{$lotteryid}" )) {
            return self::$RedisObject->_get ( "{$key}{$lotteryid}" );
        } else {
            $query = "SELECT A.p_id, B.name FROM lottery_play AS A , lottery_play_way AS B WHERE A.l_id={$lotteryid} AND A.p_id = B.id";
            $data = M ()->query ( $query );
            if (! empty ( $data )) {
                self::$RedisObject->_set ( "{$key}{$lotteryid}", $data, $expireTime );
                return self::$RedisObject->_get ( "{$key}{$lotteryid}" );
            } else {
                return false;
            }
        }
    }

    /**
     *
     * @abstract 根据玩法ID获取玩法数据
     * @access public
     * 
     * @param int $playwayid
     * @param int $expireTime
     * @return mixed
     */
    public static function getPlayNameById_Redis($playwayid, $expireTime) {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["PLAYWAY"]["BOLE_REDIS_PLAYWAY"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["PLAYWAY"]["BOLE_REDIS_PLAYWAY_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        if (self::$RedisObject->exists ( "{$key}{$playwayid}" )) {
            return self::$RedisObject->_get ( "{$key}{$playwayid}" );
        } else {
            $lotteryWay = C("LOTTERY_PLAY_WAY");
            foreach ($lotteryWay as $kk=>$vv){
                if($vv == $playwayid){
                    $data = $kk;
                }
            }
            if (! empty ( $data )) {
                self::$RedisObject->_set ( "{$key}{$playwayid}", $data, $expireTime );
                return self::$RedisObject->_get ( "{$key}{$playwayid}" );
            } else {
                return false;
            }
        }
    }

    /**
     *
     * @abstract 根据用户id获取剩余余额
     * @access public
     * 
     * @param int $uid
     * @param int $expireTime
     * @return boolean
     */
    public static function getCurrentCountByUserid_Redis($uid, $expireTime, $cur_account='', $type = "select") {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["CURRENTCOUNT"]["BOLE_REDIS_CURRENTCOUNT"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["CURRENTCOUNT"]["BOLE_REDIS_CURRENTCOUNT_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        switch ($type) {
            case 'select' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_get ( "{$key}{$uid}" );
                } else {
                    $activeData = M ( "user" )->where ( array ("id" => $uid) )->getField ( "cur_account" );
                    if (isset ( $activeData )) {
                        self::$RedisObject->_set ( "{$key}{$uid}", $activeData, $expireTime );
                        return self::$RedisObject->_get ( "{$key}{$uid}" );
                    }
                };
                break;
            case 'save' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    self::$RedisObject->_delete ( "{$key}{$uid}" );
                    $activeData = M ( "user" )->where ( array ("id" => $uid) )->getField ( "cur_account" );
                    if( isset( $activeData )) {
                        M ( "user" )->where(array("id" => $uid))->save(array("cur_account" => $cur_account));
                    }
                };
                break;
            case 'delkey' :
                self::$RedisObject->_delete("{$key}{$uid}");
                break;
            default : break;
        }

    }

    /**
     *
     * @abstract 根据用户ID获取该用户的在线充值设置
     * @access public
     * 
     * @param int $uid
     * @param int $expireTime
     * @param string $type
     * @return boolean
     */
    public static function onlineInfoByUserid_Redis($uid, $expireTime, $type = "select") {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["ONLINESET"]["BOLE_REDIS_ONLINESET"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["ONLINESET"]["BOLE_REDIS_ONLINESET_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        switch ($type) {
            case 'select' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_get ( "{$key}{$uid}" );
                } else {
                    $data = M ( "userOnlinetool" )->where ( array ("userid" => $uid ) )->find ();
                    self::$RedisObject->_set ( "{$key}{$uid}", $data, $expireTime );
                    return self::$RedisObject->_get ( "{$key}{$uid}" );
                };
                break;
            case 'save' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    self::$RedisObject->_delete ( "{$key}{$uid}" );
                    $data = M ( "userOnlinetool" )->where ( array ("userid" => $uid ) )->find ();
                    self::$RedisObject->_set ( "{$key}{$uid}", $data, $expireTime );
                    return true;
                };
                break;
            case 'del' :
                if (self::$RedisObject->exists ( "{$key}{$uid}" )) {
                    return self::$RedisObject->_delete ( "{$key}{$uid}" );
                } else {
                    return false;
                };
                break;
            default :
                break;
        }
    }

    /**
     *
     * @abstract 清除所有的的redis
     * @access public
     * 
     * @param int $uid
     * @param int $expireTime
     * @param string $type
     * @return boolean
     */
    public static function cleanAllRedisKey () {
        $REDISKEYARRAY = C("BOLEREDISKEY");
        if(strtolower($REDISKEYARRAY["CLEANKEY"]["BOLE_REDIS_CLEANKEY_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys('*');
            foreach ($keyArray as  $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
    }
    /**
     *
     * @abstract 上传并生产缩略图
     * @access public
     * 
     * @return mixed json
     */
    public static function _upload() {
        import ( "@.ORG.UploadFile" );
        // 导入上传类
        $upload = new UploadFile ();
        // 设置上传文件大小
        $upload->maxSize = C ( "MAXSIZE" );
        // 设置上传文件类型
        $upload->allowExts = explode ( ',', C ( "ALLOWEXTS" ) );
        // 设置附件上传目录
        $upload->savePath = C ( "SAVEPATH" );
        // 设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = C ( "THUMB" );
        // 设置引用图片类库包路径
        $upload->imageClassPath = '@.ORG.Image';
        // 设置需要生成缩略图的文件后缀
        $upload->thumbPrefix = C ( "THUMBPREFIX" );
        // 设置缩略图最大宽度
        $upload->thumbMaxWidth = C ( "THUMBMAXWIDTH" );
        // 设置缩略图最大高度
        $upload->thumbMaxHeight = C ( "THUMBMAXHEIGHT" );
        // 设置上传文件规则
        $upload->saveRule = C ( "SAVERULE" );
        // 删除原图
        $upload->thumbRemoveOrigin = C ( "THUMBREMOVEORIGIN" );
        // 存在同名时是否覆盖
        $upload->uploadReplace = C ( "UPLOADREPLACE" );
        if (! $upload->upload ()) {
            // 捕获上传异常
            echo $upload->getErrorMsg ();
        } else {
            // 取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo ();
            return "/" . C ( "DOCUMENT" ) . "/Upload/" . '-' . C ( "THUMBPREFIX" ) . $uploadList [0] ["savename"];
        }
    }

    /**
     *
     * @abstract 根据标签名称查询功能设置代码
     * @access public
     * 
     * @param string $labelName
     * @param int $expireTime
     * @param string $type
     * @return boolean
     */
    public function getTaglibCode_Redis($labelName, $expireTime, $type = "select") {
        $expireTime = isset ( $expireTime ) ? $expireTime : C ( "REDIS_EXPIRE" );
        $REDISKEYARRAY = C("BOLEREDISKEY");
        $key = $REDISKEYARRAY["TAGLIBCODE"]["BOLE_REDIS_TAGLIBCODE"];
        //删除缓存开关是否开启
        if(strtolower($REDISKEYARRAY["TAGLIBCODE"]["BOLE_REDIS_TAGLIBCODE_SWITCH"]) == "open") {
            $keyArray = self::$RedisObject->_keys("{$key}*");
            foreach ($keyArray as $k => $v) {
                self::$RedisObject->_delete($v);
            }
        }
        self::$RedisObject->_setOption();
        switch ($type) {
            case "select" :
                if(self::$RedisObject->exists("{$key}{$labelName}")) {
                    return self::$RedisObject->_get("{$key}{$labelName}");
                } else {
                    $data = M("expand")->field(array("fun_set"))->where(array("label_title" => $labelName))->find();
                    self::$RedisObject->_set("{$key}{$labelName}", $data, $expireTime);
                    return self::$RedisObject->_get("{$key}{$labelName}");
                };
                break;
            case 'save' :
                if (self::$RedisObject->exists ( "{$key}{$labelName}" )) {
                    self::$RedisObject->_delete ( "{$key}{$labelName}" );
                    $data = M("expand")->field(array("fun_set"))->where(array("label_title" => $labelName))->find();
                    self::$RedisObject->_set ( "{$key}{$labelName}", $data, $expireTime );
                    return true;
                } else {
                    $data = M("expand")->field(array("fun_set"))->where(array("label_title" => $labelName))->find();
                    self::$RedisObject->_set ( "{$key}{$labelName}", $data, $expireTime );
                    return true;
                }
                ;
                break;
            case "del" :
                if (self::$RedisObject->exists ( "{$key}{$labelName}" )) {
                    return self::$RedisObject->_delete ( "{$key}{$labelName}" );
                } else {
                    return false;
                }
                ;
                break;
            default :
                break;
        }
    }
}

?>
