<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/10
 * Time: 下午3:25
 */

namespace Home\Model;


use Think\Model;

class UserIntervalModel extends Model
{
    protected $trueTableName = 'user_interval';

    /**
     * 根据用户ID查询配额
     *
     * @return mixed
     */
    public function selectByUserid()
    {
        $uid = session("SESSION_ID");
        $list = $this->where(array("userid"=>$uid))->find();
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $key = md5("userbonus".$uid);
        if($redisObj->exists($key))
        {
            $userBonusInfo = json_decode($redisObj->_get($key), true);
        }
        else
        {
            $userBonusInfo = M("user_bonus")->where(array('userid'=>$uid))->find();
            $redisObj->_set($key,json_encode($userBonusInfo));
        }
        $bonus_level = $userBonusInfo['bonus_level'];
        $INTERVAL = C('INTERVAL');
        $temp = array();
        foreach($INTERVAL as $k=>$v)
        {
            $temp["interval_".$k] = $v;
        }
        $output = array();
        foreach($list as $key=>$item)
        {
            if(array_key_exists($key,$temp))
            {
                if(bccomp($key,$bonus_level,2)==-1)
                    $output[$temp[$key]*10] = $item;
            }
            else
            {
                $output[$key] = $item;
            }
        }
        return $output;
    }

    /**
     * 查询是否有配额
     *
     * @return bool
     */
    public function isQuate()
    {
        $uid = session("SESSION_ID");
        $list = $this->where(array("userid"=>$uid))->find();
        if(empty($list))
            return false;
        else
            return true;
    }

    /**
     * 获取用户的配额信息
     *
     * @return mixed
     */
    public function getQuateList()
    {
        $uid = session("SESSION_ID");
        return $list = $this->where(array("userid"=>$uid))->find();
    }
}