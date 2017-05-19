<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 上午9:18
 */

namespace Home\Controller;

use Home\Model\OnlineMemberModel as OnlineMember;
class OnlineMemberController extends CommonController
{

    public function index()
    {
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxymanager = 'onlinemembers';
        $this->display();
    }

    public function onlineSearchMember()
    {
        $username = I("post.username","");
        $starttime = I("post.starttime","");
        $endtime = I("post.endtime","");
        $mincapital = I("post.mincapital","");
        $maxcapital = I("post.maxcapital","");
        $where['username'] = (!empty($username)) ? $username : "";
        $where['reg_time'] = (!empty($starttime) && !empty($endtime)) ? array(array('gt',strtotime($starttime)),array('lt',strtotime($endtime))) : "";
        $where['cur_account'] = (!empty($mincapital) && !empty($maxcapital)) ? array(array('gt',$mincapital*100000),array('lt',$maxcapital*100000)) : "";
        $where['_string'] = "parent_id=".session("SESSION_ID");
        foreach($where as $k=>$v)
        {
            if(!$v)
            {
                unset($where[$k]);
            }
        }
        $OnlineMember = new OnlineMember();
        $OnlineMember->data = array("where"=>$where);
        echo $OnlineMember->searchOnlineMemebr();
    }
}