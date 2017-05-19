<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/11
 * Time: 上午8:16
 */

namespace Home\Controller;

use Home\Model\MemverModel as Memver;
use Home\Model\UserModel as User;
use Home\Model\TransferRecordModel as TransferRecord;
use Home\Model\FoundChangeMongoModel as FoundChange;
class MemberController extends CommonController
{

    public function index()
    {
        $this->menu = "proxy";
        $this->managermenu = 'proxy';
        $this->proxymanager = 'members';
        $uid = session("SESSION_ID");

        // 查询分红契约签订的标准
        $res = M("favourable")->where(array("type"=>2))->field("mingainrate,maxgainrate")->find();
        if(session("SESSION_ROLE")==3)
        {
            $this->maxGainRate = $res['maxgainrate'];
            $this->minGainRate = $res['mingainrate'];
        }
        else if(session("SESSION_ROLE")==4)
        {
            $sharecontract = M("sharecontract");
            $this->maxGainRate = $sharecontract->where(array("secondparty"=>$uid))->min('receive');
            $this->minGainRate = $res['mingainrate'];
            $this->dailysales = $sharecontract->where(array("secondparty"=>$uid))->min('dailysales');
            $this->loss = $sharecontract->where(array("secondparty"=>$uid))->min('loss');
            $this->receive = $sharecontract->where(array("secondparty"=>$uid))->min('receive');
        }

        // 查询日工资的标准
        $dayres = M("favourable")->where(array("type"=>3,"userid"=>$uid))->field("dayrate,numberofbet,moneyofbet")->find();
        if(session("SESSION_ROLE")==3)
        {
            $this->dailysales = 1;
            $this->dayrate = $dayres['dayrate'];
            $this->numberofbet = $dayres['numberofbet'];
            $this->moneyofbet = $dayres['moneyofbet'];
        }
        else if(session("SESSION_ROLE")==4)
        {
            $dayratecontract = M("dayratecontract");
            $this->dailysales = M("dayratecontract")->where(array("secondparty"=>$uid))->min('dailysales');
            $this->dayrate = M("dayratecontract")->where(array("secondparty"=>$uid))->min('reward');
            $this->numberofbet = M("dayratecontract")->where(array("secondparty"=>$uid))->min('activepeople');
            $this->moneyofbet = M("dayratecontract")->where(array("secondparty"=>$uid))->min('activebet');
        }
        $this->display();
    }

    public function searchMember()
    {
        $uid = I("post.uid");
        $username = rtrim(I("post.username",""));
        $starttime = I("post.starttime","");
        $endtime = I("post.endtime","");
        $mincapital = I("post.mincapital","");
        $maxcapital = I("post.maxcapital","");

        $where['username'] = (!empty($username)) ? $username : "";
        $where['reg_time'] = (!empty($starttime) && !empty($endtime)) ? array(array('gt',strtotime($starttime)),array('lt',strtotime($endtime))) : "";
        $where['cur_account'] = (!empty($mincapital) && !empty($maxcapital)) ? array(array('gt',$mincapital*100000),array('lt',$maxcapital*100000)) : "";
        if(!empty($username))
        {
            $parentId = M("user")->where(array("username"=>$username))->getField("parent_id");
            $where["parent_id"] = $parentId;
        }
        else
        {
            $where["parent_id"] = (empty($uid)) ? session("SESSION_ID") : $uid;
        }
        $where['_string'] = "FIND_IN_SET(".session("SESSION_ID").",parent_path)";
        foreach($where as $k=>$v)
        {
            if(!$v)
            {
                unset($where[$k]);
            }
        }
        $Memver = new Memver();
        $Memver->data = array("where"=>$where);
        echo $Memver->searchMemebr();
    }

