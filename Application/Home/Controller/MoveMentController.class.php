<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/11
 * Time: 下午9:30
 */

namespace Home\Controller;

use Home\Model\CommonModel as Common;
class MoveMentController extends CommonController
{
    public function propellingLogin()
    {
        $Common = new Common();
        echo $Common->sendWebSocketMsg(array("type"=>"login","to"=>"","content"=>"用户:".session("SESSION_NICKNAME")."上线-".session("SESSION_PATH")));
    }
}