<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/6
 * Time: 下午9:38
 */

namespace Home\Model;
use Think\Model;

class UserLoginModel extends Model
{
    protected $trueTableName = "user_login";

    protected $_validate = array(
        array("userid","require","用户ID不能为空",self::MUST_VALIDATE),
        array("username","require","用户名不能为空",self::MUST_VALIDATE),
        array("login_ip","require","登录IP不能为空",self::MUST_VALIDATE),
        array("login_time","require","登录时间不能为空",self::MUST_VALIDATE),
    );

    public function getAddress()
    {
        $uid = session("SESSION_ID");
        $loginIp = $this->where(array("userid"=>$uid))->order("id desc")->getField("login_ip");
        return convertip(long2ip($loginIp))."-".$loginIp;
    }
}