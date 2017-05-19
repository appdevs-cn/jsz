<?php
namespace Home\Controller;

use Home\Controller\CommonController;

class SmsController extends CommonController
{
    public function index()
    {
        $this->managermenu = "sms";
        $this->menu = "message";
        $uid = session("SESSION_ID");
        $sms = M("sms");
        $result = $sms->where(array("userid"=>$uid))->find();
        if(empty($result))
        {
            $result['withdrawnotice'] = 0;
            $result['updateloginpwdnotice'] = 0;
            $result['updatefundpwdnotice'] = 0;
            $result['updaterealnamenotice'] = 0;
            $result['bindbanknotice'] = 0;
            $result['updatetelnotice'] = 0;
            $result['loginnotice'] = 0;
        }
        $this->xReturn = $result;
        $this->display();
    }

    // 设置短息订阅
    public function SetSms()
    {
        $uid = session("SESSION_ID");
        $data = array();
        $withdrawnotice = I("post.withdrawnotice");
        if($withdrawnotice!="")
        {
            $data['withdrawnotice'] = $withdrawnotice;
        }

        $updateloginpwdnotice = I("post.updateloginpwdnotice");
        if($updateloginpwdnotice!="")
        {
            $data['updateloginpwdnotice'] = $updateloginpwdnotice;
        }

        $updatefundpwdnotice = I("post.updatefundpwdnotice");
        if($updatefundpwdnotice!="")
        {
            $data['updatefundpwdnotice'] = $updatefundpwdnotice;
        }

        $updaterealnamenotice = I("post.updaterealnamenotice");
        if($updaterealnamenotice!="")
        {
            $data['updaterealnamenotice'] = $updaterealnamenotice;
        }

        $bindbanknotice = I("post.bindbanknotice");
        if($bindbanknotice!="")
        {
            $data['bindbanknotice'] = $bindbanknotice;
        }

        $updatetelnotice = I("post.updatetelnotice");
        if($updatetelnotice!="")
        {
            $data['updatetelnotice'] = $updatetelnotice;
        }

        $loginnotice = I("post.loginnotice");
        if($loginnotice!="")
        {
            $data['loginnotice'] = $loginnotice;
        }

        $sms = M("sms");

        $result = $sms->where(array("userid"=>$uid))->find();
        if(!empty($result))
        {
            $sms->where(array("userid"=>$uid))->save($data);
        }
        else
        {
            $data['userid'] = $uid;
            $sms->add($data);
        }
        echo true;
    }
}




?>