    /**
     * 分配配额初始
     */
    public function setAccount()
    {
        $pd = I("post.pd");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $User = new User();
        $User->data = array("pd"=>$_xDe->decode($pd));
        if($User->isTrueUser())
        {
            $user_bonus = M("user_bonus");
            $myid = session("SESSION_ID");
            $childId = $_xDe->decode($pd);
            import("Class.RedisObject");
            $redisObj = new \RedisObject();
            $key = md5("userbonus".$childId);
            if($redisObj->exists($key))
            {
                $userBonusInfo = json_decode($redisObj->_get($key), true);
            }
            else
            {
                $userBonusInfo = M("user_bonus")->where(array('userid'=>$childId))->find();
                $redisObj->_set($key,json_encode($userBonusInfo));
            }
            $childBonusLevel = $userBonusInfo["bonus_level"];
            $temp = array();
            $INTERVAL = C("INTERVAL");
            if (bccomp($childBonusLevel, 12.9, 1) == 0) {
                $intevalInfoConfig = $INTERVAL;
                $temp = array(1, 2, 3, 4, 5);
            } else if (bccomp($childBonusLevel, 12.8, 1) == 0) {
                $intevalInfoConfig = $INTERVAL;
                $temp = array(2, 3, 4, 5);
            } else if (bccomp($childBonusLevel, 12.7, 1) == 0) {
                $temp = array(3, 4, 5);
                unset($INTERVAL[1]);
                unset($INTERVAL[2]);
                $intevalInfoConfig = $INTERVAL;
            } else if (bccomp($childBonusLevel, 12.6, 1) == 0) {
                $temp = array(4, 5);
                unset($INTERVAL[1]);
                unset($INTERVAL[2]);
                unset($INTERVAL[3]);
                $intevalInfoConfig = $INTERVAL;
            } else if (bccomp($childBonusLevel, 12.5, 1) == 0) {
                $temp = array(5);
                unset($INTERVAL[1]);
                unset($INTERVAL[2]);
                unset($INTERVAL[3]);
                unset($INTERVAL[4]);
                $intevalInfoConfig = $INTERVAL;
            } else if (bccomp($childBonusLevel, 12.4, 1) == 0) {
                $intevalInfoConfig = null;
            } else {
                $intevalInfoConfig = null;
            }
            $innerHtml = '';
            foreach($intevalInfoConfig as $key=>$item)
            {
                if(in_array($key,$temp))
                {
                    $checknum = getIntervalCount($key,$myid);
                    if(!empty($checknum))
                    {
                        $innerHtml .= '<tr>';
                        $innerHtml .= '<td class="text-center" style="vertical-align:middle">['.$item.']</td>';
                        $innerHtml .= '<td class="text-center" style="vertical-align:middle">'.$checknum.'</td>';
                        $num2 = getIntervalCount($key,$childId);
                        if(!empty($num2))
                            $innerHtml .= '<td class="text-center" style="vertical-align:middle">'.$num2.'</td>';
                        else
                            $innerHtml .= '<td class="text-center" style="vertical-align:middle">0</td>';
                        $innerHtml .= '<td class="text-center"><div class="input-group"><input type="text" style="width:100px;height:20px;line-height:20px" autocomplete="off" onkeyup="this.value=this.value.replace(/[^\d]/g,\'\');" onchange="this.value=this.value.replace(/[^\d]/g,\'\');" value="0" name="add_interval_'.$key.'" ></div></td>';
                        $innerHtml .= '<td class="text-center"><div class="input-group"><input  style="width:100px;height:20px;line-height:20px" type="text" autocomplete="off" onkeyup="this.value=this.value.replace(/[^\d]/g,\'\');" onchange="this.value=this.value.replace(/[^\d]/g,\'\');" value="0" name="desc_interval_'.$key.'" ></div></td>';
                        $innerHtml .= '</tr>';
                    }
                }
            }
            if($innerHtml=="")
            {
                $innerHtml .= '<tr><td class="text-center" colspan="6" style="vertical-align:middle">暂无可用配额进行分配</td></tr>';
                $innerHtml .= '<input type="hidden" name="isCanable" value="false"/>';
            }
            else
            {
                $innerHtml .= '<input type="hidden" name="isCanable" value="true"/>';
                $innerHtml .= '<input type="hidden" name="quateChildId" value="'.$childId.'"/>';
            }
            echo $innerHtml;
        }
        else
        {
            exit();
        }
    }

