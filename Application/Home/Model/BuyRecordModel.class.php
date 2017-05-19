<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/13
 * Time: 下午12:37
 */

namespace Home\Model;


use Think\Model;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
class BuyRecordModel extends Model
{
    protected $trueTableName = 'buy_record';

    public function proxyRecord()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $st = $data['data']['st'];
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_count = $this->where($map)->field("id,serial_number,userid,lottery_id,lottery_number_id,add_number,monetary,buy_time,bonus,status")->count();
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
        $lottery = C("LOTTERY");
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $list = $this->where($map)->field("id,serial_number,userid,lottery_id,lottery_number_id,add_number,monetary,buy_time,bonus,status")->limit($p->firstRow,$p->listRows)->order("id desc")->select();
        $result = array();
        $User = M("user");
        $lotteryNumberMid = M("lottery_number_mid");
        foreach($list as $item)
        {
            $temp= array();
            $temp['id'] = $_xDe->encode($item['id']);
            $temp['userid'] = $item["userid"];
            $temp['add_number'] = $item['add_number'];
            $temp['lottery_number_id'] = $item['lottery_number_id'];
            $temp['serial_number'] = $item['serial_number'];
            $temp['lottery_name'] = $lottery[$item['lottery_id']]['name'];
            $temp['username'] = $User->where(array("id"=>$item['userid']))->getField("username");
            $temp['lottery_serial_number'] = $lotteryNumberMid->where(array("id"=>$item['lottery_number_id']))->getField("series_number");
            $temp['buy_time'] = date("m/d H:i:s",$item['buy_time']);
            $temp['monetary'] = sprintf("%.4f",$item['monetary']/100000);
            $temp['bonus'] = sprintf("%.4f",$item['bonus']/100000);
            $temp['operate'] = '<a style="cursor:pointer" class="c--f35c19" data-field="'.$temp['id'].'" data-method="detail">订单详情</a>';
            $temp['page'] = $page;
            switch($item['status'])
            {
                case 0:
                    $temp['status'] = "未开奖";break;
                case 1:
                    if($item["bonus"]>0)
                        $temp['status'] = "<font style='color:#b23632'>已中奖</font>";
                    else
                        $temp['status'] = "<font style='color:green'>未中奖</font>";
                    break;
                case 2:
                    $temp['status'] = "<font style='color:red'>已撤单</font>";break;

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

    public function selfRecord()
    {
        $data = $this->data;
        $map = $data['data']['where'];
        $st = $data['data']['st'];
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_count = $this->where($map)->field("id,serial_number,userid,buy_type_id,yuan,lottery_id,lottery_number_id,add_number,monetary,bonus,status,multiple")->count();
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
        $lottery = C("LOTTERY");
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $list = $this->where($map)->field("id,serial_number,userid,buy_type_id,yuan,lottery_id,lottery_number_id,add_number,monetary,bonus,status,multiple")->limit($p->firstRow,$p->listRows)->order("id desc")->select();
        $result = array();
        $lotteryNumberMid = M("lottery_number_mid");
        $lottery_play_way = M("lottery_play_way");
        foreach($list as $item)
        {
            $temp= array();
            $temp['id'] = $_xDe->encode($item['id']);
            $temp['serial_number'] = $item['serial_number'];//订单号
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
                $temp['operate'] = '<a style="cursor:pointer" class="c--f35c19" data-field="'.$temp['id'].'" data-method="detail">详情</a><a style="cursor:pointer" class="c--f35c19 ml--10" data-field="'.$temp['id'].'" data-method="cancel"">撤销</a>';
            else
                $temp['operate'] = '<a class="c--f35c19" style="cursor:pointer" data-field="'.$temp['id'].'" data-method="detail">详情</a>';
            $temp['page'] = $page;
            switch($item['status'])
            {
                case 0:
                    $temp['status'] = "未开奖";break;
                case 1:
                    if($item["bonus"]>0)
                        $temp['status'] = "<span style='color:#b23632'>已中奖</span>";
                    else
                        $temp['status'] = "<span style='color:green'>未中奖</span>";
                    break;
                case 2:
                    $temp['status'] = "<span style='color:red'>已撤单</span>";break;

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
        import("Class.XDeode");
        $_xDe=new \XDeode();
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
        $_List = $this->join("lottery_number on buy_record.lottery_number_id=lottery_number.id")
            ->join("lottery_play_way on buy_record.buy_type_id = lottery_play_way.id")->field($_Field)
            ->where(array( "buy_record.id" => $_bid))->select();
        $_caipiao_arr = C("LOTTERY");
        $temp = array();
        $User = M("user");
        foreach($_List as $val) {
            $val["username"] = $User->where(array("id"=>$val["userid"]))->getField("username");
            $val["nomallid"] = $_xDe->encode($val["id"]);
            $val["lotteryName"] = $_caipiao_arr[$val["lottery_id"]]["name"];
            $val["monetary"] = sprintf("%.4f",$val["monetary"]/100000);
            $val["bonus"] = sprintf("%.4f",$val["bonus"]/100000);

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
            if($val["endtime"]>time()) {
                if($val["status"]==2)
                    $val["cedan"] = 0;
                else
                    $val["cedan"] = 1;
            }else
                $val["cedan"] = 0;
            if($val["status"]==0)
                $val["status"] = "未开奖";
            else if($val["status"]==1){
                if($val["bonus"]>0){
                    $val["status"] = "已中奖";
                } else if($val["bonus"]==0) {
                    $val["status"] = "未中奖";
                }
            } else if($val["status"]==2) {
                $val["status"] = $val["cancelRemark"];
            } else if($val["status"]==3){
                $val["status"] = "可疑单";
            }
            if($val["yuan"]==0)
                $val["model"] = "元";
            else if($val["yuan"]==1)
                $val["model"] = "角";
            else if($val["yuan"]==2)
                $val["model"] = "分";
            else if($val["yuan"]==3)
                $val["model"] = "厘";
                
            if(in_array($val['buy_type_id'],array(234,235,236,237,238,239,240,241,242,243)))
            {
                $val["fd"] = "龙[1:2]和[1:8]虎[1:2]";
            }
            else
            {
                $_b_arr = explode("|",$val["bonusType"]);
                if(strpos($_b_arr[0],"-")!==false){
                    $_a = explode("-",$_b_arr[0]);
                    $val["fd"] = sprintf("%.2f",$_a[0]);
                } else {
                    $val["fd"] = sprintf("%.2f",$_b_arr[0]);
                }
            }
            
            $val["lottery_number"] = (empty($val["lottery_number"])) ? "暂未开奖" : $val["lottery_number"];
            if($val["buy_type_id"]==33){
                $buyNumber = explode("|",$val["buy_number"]);
                $str="";
                if(!empty($buyNumber[0]))
                    $str .= "万,";
                if(!empty($buyNumber[1]))
                    $str .= "千,";
                if(!empty($buyNumber[2]))
                    $str .= "百,";
                if(!empty($buyNumber[3]))
                    $str .= "十,";
                if(!empty($buyNumber[4]))
                    $str .= "个,";
                $str = substr($str,0,strlen($str)-1);
                $val["position"] = $str;
            } else {
                $val["position"] = (empty($val["position"])) ? "——" : $val["position"];
            }
            $val["buy_time"] = date("m/d H:i:s",$val["buy_time"]);
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
        $_bid = $data['data']['buyid'];
        $uid = session("SESSION_ID");
        $_lotteryNumberMemory = M("lottery_number_memory");
        $_res = $this->where(array("id" => $_bid,"userid" => $uid,"add_number" => 0, "status" => 0))->find();
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
            $exchange = 'e_front_nomall_cancel';
            $conn = new AMQPConnection($HOST, $PORT, $USER, $PASS, $VHOST);
            if ($conn) {
                $ch = $conn->channel();
                $msg_body = $_bid;
                $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
                $ch->basic_publish($msg, $exchange);
                $ch->close();
                $conn->close();
            }
            return true;
        }
    }
}