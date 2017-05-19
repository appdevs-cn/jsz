<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/11
 * Time: 上午9:12
 */

namespace Home\Model;


use Think\Model;
use Home\Model\SharecontractModel as Sharecontract;
use Home\Model\DayratecontractModel as Dayratecontract;
use Home\Model\MgGameModel as MgGame;
class MemverModel extends Model
{
    protected $trueTableName = 'user';

    public function searchMemebr()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $parentid = $map['parent_id'];
        $_parentid = session("SESSION_PARENTID");
        $_group = session("SESSION_ROLE");
        $userModel = M("user");
        $_ol = "";
        if($parentid!=session("SESSION_ID"))
        {
            $parentPath = $userModel->where(array("id"=>$parentid))->getField('parent_path');
            $pathArr = explode(",",$parentPath);
            if($_group==3)
            {
                array_shift($pathArr);

            } else if($_group==4)
            {
                array_shift($pathArr);
                array_shift($pathArr);
            }
            foreach($pathArr as $p)
            {
                if($p==$_parentid) continue;
                $_name = $userModel->where(array('id'=>$p))->getField("username");
                $_ol .= '<li style="width:auto;float:left"><a style="color:#b23632" href="javascript:;" childid="'.$p.'" class="showMenuChild">'.$_name.'</a></li>';
                $_ol .='<li style="width:auto;float:left"><i class="icon-double-angle-right"></i></li>';
            }
            $selfname = $userModel->where(array('id'=>$parentid))->getField("username");
            $_ol .= '<li style="width:auto;float:left" class="active">'.$selfname.'</li>';
        }
        else
        {
            $selfname = $userModel->where(array('id'=>$parentid))->getField("username");
            $_ol .= '<li style="width:auto;float:left" class="active">'.$selfname.'</li>';
        }

        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_count = $this->where($map)->field("id,username,group_id,parent_id,parent_path,log_lasttime,cur_account,recharge_fun,share_switch,dayrate_switch")->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = C("LISTROWS");
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
        $list = $this->where($map)->field("id,username,group_id,parent_id,parent_path,log_lasttime,cur_account,recharge_fun,status,share_switch,dayrate_switch")->limit($p->firstRow,$p->listRows)->select();
        $result = array();
        $uid = session('SESSION_ID');
        $Sharecontract = new Sharecontract();
        $Dayratecontract = new Dayratecontract();
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $MgGame = new MgGame();

        $self = $this->where(array("id"=>$uid))->field("share_switch,dayrate_switch")->find();

