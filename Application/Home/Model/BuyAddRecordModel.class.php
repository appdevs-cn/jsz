<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午12:37
 */

namespace Home\Model;


use Think\Model;
use Home\Model\BuyRecordModel as BuyRecord;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
class BuyAddRecordModel extends Model
{
    protected $trueTableName = 'buy_add_record';

    public function proxyRecord()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $st = $data['data']['st'];
        $BuyRecord = new BuyRecord();
        $BuyRecord->data = array("data"=>array("where"=>$map,"st"=>$st));
        $_res = (array)json_decode($BuyRecord->proxyRecord());
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_temp = array();
        if(!empty($_res))
        {
            foreach($_res as $add) {
                $add = (array)$add;
                $_comp_count = $this->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->count();
                $add["compCount"] = $_comp_count;
                $_compAmount = $this->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->sum('monetary');
                $add["compAmount"] = sprintf("%.4f",$_compAmount/100000);
                $_comp_amount = $this->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->sum('bonus');
                $add["bonusAmount"] = sprintf("%.4f",$_comp_amount/100000);
                //状态
                if(!empty($st))
                {
                    if($st==5)
                    {
                        if($_comp_count<$add['add_number'])
                        {
                            $add['status'] = "<span class='green'>进行中</span>";
                            $_temp[] = $add;
                        }
                        else
                        {
                            continue;
                        }
                    }
                    if($st==6)
                    {
                        if($_comp_count==$add['add_number'])
                        {
                            $add['status'] = "已完成";
                            $_temp[] = $add;
                        }
                        else
                        {
                            continue;
                        }
                    }
                }
                else
                {
                    if($_comp_count<$add['add_number'])
                    {
                        $add['status'] = "<span class='green'>进行中</span>";
                    }
                    else if($_comp_count==$add['add_number'])
                    {
                        $add['status'] = "已完成";
                    }
                    $_temp[] = $add;
                }
            }
        }
        return json_encode($_temp);
    }

    public function selfRecord()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $st = $data['data']['st'];
        $BuyRecord = new BuyRecord();
        $BuyRecord->data = array("data"=>array("where"=>$map,"st"=>$st));
        $_res = (array)json_decode($BuyRecord->proxyRecord());
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_temp = array();
        if(!empty($_res))
        {
            foreach($_res as $add) {
                $add = (array)$add;
                $_comp_count = $this->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->count();
                $add["compCount"] = $_comp_count;
                $_compAmount = $this->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->sum('monetary');
                $add["compAmount"] = sprintf("%.4f",$_compAmount/100000);
                $_comp_amount = $this->where(array("buy_id"=>$_xDe->decode($add['id']),"userid"=>$add['userid'],"status"=>array('neq',0)))->sum('bonus');
                $add["bonusAmount"] = sprintf("%.4f",$_comp_amount/100000);
                //状态
                if(!empty($st))
                {
                    if($st==5)
                    {
                        if($_comp_count<$add['add_number'])
                        {
                            $add['status'] = "<span class='green'>进行中</span>";
                            $_temp[] = $add;
                        }
                        else
                        {
                            continue;
                        }
                    }
                    if($st==6)
                    {
                        if($_comp_count==$add['add_number'])
                        {
                            $add['status'] = "已完成";
                            $_temp[] = $add;
                        }
                        else
                        {
                            continue;
                        }
                    }
                }
                else
                {
                    if($_comp_count<$add['add_number'])
                    {
                        $add['status'] = "<span class='green'>进行中</span>";
                    }
                    else if($_comp_count==$add['add_number'])
                    {
                        $add['status'] = "已完成";
                    }
                    $add['operate'] = '<a style="cursor:pointer" class="c--f35c19" data-method="detail" data-field="'.$add['id'].'">详情</a><a style="cursor:pointer" class="c--f35c19 ml--10" data-field="'.$add['id'].'" data-method="list">追号列表</a>';
                    $_temp[] = $add;
                }
            }
        }
        return json_encode($_temp);
    }

    public function selfRecordList()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $st = $data['data']['st'];
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_count = $this->where($map)->field("id,buy_id,add_serial_number,userid,buy_type_id,yuan,lottery_id,lottery_number_id,monetary,bonus,status,multiple")->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 1000;
        $roolPage = C("ROOLPAGE");
        $url = "";
        //获取数据总数
        $totalRows = $_count;
        $p=new \Page($totalRows, $listRows, http_build_query($map),$url);

        //分页栏每页显示的页数
        $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
        $pages = C('PAGE');
        //可以使用该方法前用C临时改变配置
        $lottery = C("LOTTERY");
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $list = $this->where($map)->field("id,buy_id,add_serial_number,userid,buy_type_id,yuan,lottery_id,lottery_number_id,monetary,bonus,status,multiple")->limit($p->firstRow,$p->listRows)->order("id asc")->select();
        $result = array();
        $lotteryNumberMid = M("lottery_number_mid");
        $lottery_play_way = M("lottery_play_way");
        foreach($list as $item)
        {
            $temp= array();
            $temp['id'] = $_xDe->encode($item['id']);
            $temp['buy_id'] = $_xDe->encode($item['buy_id']);
            $temp['serial_number'] = $item['add_serial_number'];//订单号
            $temp['lottery_name'] = $lottery[$item['lottery_id']]['name'];  //彩种
            $temp['lottery_serial_number'] = $lotteryNumberMid->where(array("id"=>$item['lottery_number_id']))->getField("series_number");//期号
            $temp["playName"] = $lottery_play_way->where(array("id"=>$item['buy_type_id']))->getField("name"); //玩法
            if($item['yuan']==0)
                $temp["mode"] = "元";
            else if($item['yuan']==1)
                $temp["mode"] = "角";
            else if($item['yuan']==2)
                $temp["mode"] = "分";
            else if($item['yuan']==3)
                $temp["mode"] = "厘";
            $temp['monetary'] = sprintf("%.4f",$item['monetary']/100000);
            $temp['multiple'] = $item['multiple'];
            $temp['bonus'] = sprintf("%.4f",$item['bonus']/100000);
            $endtime = $lotteryNumberMid->where(array("id"=>$item['lottery_number_id']))->getField("endtime");
            if($endtime>time() && $item['status']==0)
                $temp['operate'] = '<a style="cursor:pointer" class="c--f35c19" data-field="'.$temp['buy_id'].'-'.$temp['id'].'" data-method="addcancel"">订单撤销</a>';
            else
                $temp['operate'] = '无操作';
            
            $temp['page'] = $page;
            switch($item['status'])
            {
                case 0:
                    $temp['status'] = "未开奖";break;
                case 1:
                    ++$compcount;
                    if($item["bonus"]>0)
                        $temp['status'] = "<span style='color:#b23632'>已中奖</span>";
                    else
                        $temp['status'] = "<span style='color:green'>未中奖</span>";
                    break;
                case 2:
                    ++$compcount;
                    $temp['status'] = "<span style='color:red'>已撤单</span>";break;

            }
            $compcount = $this->where(array("buy_id"=>$item['buy_id'],"userid"=>$item['userid'],"status"=>array('neq',0)))->count();
            if($compcount!=$_count)
            {
                $temp['showcancelbtn'] = true;
            }
            else
            {
                $temp['showcancelbtn'] = false;
            }
            if(!empty($st))
            {
                if($st==1)
                {
                    if($item['status']==0)
                        $result[] =$temp;
                }
                if($st==2)
                {
                    if($item['status']==1 && empty($item['bonus']))
                        $result[] =$temp;
                }
                if($st==3)
                {
                    if($item['status']==1 && !empty($item['bonus']))
                        $result[] =$temp;
                }
                if($st==4)
                {
                    if($item['status']==2)
                        $result[] =$temp;
                }
                if($st==5 || $st==6)
                {
                    $result[] =$temp;
                }
            }
            else
            {
                $result[] =$temp;
            }
        }
        return json_encode($result);
    }

    /**
     * 订单详情
     */
    public function detailRecord()
    {
        $data = $this->data;
        $_bid = $data['data']['buyid'];
        $_Field = Array("lottery_play_way.name",
            "buy_record.userid",
            "buy_record.buy_time",
            "buy_record.serial_number",
            "buy_record.id",
            "buy_record.lottery_id",
            "buy_record.lottery_number_id",
            "buy_record.monetary",
            "buy_record.multiple",
            "buy_record.buy_number",
            "buy_record.status",
            "buy_record.yuan",
            "buy_record.bonus",
            "buy_record.buy_type_id",
            "buy_record.add_number",
            "buy_record.stop_add",
            "buy_record.bonusType",
            "buy_record.position",
            "buy_record.cancelRemark",
            "lottery_number.starttime",
            "lottery_number.endtime",
            "lottery_number.lottery_number",
            "lottery_number.series_number as series"
        );
        $_List = M("buy_record")->join("lottery_number on buy_record.lottery_number_id=lottery_number.id")
            ->join("lottery_play_way on buy_record.buy_type_id = lottery_play_way.id")->field($_Field)
            ->where(array( "buy_record.id" => $_bid))->select();
        $_caipiao_arr = C("LOTTERY");
        $temp = array();
        $User = M("user");
        foreach($_List as $val) {
            $val["serial_number"] = $val['serial_number'];
            $val["username"] = $User->where(array("id"=>$val["userid"]))->getField("username");
            $val["buy_time"] = date("Y/m/d H:i:s",$val['buy_time']);
            $val["lotteryName"] = $_caipiao_arr[$val["lottery_id"]]["name"];

            if($val['buy_type_id']==51)
            {
                $arr = array(0=>"0单5双",1=>"1单4双",2=>"2单3双",3=>"3单2双",4=>"4单1双",5=>"5单0双");
                $val['buy_number'] = rtrim($val['buy_number']);
                $buynumber = explode(" ",$val['buy_number']);
                $str = "";
                foreach($buynumber as $n)
                {
                    $str .= $arr[$n]."|";
                }
                $val['buy_number'] = substr($str,0,strlen($str)-1);
            }
            else
            {
                $val['buy_number'] = str_replace(",","|",$val["buy_number"]);
            }
            
            $val["play_way"] = $val['name'];
            if($val["yuan"]==0)
                $val["model"] = "元";
            else if($val["yuan"]==1)
                $val["model"] = "角";
            else if($val["yuan"]==2)
                $val["model"] = "分";
            else if($val["yuan"]==3)
                $val["model"] = "厘";
            $val["compCount"] = $_comp_count = $this->where(array("buy_id"=>$val['id'],"userid"=>$val['userid'],"status"=>array('neq',0)))->count();
            $val["cancelCount"] = $_comp_count = $this->where(array("buy_id"=>$val['id'],"userid"=>$val['userid'],"status"=>array('eq',2)))->count();
            $val['monetary'] = sprintf("%.4f",$val['monetary']/100000);
            $_compAmount = $this->where(array("buy_id"=>$val['id'],"userid"=>$val['userid'],"status"=>array('neq',0)))->sum('monetary');
            $val['compAmount'] = sprintf("%.4f",$_compAmount/100000);
            $val['bonusCount'] = $_comp_count = $this->where(array("buy_id"=>$val['id'],"userid"=>$val['userid'],"bonus"=>array('gt',0)))->count();
            $_comp_amount = $this->where(array("buy_id"=>$val['id'],"userid"=>$val['userid'],"status"=>array('neq',0)))->sum('bonus');
            $val["bonusAmount"] = sprintf("%.4f",$_comp_amount/100000);
            $_cancel_amount = $this->where(array("buy_id"=>$val['id'],"userid"=>$val['userid'],"status"=>array('neq',2)))->sum('monetary');
            $val['cancelAmount'] = sprintf("%.4f",$_cancel_amount/100000);
            $val['isStop'] = ($val["stop_add"]==1) ? "是" : "否";
            $val["status"] = ($val["compCount"]<$val['add_number']) ? '进行中' : '已完成';
            $temp[] = $val;
        }
        return json_encode($temp[0]);
    }

    /**
     * 购买记录撤单
     *
     * @return string
     */
    public function cancelOrder()
    {
        $data = $this->data;
        $_x = $data['data']['x'];
        $_y = $data['data']['y'];
        $uid = session("SESSION_ID");
        $_lotteryNumberMemory = M("lottery_number_memory");
        $_res = $this->where(array("id" => $_y,"userid" => $uid,"buy_id" => $_x, "status" => 0))->find();
        if(empty($_res))
        {
            return false;
            exit();
        }
        else
        {
            $_lotteryNumberId = $_res["lottery_number_id"];
            $_haveNumber = $_lotteryNumberMemory->where(array("id" => $_lotteryNumberId))->find();
            if(!empty($_haveNumber['lottery_number']))
            {
                return false;exit();
            }
            $HOST = C("MG_HOST");
            $PORT = C("MG_PORT");
            $USER = C("MG_USER");
            $PASS = C("MG_PASS");
            $VHOST = C("MG_VHOST");
            $exchange = 'e_front_add_cancel';
            $conn = new AMQPConnection($HOST, $PORT, $USER, $PASS, $VHOST);
            if ($conn) {
                $ch = $conn->channel();
                $msg_body = $_x."-".$_y;
                $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
                $ch->basic_publish($msg, $exchange);
                $ch->close();
                $conn->close();
            }
            return true;
        }
    }

    /**
     * 购买记录撤单
     *
     * @return string
     */
    public function allcancelOrder()
    {
        $data = $this->data;
        $_x = $data['data']['x'];
        $uid = session("SESSION_ID");
        $_lotteryNumberMemory = M("lottery_number_memory");
        $lottery_number_mid = M("lottery_number_mid");
        $_result = $this->where(array("userid" => $uid,"buy_id" => $_x, "status" => 0))->field("id,buy_id,lottery_number_id")->select();
        if(empty($_result))
        {
            return false;
            exit();
        }
        else
        {
            $HOST = C("MG_HOST");
            $PORT = C("MG_PORT");
            $USER = C("MG_USER");
            $PASS = C("MG_PASS");
            $VHOST = C("MG_VHOST");
            foreach($_result as $_res){
                $_lotteryNumberId = $_res["lottery_number_id"];
                $_haveNumber = $_lotteryNumberMemory->where(array("id" => $_lotteryNumberId))->find();
                if(!empty($_haveNumber['lottery_number']))
                {
                    continue;
                }
                $endtime = $lottery_number_mid->where(array("id"=>$_res["lottery_number_id"]))->getField("endtime");
                if($endtime<=time())
                {
                    continue;
                }
                $argv = $_res["buy_id"]."-".$_res["id"];

                $exchange = 'e_front_add_cancel';
                $conn = new AMQPConnection($HOST, $PORT, $USER, $PASS, $VHOST);
                if ($conn) {
                    $ch = $conn->channel();
                    $msg_body = $argv;
                    $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
                    $ch->basic_publish($msg, $exchange);
                    $ch->close();
                    $conn->close();
                }
            }
            return true;
        }
    }
}