    public function fenpaiHandler()
    {
        $childid = I("post.quateChildId");
        $parentid = session("SESSION_ID");
        $userinfo = M("user")->where(array("id"=>$childid))->find();
        $parentinfo = M("user")->where(array("id"=>$parentid))->find();
        $add_interval_1 = I("request.add_interval_1",0);
        $add_interval_2 = I("request.add_interval_2",0);
        $add_interval_3 = I("request.add_interval_3",0);
        $add_interval_4 = I("request.add_interval_4",0);
        $add_interval_5 = I("request.add_interval_5",0);

        $desc_interval_1 = I("request.desc_interval_1",0);
        $desc_interval_2 = I("request.desc_interval_2",0);
        $desc_interval_3 = I("request.desc_interval_3",0);
        $desc_interval_4 = I("request.desc_interval_4",0);
        $desc_interval_5 = I("request.desc_interval_5",0);

        $User = new User();
        $User->data = array("pd"=>$childid);
        $user_interval = M("user_interval");
        $interval_change = M("interval_change");
        if($User->isTrueUser())
        {
            //下级配额增加
            if(FALSE == $res = $user_interval->where(array("userid"=>$childid))->find()){
                $data = array(
                    "userid"=>$childid,
                    "parent_id"=>$parentid,
                    "parent_path"=>$userinfo["parent_path"],
                    "interval_1"=>0,
                    "interval_2"=>0,
                    "interval_3"=>0,
                    "interval_4"=>0,
                    "interval_5"=>0,
                    "interval_time" => time()
                );
                $user_interval->data($data)->add();
            }
            for($i=1;$i<=5;$i++){
                $addname = "add_interval_".$i;
                $descname = "desc_interval_".$i;
                $name = "interval_".$i;
                $sm = $$addname-$$descname;
                if($sm==0) continue;
                if($sm >0 ){
                    //上级减少相应的配额
                    $parentintval = $user_interval->where(array("userid"=>$parentid))->getField($name);
                    if($parentintval <=0 OR $sm>$parentintval){
                        echo "上级在该区间的配额不足！";
                        exit();
                    } else {
                        $past_interval = $user_interval->where(array("userid"=>$childid))->getField($name);
                        M()->execute("UPDATE user_interval SET $name=$name+".$sm." WHERE userid=".$childid);
                        $current_interval = $user_interval->where(array("userid"=>$childid))->getField($name);
                        unset($account);
                        $account = array(
                            "userid" => $childid,
                            "username" => $userinfo["username"],
                            "parent_id" => $userinfo["parent_id"],
                            "parent_path" => $userinfo["parent_path"],
                            "relation_userid" => $parentid,
                            "relation_username"=>$parentinfo["username"],
                            "interval_range" => $i,
                            "remark"  => 5,
                            "past_interval" => $past_interval,
                            "inr_interval" => $sm,
                            "dec_interval" => 0,
                            "current_interval" => $current_interval,
                            "interval_time" => time()
                        );
                        $interval_change->add($account);
                        //上级在该区间减去相应的配额
                        $parent_past_interval = $user_interval->where(array("userid"=>$parentid))->getField($name);
                        M()->execute("UPDATE user_interval SET $name=$name-".$sm." WHERE userid=".$parentid);
                        $parent_current_interval = $user_interval->where(array("userid"=>$parentid))->getField($name);
                        //增加一条帐变
                        unset($account);
                        $account = array(
                            "userid" => $parentid,
                            "username" => $parentinfo["username"],
                            "parent_id" => $parentinfo["parent_id"],
                            "parent_path" => $parentinfo["parent_path"],
                            "relation_userid" => $childid,
                            "relation_username"=>$userinfo["username"],
                            "interval_range" => $i,
                            "remark"  => 4,
                            "past_interval" => $parent_past_interval,
                            "inr_interval" => 0,
                            "dec_interval" => $sm,
                            "current_interval" => $parent_current_interval,
                            "interval_time" => time()
                        );
                        $interval_change->add($account);
                    }
                }
                else if($sm <0)
                {
                    $sm = abs($sm);
                    //下级返还相应的配额
                    $parentintval = $user_interval->where(array("userid"=>$childid))->getField($name);
                    if($parentintval <=0 OR $sm>$parentintval){
                        echo "下级在该区间的配额不足！";
                        exit();
                    } else {
                        $past_interval = $user_interval->where(array("userid"=>$childid))->getField($name);
                        M()->execute("UPDATE user_interval SET $name=$name-".$sm." WHERE userid=".$childid);
                        $current_interval = $user_interval->where(array("userid"=>$childid))->getField($name);
                        unset($account);
                        $account = array(
                            "userid" => $childid,
                            "username" => $userinfo["username"],
                            "parent_id" => $userinfo["parent_id"],
                            "parent_path" => $userinfo["parent_path"],
                            "relation_userid" => $parentid,
                            "relation_username"=>$parentinfo["username"],
                            "interval_range" => $i,
                            "remark"  => 6,
                            "past_interval" => $past_interval,
                            "inr_interval" => 0,
                            "dec_interval" => $sm,
                            "current_interval" => $current_interval,
                            "interval_time" => time()
                        );
                        $interval_change->add($account);
                        //上级在该区间增加相应的配额 回收配额
                        $parent_past_interval = $user_interval->where(array("userid"=>$parentid))->getField($name);
                        M()->execute("UPDATE user_interval SET $name=$name+".$sm." WHERE userid=".$parentid);
                        $parent_current_interval = $user_interval->where(array("userid"=>$parentid))->getField($name);
                        //增加一条帐变
                        unset($account);
                        $account = array(
                            "userid" => $parentid,
                            "username" => $parentinfo["username"],
                            "parent_id" => $parentinfo["parent_id"],
                            "parent_path" => $parentinfo["parent_path"],
                            "relation_userid" => $childid,
                            "relation_username"=>$userinfo["username"],
                            "interval_range" => $i,
                            "remark"  => 7,
                            "past_interval" => $parent_past_interval,
                            "inr_interval" => $sm,
                            "dec_interval" => 0,
                            "current_interval" => $parent_current_interval,
                            "interval_time" => time()
                        );
                        $interval_change->add($account);
                    }
                }
            }
            echo "开户配置分配成功";
            exit();
        }
        else
        {
            echo "开户配置失败";
            exit();
        }
    }

