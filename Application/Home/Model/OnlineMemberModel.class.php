<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/11
 * Time: 上午9:12
 */

namespace Home\Model;


use Think\Model;
class OnlineMemberModel extends Model
{
    protected $trueTableName = 'user';

    public function searchOnlineMemebr()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $_count = $this->where($map)->field("id,username,group_id,parent_path,log_lasttime,cur_account,status")->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 500;
        $roolPage = C("ROOLPAGE");
        $url = "";
        //获取数据总数
        $totalRows = $_count;
        $p=new \Page($totalRows, $listRows, http_build_query($map),$url);

        //分页栏每页显示的页数
        $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
        $pages = C('PAGE');
        //可以使用该方法前用C临时改变配置
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $list = $this->where($map)->field("id,username,group_id,parent_path,log_lasttime,cur_account,status")->limit($p->firstRow,$p->listRows)->select();
        $result = array();
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        foreach($list as $item)
        {
            $temp= array();
            $temp['sec'] = $item['id'];
            $temp['username'] = $item['username'];
            switch($item['group_id'])
            {
                case 2 :
                    $temp["group"] = '分销商';break;
                case 3 :
                    $temp["group"] = '总代';break;
                case 4 :
                    $_a = explode(",", $item["parent_path"]);
                    $_b = array("一","二","三","四","五","六","七","八","九","十");
                    $temp["group"] = $_b[count($_a)-2].'级代理';break;
                case 5 :
                    $temp["group"] = '会员';break;
            }
            $key = md5("userbonus".$item["id"]);
            if($redisObj->exists($key))
            {
                $userBonusInfo = json_decode($redisObj->_get($key), true);
            }
            else
            {
                $userBonusInfo = M("user_bonus")->where(array('userid'=>$item["id"]))->find();
                $redisObj->_set($key,json_encode($userBonusInfo));
            }
            $bonus_level = $userBonusInfo["bonus_level"];
            $klc_bonus_level = $userBonusInfo["klc_bonus_content"];
            $temp['cur_account'] = '<s class="fa fa-jpy"></s>'.sprintf("%.4f",$item['cur_account']/100000);
            $temp['bonusLevel'] = $bonus_level.'/'.$klc_bonus_level.'/0';
            $temp['log_lasttime'] = '<s class="fa fa-clock-o"></s>'.date("m/d H:i:s",$item['log_lasttime']);
            $temp['page'] = $page;
            $temp['status'] = $item['status'];
            $result[] =$temp;
        }
        return json_encode($result);
    }

}