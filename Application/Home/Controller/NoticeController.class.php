<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:19
 */

namespace Home\Controller;


use Think\Controller;

class NoticeController extends CommonController
{
    public function index()
    {
        $sid = I('get.n');
        $this->menu = "notice";
        $this->getNotice($sid);
        $this->display();
    }

    protected function getNotice($sid) {
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $redisObj->_setOption();
        $newModel = M("news");
        if($redisObj->exists("notice")){
            $result = $redisObj->_get("notice");
        } else {
            $notice = $newModel->where(array("status"=>1))->order("id DESC")->limit(20)->select();
            $redisObj->_set("notice",$notice);
            $result = $notice;
        }
        $temp = array();
        foreach($result as $val){
            $val["content"] = html_entity_decode($val["content"]);
            $val["time"] = date("Y-m-d H:i:s",$val['dateline']);
            $temp[] = $val;
        }
        $this->count = count($temp);
        $this->sid = ($sid=="") ? $this->count : $sid;
        $this->result = $temp;
    }
}