    // 转账推送
    public static function sendWebSocketMsg($content)
    {
        $push_api_url = "http://".C("TUISONG_HOST").":2121/";
        $post_data = $content;
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $push_api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_HEADER, 0 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_data );
        $result = curl_exec ( $ch );
        curl_close ( $ch );
        return $result;
    }

    public function transfer()
    {
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $_pwd = I("post.p","");
        $_type = I("post.t");
        $_u = I("post.u","");
        $_id = $_xDe->decode($_u);
        $_m = I("post.m","");
        $_money = $_m*100000;
        $_uid = session("SESSION_ID");
        $_name = session("SESSION_NAME");
        $_nickname = session("SESSION_NICKNAME");
        $_parentId = session("SESSION_PARENTID");
        $_parentPath = session("SESSION_PATH");

        $FoundChange = new FoundChange();

        $ip = ip2long(get_client_ip());
        $MD5STR = md5($_name.$_uid.$ip."zhuanzhang");
        //防止重复提交
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        if($redisObj->_get($MD5STR)){
            echo '转账信息已经提交！';
            exit();
        }else{
            $redisObj->_set($MD5STR,1);
            $redisObj->_expire($MD5STR,2);
        }

        //验证数据的合法性
        if(empty($_pwd) || empty($_u) || empty($_m) || empty($_type)){
            echo "提交数据参数有误";
            exit();
        }

        if(!preg_match("/^\d+$/",$_m)){
            echo "提交金额有误,请重新提交";
            exit();
        }
        $userModel = M("user");
        $accountChange = M("accounts_change");

        //接受转账的用户信息
        $_where["_string"] = "FIND_IN_SET(".$_uid.",parent_path)";
        $_where["id"] = $_id;
        $_where["parent_id"] = $_uid;
        $_info = $userModel->where($_where)->find();
        if(empty($_info)){
            echo "接受转账的用户不存在";
            exit();
        }
        $_result = $userModel->where(array("id" => $_uid))->find();

        if($_uid!=$_info["parent_id"])
        {
            if($_result["recharge_fun"]!=1){
                echo "你未开通转账功能";
                exit();
            }
        }


        $acc_password_new = md5(md5($_pwd.C("PASSWORD_HALT")).$_result["safe_key"]);
        if($acc_password_new != $_result["acc_password"]){
            echo "资金密码输入不正确";
            exit();
        }

        if($_id==$_uid || empty($_uid)){
            echo "转账失败";
            exit();
        }

        //启动事务
        $userModel->startTrans();

        //找出自己的资金
        $_from = $userModel->db(0)->lock(true)->where(array("id" => $_uid))->getField("cur_account");
        //从自己的资金里面扣除转账的钱
        $_a = $_from-$_money;
        if(bccomp($_a,0,4)==-1){
            echo "你的账户金额不足，转账失败!";
            exit();
        }

        // 判断是否为活动转账
        if($_type==2)
        {
            $activeRemark = "活动";
        }
        else if($_type==1)
        {
            $activeRemark = "普通";
        }
        $TransferRecord = new TransferRecord();
        //更新自己的账户
        $_f_1 = $userModel->db(0)->where(array("id" => $_uid))->save(array("cur_account"=>$_a));
        //账变
        $_change_data = array(
            "accounts_type" => 15,  //转账转出
            "buy_record_id" => 0,
            "change_amount" => $_money,
            "userid" => $_uid,
            "username" => $_name,
            "parent_id" => $_parentId,
            "parent_path" => $_parentPath,
            "cur_account" => $_a,
            "serial_number" => 0,
            "runner_id" => $_info["id"],
            "runner_name" => $_info["username"],
            "change_time" => time(),
            "is_addto" => 0,
            "remark"	=> "给下级".$_info["username"]."{$activeRemark}转账"
        );
        $_account_change_id = $accountChange->add($_change_data);
        //更新该条账变的账变编号
        $achange_num = strtoupper($_xDe->encode($_account_change_id));
        $accountChange->where(array("id" =>$_account_change_id))->save(array("achange_num"=>$achange_num));
        
        // 转出用户的转账记录添加
        $TransferRecord->data = array('add'=>array(
            "userid" => $_uid
            ,"username" => $_name
            ,"parent_id" => $_parentId
            ,"parent_path" => $_parentPath
            ,"beforeAmount" => $_from
            ,"amount" => $_money
            ,"afterAmount" => $_a
            ,"type" => "资金转出"
            ,"remark" => "给下级".$_info["username"]."{$activeRemark}转账"
            ,"time" => time()
            ,"status" => 1
            ,"isThird" =>0
            ,"toUserid" => $_info["id"]
            ,"toUsername" => $_info["username"]
        ));
        $TransferRecord->addTransferRecord();
        
        // 增加资金变化
        $FoundChangeData = array();
        $FoundChangeData['userid'] = $_uid;
        $FoundChangeData['username'] = $_name;
        $FoundChangeData['parent_id'] = $_parentId;
        $FoundChangeData['parent_path'] = $_parentPath;
        $FoundChangeData['beforeMoney'] = intval($_from);
        $FoundChangeData['money'] = intval($_money);
        $FoundChangeData['afterMoney'] = intval($_a);
        $FoundChangeData['time'] = time();
        $FoundChangeData['remark'] = "给下级".$_info["username"]."{$activeRemark}转账";
        $FoundChange->add($FoundChangeData);

        //查出接受转账的用户的金额
        $_b = $_info["cur_account"];
        //接受转账之后的金额
        $_b_1 = $_b+$_money;
        //更新接受转账的用户金额
        $_f_2 = $userModel->where(array("id" => $_id))->save(array("cur_account"=>$_b_1));
        //账变
        $_change_z_data = array(
            "accounts_type" => 16,  //转账转入
            "buy_record_id" => 0,
            "change_amount" => $_money,
            "userid" => $_info["id"],
            "username" => $_info["username"],
            "parent_id" => $_info["parent_id"],
            "parent_path" => $_info["parent_path"],
            "cur_account" => $_b_1,
            "serial_number" => 0,
            "runner_id" => $_uid,
            "runner_name" => $_name,
            "change_time" => time(),
            "is_addto" => 0,
            "remark"	=> "获得上级".$_name."{$activeRemark}转账"
        );
        $_account_z_change_id = $accountChange->add($_change_z_data);
        //更新该条账变的账变编号
        $achange_z_num = strtoupper($_xDe->encode($_account_z_change_id));
        $accountChange->where(array("id" =>$_account_z_change_id))->save(array("achange_num"=>$achange_z_num));

        // 转出用户的转账记录添加
        $TransferRecord->data = array('add'=>array(
            "userid" => $_info["id"]
            ,"username" => $_info["username"]
            ,"parent_id" => $_info["parent_id"]
            ,"parent_path" => $_info["parent_path"]
            ,"beforeAmount" => $_b
            ,"amount" => $_money
            ,"afterAmount" => $_b_1
            ,"type" => "资金转入"
            ,"remark" => "获得上级".$_name."{$activeRemark}转账"
            ,"time" => time()
            ,"status" => 1
            ,"isThird" =>0
            ,"toUserid" => $_uid
            ,"toUsername" => $_name
        ));
        $TransferRecord->addTransferRecord();

        // 增加资金变化
        $FoundChangeData = array();
        $FoundChangeData['userid'] = $_info["id"];
        $FoundChangeData['username'] = $_info["username"];
        $FoundChangeData['parent_id'] = $_info["parent_id"];
        $FoundChangeData['parent_path'] = $_info["parent_path"];
        $FoundChangeData['beforeMoney'] = intval($_b);
        $FoundChangeData['money'] = intval($_money);
        $FoundChangeData['afterMoney'] = intval($_b_1);
        $FoundChangeData['time'] = time();
        $FoundChangeData['remark'] = "获得上级".$_name."{$activeRemark}转账";
        $FoundChange->add($FoundChangeData);

        if($_type==2)
        {
            // 如果是活动转账，那么金额要进入报表
            _team_report(array($_info["id"],0,0,0,0,0,$_money,0));
        }

        if($_f_1 !== false && $_f_2 !== false){
            $userModel->commit();
            M("parent_transfer")->data(array("parent_id"=>$_uid,"userid"=>$_info["id"],"parent_path"=>$_info["parent_path"],"money"=>$_money,"time"=>time()))->add();
            $currentTime = time();
            $msg = $_nickname."转入金额:".sprintf("%.4f",$_money/100000)."元";
            M('system_message')->add(array(
                "userid" => $_info["id"],
                "type" => 2,
                "content" => "获得上级转账".sprintf('%.4f',$_money/100000)."元",
                "date" => $currentTime,
                "status" => 0
            ));
            $send = $msg."-2-".$currentTime;
            $content = array('type'=>"inbox","to"=>$_info["id"],'content'=>$send);
            $result = self::sendWebSocketMsg($content);
            
            echo "转账成功";
        } else {
            $userModel->rollback();
            echo "转账失败";
        }
    }

    /**
     * 配额开户 获取配额信息
     */
    public function quoatInfo()
    {
        $pd = I("post.xd");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $User = new User();
        $User->data = array("pd"=>$_xDe->decode($pd));
        $temparr = '';
        if($User->isTrueUser())
        {
            $user_bonus = M("user_bonus");
            $myid = session("SESSION_ID");
            $childId = $_xDe->decode($pd);
            import("Class.RedisObject");
            $redisObj = new \RedisObject();
            $key = md5("userbonus".$childId);
            if($redisObj->exists($key))
            {
                $userBonusInfo = json_decode($redisObj->_get($key), true);
            }
            else
            {
                $userBonusInfo = M("user_bonus")->where(array('userid'=>$childId))->find();
                $redisObj->_set($key,json_encode($userBonusInfo));
            }
            $childBonusLevel = $userBonusInfo["bonus_level"];
            $key = md5("userbonus".$myid);
            if($redisObj->exists($key))
            {
                $userBonusInfo = json_decode($redisObj->_get($key), true);
            }
            else
            {
                $userBonusInfo = M("user_bonus")->where(array('userid'=>$myid))->find();
                $redisObj->_set($key,json_encode($userBonusInfo));
            }
            $myBounsLevel = $userBonusInfo["bonus_level"];
            $temp = array();
            $INTERVAL = C("INTERVAL");
            if (bccomp($myBounsLevel, 12.9, 1) == 0) {
                $intevalInfoConfig = $INTERVAL;
                $temp = array(1, 2, 3, 4, 5);
            } else if (bccomp($myBounsLevel, 12.8, 1) == 0) {
                $intevalInfoConfig = $INTERVAL;
                $temp = array(2, 3, 4, 5);
            } else if (bccomp($myBounsLevel, 12.7, 1) == 0) {
                $temp = array(3, 4, 5);
                unset($INTERVAL[1]);
                unset($INTERVAL[2]);
                $intevalInfoConfig = $INTERVAL;
            } else if (bccomp($myBounsLevel, 12.6, 1) == 0) {
                $temp = array(4, 5);
                unset($INTERVAL[1]);
                unset($INTERVAL[2]);
                unset($INTERVAL[3]);
                $intevalInfoConfig = $INTERVAL;
            } else if (bccomp($myBounsLevel, 12.5, 1) == 0) {
                $temp = array(5);
                unset($INTERVAL[1]);
                unset($INTERVAL[2]);
                unset($INTERVAL[3]);
                unset($INTERVAL[4]);
                $intevalInfoConfig = $INTERVAL;
            } else if (bccomp($myBounsLevel, 12.4, 1) == 0) {
                $intevalInfoConfig = null;
            } else {
                $intevalInfoConfig = null;
            }
            foreach($intevalInfoConfig as $key=>$item)
            {
                if(in_array($key,$temp))
                {
                    $checknum = getIntervalCount($key,$myid);
                    if(!empty($checknum))
                    {
                        $num2 = getIntervalCount($key,$childId);
                        $num2 = (!empty($num2)) ? $num2 : 0;
                        $temparr[] = array("p"=>$item,"m"=>$checknum,"y"=>$num2,"yb"=>$childBonusLevel,"mb"=>$myBounsLevel);
                    }
                }
            }
            echo json_encode($temparr);
        }
        else
        {
            echo null;
            exit();
        }
    }

    /**
     * 用户升点
     */
    public function upperUserPoint()
    {
        $quoatType = I("post.quoatType");
        $point = I("post.point");
        $point = sprintf("%.1f",$point);
        $xd = I("post.xd");
        if($quoatType=="" || $point=="" || $xd=="") exit();
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $id = $_xDe->decode($xd);
        $uid = session("SESSION_ID");
        $map["_string"] = "FIND_IN_SET(".$uid.",parent_path)";
        $map["id"] = $id;
        $userModel = M("user");
        $res = $userModel->where($map)->find();
        if(!empty($res))
        {
            $userBonusModel = M("user_bonus");
            $user_interval = M("user_interval");
            $interval_change = M("interval_change");
            //禁止开设统计返点
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
            $now_bonus_level = $userBonusInfo["bonus_level"];
            if(bccomp($now_bonus_level,$point,3)==-1){
                echo "返点设置不正确";
                exit();
            }
            else
            {
                //检查该返点是否高于上级的返点
                $key = md5("userbonus".$res["parent_id"]);
                if($redisObj->exists($key))
                {
                    $userBonusInfo = json_decode($redisObj->_get($key), true);
                }
                else
                {
                    $userBonusInfo = M("user_bonus")->where(array('userid'=>$res["parent_id"]))->find();
                    $redisObj->_set($key,json_encode($userBonusInfo));
                }
                $parentBonus = $userBonusInfo["bonus_level"];
                //检查下级的返点
                $childBonus = $userBonusModel->field("bonus_level")->where("FIND_IN_SET(".$res["id"].",parent_path)")->select();
                $temp = array();
                foreach($childBonus as $val){
                    $temp[] = $val;
                }
                $childMaxBonus = $temp[array_search(max($temp), $temp)];
                if(bccomp($point,$parentBonus,3)==1 || bccomp($point,$childMaxBonus,3)==-1){
                    echo "返点设置错误,编辑返点失败";
                    exit();
                }
                $key = md5("userbonus".$id);
                if($redisObj->exists($key))
                {
                    $userBonusInfo = json_decode($redisObj->_get($key), true);
                }
                else
                {
                    $userBonusInfo = M("user_bonus")->where(array('userid'=>$id))->find();
                    $redisObj->_set($key,json_encode($userBonusInfo));
                }
                $currentpoint = $userBonusInfo["bonus_level"];
                $intervalRange = C("INTERVAL");
                $pastInterval="";
                $pointInterval = "";
                foreach ($intervalRange as $k=>$v){
                    if($v==$currentpoint){
                        $pastInterval = $k;
                        break;
                    }
                }
                foreach ($intervalRange as $k=>$v){
                    if($v==$point){
                        $pointInterval = $k;
                        break;
                    }
                }

                $userInfo = $res;

                if(bccomp($point,12.4,3)!=-1 && $quoatType==1 && bccomp($point,12.8,3)!=0){
                    $po = $user_interval->where(array("userid"=>$uid))->getField("interval_".$pointInterval);
                    if((int)$po==0){
                        echo "相应区间配额不足";
                        exit();
                    }
                    if($pastInterval!=""){
                        //将相应的区间配额加1
                        unset($past_interval_bak);
                        $past_interval_bak = $user_interval->where(array("userid"=>$uid))->getField("interval_".$pastInterval);
                        $current_interval_bak = $past_interval_bak+1;
                        M()->execute("UPDATE user_interval SET interval_".$pastInterval."=interval_".$pastInterval."+1 WHERE userid=".$uid);
                        unset($data);
                        //产生一个帐变过程
                        $data = array(
                            "userid" => $uid,
                            "username" => session("SESSION_NAME"),
                            "parent_id" => session("SESSION_PARENTID"),
                            "parent_path" => session("SESSION_PATH"),
                            "relation_username" => $userInfo["username"],
                            "relation_userid" => $userInfo["id"],
                            "interval_range" => $pastInterval,
                            "remark" => 11,
                            "past_interval" => $past_interval_bak,
                            "inr_interval" => 1,
                            "dec_interval" => 0,
                            "current_interval" => $current_interval_bak,
                            "interval_time" => time()
                        );
                        $interval_change->add($data);
                    }

                    //将相应的区间配额减1
                    unset($past_interval_bak_dec);
                    $past_interval_bak_dec = $user_interval->where(array("userid"=>$uid))->getField("interval_".$pointInterval);
                    $current_interval_bak_dec = $past_interval_bak_dec-1;
                    M()->execute("UPDATE user_interval SET interval_".$pointInterval."=interval_".$pointInterval."-1 WHERE userid=".$uid);
                    unset($data);
                    //产生一个帐变过程
                    $data = array(
                        "userid" => $uid,
                        "username" => session("SESSION_NAME"),
                        "parent_id" => session("SESSION_PARENTID"),
                        "parent_path" => session("SESSION_PATH"),
                        "relation_username" => $userInfo["username"],
                        "relation_userid" => $userInfo["id"],
                        "interval_range" => $pointInterval,
                        "remark" => 10,
                        "past_interval" => $past_interval_bak_dec,
                        "inr_interval" => 0,
                        "dec_interval" => 1,
                        "current_interval" => $current_interval_bak_dec,
                        "interval_time" => time()
                    );
                    $interval_change->add($data);
                    //插入用户奖金信息表
                    $reward_array = C("REWARDS");
                    $rewardKey = strval($point);
                    $key = md5("userbonus".$id);
                    if($redisObj->exists($key))
                    {
                        $userBonusInfo = json_decode($redisObj->_get($key), true);
                    }
                    else
                    {
                        $userBonusInfo = M("user_bonus")->where(array('userid'=>$id))->find();
                        $redisObj->_set($key,json_encode($userBonusInfo));
                    }
                    $last_bonus_level = $userBonusInfo["bonus_level"];
                    $user_bonus_data = array(
                        "bonus_content" => $reward_array[$rewardKey],
                        "bonus_level" => $point,
                        "last_bonus_level"=>$last_bonus_level,
                        "incleveltype" => "配额升点",
                        "incleveltime" => time()
                    );
                    $userBonusModel->where(array("userid"=>$id))->save($user_bonus_data);
                    if($redisObj->exists($key))
                    {
                        $redisObj->_delete($key);
                    }
                } else if(bccomp($point,12.4,3)==-1 && $quoatType==2) {
                    //插入用户奖金信息表
                    $reward_array = C("REWARDS");
                    $rewardKey = strval($point);
                    $key = md5("userbonus".$id);
                    if($redisObj->exists($key))
                    {
                        $userBonusInfo = json_decode($redisObj->_get($key), true);
                    }
                    else
                    {
                        $userBonusInfo = M("user_bonus")->where(array('userid'=>$id))->find();
                        $redisObj->_set($key,json_encode($userBonusInfo));
                    }
                    $last_bonus_level = $userBonusInfo["bonus_level"];
                    $user_bonus_data = array(
                        "bonus_content" => $reward_array[$rewardKey],
                        "bonus_level" => $point,
                        "last_bonus_level"=>$last_bonus_level,
                        "incleveltype" => "普通升点",
                        "incleveltime" => time()
                    );
                    $userBonusModel->where(array("userid"=>$id))->save($user_bonus_data);
                    if($redisObj->exists($key))
                    {
                        $redisObj->_delete($key);
                    }
                } else if(bccomp($point,12.8,3)==0 && $quoatType==1)
                {
                    //插入用户奖金信息表
                    $reward_array = C("REWARDS");
                    $rewardKey = strval($point);
                    $key = md5("userbonus".$id);
                    if($redisObj->exists($key))
                    {
                        $userBonusInfo = json_decode($redisObj->_get($key), true);
                    }
                    else
                    {
                        $userBonusInfo = M("user_bonus")->where(array('userid'=>$id))->find();
                        $redisObj->_set($key,json_encode($userBonusInfo));
                    }
                    $last_bonus_level = $userBonusInfo["bonus_level"];
                    $user_bonus_data = array(
                        "bonus_content" => $reward_array[$rewardKey],
                        "bonus_level" => $point,
                        "last_bonus_level"=>$last_bonus_level,
                        "incleveltype" => "配额升点",
                        "incleveltime" => time()
                    );
                    $userBonusModel->where(array("userid"=>$id))->save($user_bonus_data);
                    if($redisObj->exists($key))
                    {
                        $redisObj->_delete($key);
                    }
                }
                else{
                    echo "请选择对应的升点类型";
                    exit();
                }
                $CommonSaveObject = new \Home\Controller\CommonSaveDataController();
                $CommonSaveObject::UserBonusById_Redis($id, 600, 'del');

                // 删除返奖系统中的redis
                $redisObj->_delete("getUserBonusConfig".$id);
                $redisObj->_delete("getUserBonusKlcConfig".$id);

                $currentTime = time();
                $msg = "您的返点已被调整,系统3秒后退出！";
                $send = $msg."-6-".$currentTime;
                $content = array('type'=>"inbox","to"=>$id,'content'=>$send);
                $result = self::sendWebSocketMsg($content);

                echo "用户升点成功";
            }
        }
        else
        {
            echo "用户升点失败";
            exit();
        }
    }

    // 开启日工资开关
    public function OpenDayrate()
    {
        $dayrate_switch = 1;
        $uid = I("post.uid");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $uid = $_xDe->decode($uid);

        if(empty($uid))
        {
            echo false;
            exit();
        }

        $parentid = session("SESSION_ID");
        $pid = M("user")->where(array("id"=>$uid))->getField("parent_id");

        if($parentid!=$pid)
        {
            echo false;
            exit();
        }

        M("user")->where(array("id"=>$uid))->save(array("dayrate_switch"=>$dayrate_switch));
        echo true;
    }

    // 关闭日工资开关
    public function CloseDayrate()
    {
        $dayrate_switch = 0;
        $uid = I("post.uid");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $uid = $_xDe->decode($uid);

        if(empty($uid))
        {
            echo false;
            exit();
        }

        $parentid = session("SESSION_ID");
        $pid = M("user")->where(array("id"=>$uid))->getField("parent_id");

        if($parentid!=$pid)
        {
            echo false;
            exit();
        }

        M("user")->where(array("id"=>$uid))->save(array("dayrate_switch"=>$dayrate_switch));
        echo true;
    }

    // 开启契约开关
    public function OpenShare()
    {
        $share_switch = 1;
        $uid = I("post.uid");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $uid = $_xDe->decode($uid);

        if(empty($uid))
        {
            echo false;
            exit();
        }

        $parentid = session("SESSION_ID");
        $pid = M("user")->where(array("id"=>$uid))->getField("parent_id");

        if($parentid!=$pid)
        {
            echo false;
            exit();
        }

        M("user")->where(array("id"=>$uid))->save(array("share_switch"=>$share_switch));
        echo true;
    }

    // 关闭日工资开关
    public function CloseShare()
    {
        $share_switch = 0;
        $uid = I("post.uid");
        import("Class.XDeode");
        $_xDe=new \XDeode();
        $uid = $_xDe->decode($uid);

        if(empty($uid))
        {
            echo false;
            exit();
        }

        $parentid = session("SESSION_ID");
        $pid = M("user")->where(array("id"=>$uid))->getField("parent_id");

        if($parentid!=$pid)
        {
            echo false;
            exit();
        }

        M("user")->where(array("id"=>$uid))->save(array("share_switch"=>$share_switch));
        echo true;
    }
}