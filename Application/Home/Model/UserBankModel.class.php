<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午7:35
 */

namespace Home\Model;


use Think\Model;
use Home\Model\UserModel as User;

class UserBankModel extends Model
{
    protected $trueTableName = 'user_bank';

    public $CARD_NUMBER = 3;

    /**
     * 查询用户银行卡信息
     *
     * @return mixed
     */
    public function checkUserBank()
    {
        $uid = session("SESSION_ID");
        $result = $this->field("userid,parent_path",true)->where(array("userid"=>$uid,"status"=>2))->select();
        return $result;
    }

    /**
     * 查询默认的提款银行卡
     *
     * @return mixed
     */
     public function getUserMrBank()
     {
         $uid = session("SESSION_ID");
         $result = $this->where(array("userid"=>$uid,"status"=>2))->select();
         return $result;
     }

    /**
     * 绑定银行卡
     *
     * @return string
     */
    public function bindBankCard()
    {
        $count = $this->checkBankCardNum();
        if($count>$this->CARD_NUMBER)
        {
            return "error1";  //最多只能绑定3张银行卡
        }
        $data = $this->data;
        $obj['bank_id'] = $data['data']['bank_id'];
        $obj['account_num'] = $data['data']['account_num'];
        $obj['bankprov'] = $data['data']['bankprov'];
        $obj['bankcity'] = $data['data']['bankcity'];
        $bindSecurityPassword = $data['data']['bindSecurityPassword'];
        $obj['bank_addr'] = "";
        $obj['status'] = 2;
        $obj['ptime'] = time();
        $obj['unlockStatus'] = 0;
        if($this->checkBankCardNum()==0)
            $obj['moren'] = 1;
        else
            $obj['moren'] = 0;
        if($this->isCardBinded($obj['bank_id'], $obj['account_num']))
        {
            return "error6";
        }
        $User = new User();
        $obj['userid'] = session("SESSION_ID");
        $UserInfo = $User->field("parent_path,acc_password,safe_key,realname")->where(array("id"=>$obj['userid']))->find();

        // 查询该银行卡是否有其他人绑定过
        $ishavecardres = $this->where(array("realname"=>$UserInfo['realname'],"account_num"=>$obj['account_num'],"userid"=>array("neq",session("SESSION_ID"))))->find();
        if(!empty($ishavecardres))
        {
            return "error6"; //该卡号已被绑定
        }

        $bindSecurityPasswordMd5 = md5(md5($bindSecurityPassword . C("PASSWORD_HALT")) . $UserInfo['safe_key']);
        if($bindSecurityPasswordMd5!=$UserInfo['acc_password'])
        {
            return "error2"; //资金密码不正确,绑定失败
        }
        else
        {
            $obj['parent_path'] = $UserInfo['parent_path'];
            $obj['realname'] = $UserInfo['realname'];
            if($this->data($obj)->add())
            {
                return "success";
            }
            else
            {
                return "error3"; //银行卡绑定失败
            }
        }
    }

    /**
     * 删除用户银行卡
     *
     * @return bool
     */
    public function delBankCard()
    {
        $data = $this->data;
        $SecurityPassword = $data['data']['SecurityPassword'];
        $bankid = $data['data']['bankid'];
        $uid = session("SESSION_ID");
        $User = new User();
        $UserInfo = $User->field("acc_password,safe_key,realname")->where(array("id"=>$uid))->find();
        $SecurityPasswordMd5 = md5(md5($SecurityPassword . C("PASSWORD_HALT")) . $UserInfo['safe_key']);
        if($SecurityPasswordMd5!=$UserInfo['acc_password'])
        {
            return false;
        }
        else
        {
            $realname = $this->where(array("id"=>$bankid))->getField("realname");
            if($realname!=$UserInfo['realname'])
            {
                return false;
            }
            else
            {
                $this->where(array("id"=>$bankid))->delete();
                return true;
            }
        }
    }

    /**
     * 查询用户已经绑定的银行卡
     *
     * @return mixed
     */
    public function checkBankCardNum()
    {
        $uid = session("SESSION_ID");
        return $this->where(array("userid"=>$uid,"status"=>1))->count();
    }

    /**
     * 检查银行卡是否已经被绑定
     *
     * @param $bank_id
     * @param $account_num
     * @return bool
     */
    public function isCardBinded($bank_id, $account_num)
    {
        $r1 = $this->where(array("account_num"=>$account_num))->count();
        if($r1>0)
        {
            return true;
        }
        else
        {
            $uid = session("SESSION_ID");
            $r2 = $this->where(array("account_num"=>$account_num,"userid"=>$uid,"bank_id"=>$bank_id))->count();
            if(r2>0)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * 验证银行卡号是否与真实姓名符合
     *
     * @param $account_num
     * @return string
     */
    public function checkUserAndBank($account_num)
    {
        $uid = session("SESSION_ID");
        $User = new User();
        $realname = $User->where(array("id"=>$uid))->getField("realname");
        $Appkey = C("VerifyingBankCardKey");
        $url = "http://api.avatardata.cn/BankCardCertificate/Verify?key={$Appkey}&realname={$realname}&cardnum={$account_num}";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($result,true);
        return $res['result']['code'];
    }

    /**
     * 银行卡信息
     *
     * @return mixed
     */
    public function bindBankInfo()
    {
        $data = $this->data;
        $bankid = $data['data']['bankid'];
        return $this->where(array("userid"=>session("SESSION_ID"),"id"=>$bankid))->find();
    }


}