        foreach($list as $item)
        {
            $_html = '';
            $temp= array();
            $temp['id'] = $_xDe->encode($item['id']);
            $temp['sec'] = $item['id'];
            $isChild = $this->where(array("parent_id"=>$item['id']))->count();
            if($isChild>0)
                $temp['username'] = '<a childid="'.$item['id'].'" style="cursor:pointer" class="c--f35c19 ml--10 showChild">'.$item['username'].'</a> ('.$isChild.'人)';
            else
                $temp['username'] = $item['username'];
            switch($item['group_id'])
            {
                case 2 :
                    $temp["group"] = '分销商';break;
                case 3 :
                    $temp["group"] = '总代组';break;
                case 4 :
                    //$_a = explode(",", $item["parent_path"]);
                    //$_b = array("一","二","三","四","五","六","七","八","九","十");
                    //$temp["group"] = $_b[count($_a)-2].'级代理';break;
                    $temp["group"] = "代理组";
                    break;
                case 5 :
                    $temp["group"] = '会员组';break;
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
            $temp['cur_account'] = sprintf("%.4f",$item['cur_account']/100000);
            $temp['bonusLevel'] = $bonus_level.'/'.$klc_bonus_level.'/0';
            $temp['log_lasttime'] = date("m/d H:i:s",$item['log_lasttime']);
            if(bccomp($bonus_level,12.4,2)==1)
                $_html .= '<a style="cursor:pointer" class="c--f35c19 ml--10" pd="'.$temp['id'].'" data-method="set_account">开户配置</a>';

            if($item['parent_id']==$uid)
            {
                $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" u="'.$temp['id'].'" uname="'.$item["username"].'" data-method="set_transfer">下级转账</a>';
            }
            else
            {
                if($item['recharge_fun']==1)
                {
                    $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" data-method="set_transfer">下级转账</a>';
                }
            }
            if($item['parent_id']==$uid)
            {
                $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" xd="'.$temp['id'].'" data-method="set_quota">游戏返点</a>';
            }


            // 签署契约分红
            if($Sharecontract->isHaveSharecontrace())
            {
                if($item['group_id']==4 && $item['parent_id']==session("SESSION_ID") && $item["share_switch"]==1)
                {
                    $Sharecontract->data = array('firstparty'=>session("SESSION_ID"),'secondparty'=>$item['id']);
                    $xReturn = $Sharecontract->SearchContractStatus();
                    $contractResult = json_decode($xReturn, true);
                    if($contractResult['status']==0)
                    {
                        $temp['contract'] = '<font style="color:red">未签约</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" data-method="sign_contract">签订契约分红</a>';
                    }
                    else if($contractResult['status']==1)
                    {
                        $temp['contract'] = '<font style="color:#f78a48">签约中</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" contract="'.$_xDe->encode($contractResult['secondparty']).'" data-method="sign_contracting">契约分红签订中</a>';
                    }
                    else if($contractResult['status']==2)
                    {
                        $temp['contract'] = '<font style="color:green">签约成功</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" contract="'.$_xDe->encode($contractResult['secondparty']).'" data-method="sea_contract">查看契约分红</a>';
                    }
                    else if($contractResult['status']==3)
                    {
                        $temp['contract'] = '<font style="color:blue">解约中</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" contract="'.$_xDe->encode($contractResult['id']).'" data-method="dischange_contract">解除契约分红</a>';
                    }
                }
                else
                {
                    $temp['contract'] = '<i class="icon-minus"></i>';
                }
            }
            else
            {
                $temp['contract'] = '<i class="icon-minus"></i>';
            }
            

            // 签署日工资契约
            if($Dayratecontract->isHaveDayratecontrace())
            {
                if($item['group_id']==4 && $item['parent_id']==session("SESSION_ID") && $item["dayrate_switch"]==1)
                {
                    $Dayratecontract->data = array('firstparty'=>session("SESSION_ID"),'secondparty'=>$item['id']);
                    $xReturn = $Dayratecontract->SearchContractStatus();
                    $contractResult = json_decode($xReturn, true);
                    if($contractResult['status']==0)
                    {
                        $temp['dayratecontract'] = '<font style="color:red">未签约</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" data-method="sign_dayrate_contract">签订契约日工资</a>';
                    }
                    else if($contractResult['status']==1)
                    {
                        $temp['dayratecontract'] = '<font style="color:#f78a48">签约中</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" contract="'.$_xDe->encode($contractResult['secondparty']).'" data-method="sign_dayrate_contracting">契约日工资签订中</a>';
                    }
                    else if($contractResult['status']==2)
                    {
                        $temp['dayratecontract'] = '<font style="color:green">签约成功</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" contract="'.$_xDe->encode($contractResult['secondparty']).'" data-method="sea_dayrate_contract">查看契约日工资</a>';
                    }
                    else if($contractResult['status']==3)
                    {
                        $temp['dayratecontract'] = '<font style="color:blue">解约中</font>';
                        $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" uname="'.$item["username"].'" party="'.$temp['id'].'" contract="'.$_xDe->encode($contractResult['id']).'" data-method="dischange_dayrate_contract">解除契约日工资</a>';
                    }
                }
                else
                {
                    $temp['dayratecontract'] = '<i class=" icon-minus"></i>';
                }
            }
            else
            {
                $temp['dayratecontract'] = '<i class=" icon-minus"></i>';
            }

            if($self['dayrate_switch']==1)
            {
                if($item['group_id']==4 && $item['parent_id']==session("SESSION_ID") && $item["dayrate_switch"]==1)
                {
                    $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" u="'.$temp['id'].'" data-method="close_dayrateswitch">关闭日工资契约</a>';
                }
                if($item['group_id']==4 && $item['parent_id']==session("SESSION_ID") && $item["dayrate_switch"]==0)
                {
                    $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" u="'.$temp['id'].'" data-method="open_dayrateswitch">开启日工资契约</a>';
                }
            }
            
            if($self['share_switch']==1)
            {
                if($item['group_id']==4 && $item['parent_id']==session("SESSION_ID") && $item["share_switch"]==1)
                {
                    $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" u="'.$temp['id'].'" data-method="close_shareswitch">关闭分红契约</a>';
                }
                if($item['group_id']==4 && $item['parent_id']==session("SESSION_ID") && $item["share_switch"]==0)
                {
                    $_html .= '<a class="c--f35c19 ml--10" style="cursor:pointer" u="'.$temp['id'].'" data-method="open_shareswitch">开启分红契约</a>';
                }
            }

            if($_html=="")
            {
                $_html .= '暂无操作项';;
            }
            if($item['status']==1)
                $temp['operate'] = $_html."</ul>";
            else
                $temp['operate'] = '<a class="disabled c--f35c19">禁止操作</a>';
            $temp['page'] = $page;
            $temp['status'] = $item['status'];
            $temp['ol'] = $_ol;
            $temp['currentuid'] = $parentid;
            $result[] =$temp;
        }
        return json_encode($result);
    }

}