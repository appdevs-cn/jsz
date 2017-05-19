<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/15
 * Time: 上午12:51
 */

namespace Home\Controller;
use \Home\Model\FoundChangeMongoModel as FoundChange;

class CouponController extends CommonController
{

    public function index()
    {
        $this->menu = "coupon";
        $this->display();
    }

    // 活动详情
    public function Detail()
    {
        //判断现在时间是不是00：00：00 到 02：59：59
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordtime = mktime(3, 0, 0, date('m'), date('d') - 1, date('Y'));
        } else {
            $accordtime = mktime(3, 0, 0, date('m'), date('d'), date('Y'));
        }
        $accordingTimeModel = M("according_time");
        $this->touzhuAmount = $accordingTimeModel->where("userid=".session("SESSION_ID")." and accordTime=".$accordtime)->sum("touzhuAmount");
        $this->menu = "coupon";
        $this->display("activeDetail1");
    }

    // 消费送活动
    public function ConsumeDelivery()
    {
        $uid = session("SESSION_ID");
        $username = session("SESSION_NAME");
        $stime = mktime(0,0,0,date("m"),date("d"),date("Y"));
        $etime = mktime(0,0,0,date("m"),date("d")+1,date("Y"));

        //查看今天是否已经领取
        \SeasLog::setLogger("ConsumeDelivery");
        $info = \SeasLog::analyzerDetail(SEASLOG_INFO,'*',$username);
        $isHaveGet = false;
        foreach($info as $item)
        {
            $str = explode("|", $item);
            if($str[4]>=$stime)
            {
                $isHaveGet = true;
            }
        }
        if($isHaveGet)
        {
            echo "今天已领取过消费送活动";
            exit();
        }

        $standard = array(
            "888800000" => 1800000
            ,"1888800000" => 3800000
            ,"2888800000" => 5800000
            ,"5888800000" => 8800000
            ,"8888800000" => 10800000
        );
        // 查询今天的投注量
        $_map["userid"] = session("SESSION_ID");
        $_map["_string"] = "accordTime>=".$stime." and accordTime<=".$etime;
        $totaltouzhuAmount = M("according_time")->where($_map)->sum('touzhuAmount');

        $getBonus = 0;
        foreach($standard as $stand=>$bonus)
        {
            if(bccomp($totaltouzhuAmount,$stand,4)!=-1)
            {
                $getBonus = $bonus;
            }
        }

        if($getBonus==0)
        {
            echo "今天的消费还未达到，不能领取奖励";
            exit();
        }

        //对用户的积分进行增加
        $attModel = M("according_time");
        $userModel = M("user");
        $accounts_change = M("accounts_change");

        $userModel->startTrans();
        $userInfo = $userModel->where(array("id"=>$uid))->find();
        $now_money = $getBonus+$userInfo["cur_account"];

        $account_data = array(
            "accounts_type" => 24,
            "buy_record_id" => 0,
            "change_amount" => $getBonus,
            "userid" => $uid,
            "username" => $username ,
            "parent_id" => session("SESSION_PARENTID") ,
            "parent_path" => session("SESSION_PATH") ,
            "cur_account" => $now_money,
            "serial_number" => 0,
            "runner_id" => $uid,
            "runner_name" => $username ,
            "change_time" => time() ,
            "is_addto" => 0,
            "remark" => "消费送",
            "buy_type_id" => 0,
            "lottery_id" => 0,
            "lottery_number_id" => 0,
            "yuan" => 0
        );
        $account_id = $accounts_change->add($account_data);
        import("Class.XDeode");
        $_xDe = new \XDeode();
        //更新该条账变的账变编号
        $achange_num = strtoupper($_xDe->encode($account_id));
        $accounts_change->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
        //加入盈利报表统计
        _team_report(array($uid,0,0,0,0,0,$getBonus,0));
        //更新用户余额
        $userModel->where(array("id"=>$uid))->save(array("cur_account"=>$now_money));
        $userModel->commit();
        //将活动数据保存到SeasLog中
        \SeasLog::info(time()."|".$username);
        echo "消费活动领取成功";
    }


    public function getXiaofeiHuodong() {
        //防止重复提交活动
        import("Class.RedisObject");
        $redisObj = new \RedisObject();
        $submitactiveMD5=md5("xiaofei".session("SESSION_ID").time());
        if($redisObj->_get($submitactiveMD5)){
            echo "你已提交过该次闯关";
            exit();
        }else{
            $redisObj->_set($submitactiveMD5,1);
            $redisObj->_expire($submitactiveMD5,5);
        }


        //判断现在时间是不是00：00：00 到 02：59：59
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordtime = mktime(3, 0, 0, date('m'), date('d') - 1, date('Y'));
        } else {
            $accordtime = mktime(3, 0, 0, date('m'), date('d'), date('Y'));
        }

        import("Class.XDeode");
        $_xDe=new \XDeode();

        $uid = session("SESSION_ID");
        $type=I("post.type","");
        if(!in_array($type,array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18))){
            echo "所闯关卡不存在";
            exit();
        }

        //模型
        $accountsChangeModel = M("accounts_change");
        $activeXiaofeisongModel = M("active_xiaofeisong");
        $accordingTimeModel = M("according_time");

        //判断该用户是否用充值
       $recharge = $accordingTimeModel->where("userid=".$uid." and accordTime=".$accordtime)->sum("rechargeAmount");
       if($recharge==0){
           echo "对不起，今天还未进行充值，不能参与该活动";exit();
       }
        $touzhuAmount = $accordingTimeModel->where("userid=".$uid." and accordTime=".$accordtime)->sum("touzhuAmount");
        $active = $activeXiaofeisongModel->where("userid=".$uid." and lingqu_time=".$accordtime)->find();
        if(bccomp($touzhuAmount,88800000,4)==-1){
            echo "消费金额不足，闯关失败";
            exit();
        }
        if($type==1) {
            if (bccomp($touzhuAmount, 88800000, 4) != -1) {
                if ($active["step1"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if (empty($active)) {
                    $bonus = 3;
                    $activeXiaofeisongModel->add(array("userid" => $uid, "username" => session("SESSION_NAME"), "bonus1" => $bonus, "lingqu_time" => $accordtime, "step1" => 1));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡一奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));

                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡一奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==2) {
            if (bccomp($touzhuAmount, 188800000, 4) != -1) {

                if ($active["step2"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step1"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 5;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step2" => 1, "bonus2" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡二奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡二奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败" ;
                exit();
            }
        }

        if($type==3) {
            if (bccomp($touzhuAmount, 388800000, 4) != -1) {
                if ($active["step3"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step2"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 8;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step3" => 1, "bonus3" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡三奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡三奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==4) {
            if (bccomp($touzhuAmount, 588800000, 4) != -1) {
                if ($active["step4"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step3"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 9;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step4" => 1, "bonus4" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡四奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡四奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }



        if($type==5) {
            if (bccomp($touzhuAmount, 888800000, 4) != -1) {
                if ($active["step5"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step4"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 10;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step5" => 1, "bonus5" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡五奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡五奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==6) {
            if (bccomp($touzhuAmount, 1588800000, 4) != -1) {
                if ($active["step6"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step5"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 12;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step6" => 1, "bonus6" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡六奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡六奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }


        if($type==7) {
            if (bccomp($touzhuAmount, 1888800000, 4) != -1) {
                if ($active["step7"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step6"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 15;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step7" => 1, "bonus7" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡七奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡七奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==8) {
            if (bccomp($touzhuAmount, 2888800000, 4) != -1) {
                if ($active["step8"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step7"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 20;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step8" => 1, "bonus8" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡八奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡八奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==9) {
            if (bccomp($touzhuAmount, 3888800000, 4) != -1) {
                if ($active["step9"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step8"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 35;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step9" => 1, "bonus9" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡九奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡九奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==10) {
            if (bccomp($touzhuAmount, 5888800000, 4) != -1) {
                if ($active["step10"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step9"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 50;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step10" => 1, "bonus10" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==11) {
            if (bccomp($touzhuAmount, 8088800000, 4) != -1) {
                if ($active["step11"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step10"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 80;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step11" => 1, "bonus11" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十一奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十一奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }

        if($type==12) {
            if (bccomp($touzhuAmount, 10888800000, 4) != -1) {
                if ($active["step12"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step11"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 120;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step12" => 1, "bonus12" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十二奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十二奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==13) {
            if (bccomp($touzhuAmount, 15888800000, 4) != -1) {
                if ($active["step13"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step12"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 180;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step13" => 1, "bonus13" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十三奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十三奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }

        if($type==14) {
            if (bccomp($touzhuAmount, 25888800000, 4) != -1) {
                if ($active["step14"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step13"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 300;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step14" => 1, "bonus14" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十四奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十四奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }

        if($type==15) {
            if (bccomp($touzhuAmount, 35888800000, 4) != -1) {
                if ($active["step15"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step14"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 500;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step15" => 1, "bonus15" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十五奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十五奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }

        if($type==16) {
            if (bccomp($touzhuAmount, 50888800000, 4) != -1) {
                if ($active["step16"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step15"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 750;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step16" => 1, "bonus16" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十六奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十六奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
        if($type==17) {
            if (bccomp($touzhuAmount, 70888800000, 4) != -1) {
                if ($active["step17"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step16"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 1000;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step17" => 1, "bonus17" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十七奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十七奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }

        if($type==18) {
            if (bccomp($touzhuAmount, 108888800000, 4) != -1) {
                if ($active["step18"] == 1) {
                    echo "该关卡已过关";
                    exit();
                }
                if ($active["step17"] != 1) {
                    echo "您之前还有未闯过的关卡";
                    exit();
                } else {
                    $bonus = 1500;
                    $activeXiaofeisongModel->where("userid=" . $uid . " and lingqu_time=" . $accordtime)->save(array("lingqu_time" => $accordtime, "step18" => 1, "bonus18" => $bonus));
                }
                M("user")->startTrans();
                $user_result = M("user")->db(0)->lock(true)->field("username,id,cur_account,parent_id,parent_path")->where("id=" . $uid)->find();
                $cur_account = $user_result["cur_account"];
                $self_money = $bonus * 100000;
                //更新活动用户本身
                $now_account = $cur_account + $self_money;
                M("user")->where(array("id" => $uid, "status" => 1))->save(array("cur_account" => $now_account));
                M("user")->commit();
                //添加帐边
                $account_data = array(
                    "accounts_type" => 24,
                    "buy_record_id" => 0,
                    "change_amount" => $self_money,
                    "userid" => $uid,
                    "username" => $user_result["username"],
                    "parent_id" => $user_result["parent_id"],
                    "parent_path" => $user_result["parent_path"],
                    "cur_account" => $now_account,
                    "serial_number" => 0,
                    "runner_id" => $uid,
                    "runner_name" => $user_result["username"],
                    "change_time" => time(),
                    "is_addto" => 0,
                    "remark" => "闯关卡十八奖励" . sprintf("%.2f", $self_money / 100000),
                    "buy_type_id" => 0,
                    "lottery_id" => 0,
                    "lottery_number_id" => 0,
                    "yuan" => 0
                );
                $account_id = $accountsChangeModel->add($account_data);
                //更新该条账变的账变编号
                $achange_num = strtoupper($_xDe->encode($account_id));
                $accountsChangeModel->where(array("id" => $account_id))->save(array("achange_num" => $achange_num));
                //加入盈利报表统计
                _team_report(array($uid, 0, 0, 0, 0, 0, $self_money, 0));
                // 增加资金变化
                $FoundChange = new FoundChange();
                $FoundChangeData = array();
                $FoundChangeData['userid'] = $uid;
                $FoundChangeData['username'] = session("SESSION_NAME");
                $FoundChangeData['parent_id'] = session("SESSION_PARENTID");
                $FoundChangeData['parent_path'] = session("SESSION_PATH");
                $FoundChangeData['beforeMoney'] = intval($cur_account);
                $FoundChangeData['money'] = intval($self_money);
                $FoundChangeData['afterMoney'] = intval($now_account);
                $FoundChangeData['time'] = time();
                $FoundChangeData['remark'] = "闯关卡十八奖励".sprintf("%.2f", $self_money / 100000);
                $FoundChange->add($FoundChangeData);
                
                echo "领取关卡奖励{$bonus}元";
                exit();
            } else {
                echo "消费金额不足，闯关失败";
                exit();
            }
        }
    }

    public function getXiaofeiHuodongStatus() {
        //判断现在时间是不是00：00：00 到 02：59：59
        $nowTime = time();
        $today0Time = strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") , date("d") , date("Y"))));
        $today3Time = strtotime(date("Y-m-d H:i:s", mktime(03, 0, 0, date("m") , date("d") , date("Y"))));
        if ($nowTime >= $today0Time && $nowTime < $today3Time) {
            $accordtime = mktime(3, 0, 0, date('m'), date('d') - 1, date('Y'));
        } else {
            $accordtime = mktime(3, 0, 0, date('m'), date('d'), date('Y'));
        }
        $active_xiaofeisong = M("active_xiaofeisong");
        $uid = session("SESSION_ID");
        $res = $active_xiaofeisong->field("step1,step2,step3,step4,step5,step6,step7,step8,step9,step10,step11,step12,step13,step14,step15,step16,step17,step18")->where("lingqu_time=".$accordtime." and userid=".$uid)->select();
        if(empty($res)){
            echo "error";
            exit();
        }
        $result = "";
        foreach($res as $val) {
            if($val["step1"]==1){
                $result .= "1-";
            }
            if($val["step2"]==1){
                $result .= "2-";
            }
            if($val["step3"]==1){
                $result .= "3-";
            }
            if($val["step4"]==1){
                $result .= "4-";
            }
            if($val["step5"]==1){
                $result .= "5-";
            }
            if($val["step6"]==1){
                $result .= "6-";
            }
            if($val["step7"]==1){
                $result .= "7-";
            }
            if($val["step8"]==1){
                $result .= "8-";
            }
            if($val["step9"]==1){
                $result .= "9-";
            }
            if($val["step10"]==1){
                $result .= "10-";
            }
            if($val["step11"]==1){
                $result .= "11-";
            }
            if($val["step12"]==1){
                $result .= "12-";
            }
            if($val["step13"]==1){
                $result .= "13-";
            }
            if($val["step14"]==1){
                $result .= "14-";
            }
            if($val["step15"]==1){
                $result .= "15-";
            }
            if($val["step16"]==1){
                $result .= "16-";
            }
            if($val["step17"]==1){
                $result .= "17-";
            }
            if($val["step18"]==1){
                $result .= "18-";
            }
        }
        echo substr($result,0,strlen($result)-1);
    }
}