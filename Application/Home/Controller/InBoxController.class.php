<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/14
 * Time: 下午11:59
 */

namespace Home\Controller;

use Home\Model\SystemMessageModel as SystemMessage;
class InBoxController extends CommonController
{
    public function index()
    {
        $this->managermenu = "inbox";
        $this->menu = "message";
        $this->display();
    }

    // 收件箱
    public function getOutboxMessage()
    {
        $uid = session("SESSION_ID");
        //模型
        $mail = M("mail");
        $user = M("user");

        $field = array(
            "mail_to_user.status",
            "mail_to_user.tid",
            "mail_to_user.uid",
            "mail_to_user.mid",
            "mail.title",
            "mail.content",
            "mail.sendtime",
            "mail.id"
        );

        $_count = $mail->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where("mail_to_user.tid=".$uid)->count();

        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        //$listRows = C("LISTROWS");
        $listRows = 20;
        $roolPage = C("ROOLPAGE");
        $url = "";
        //获取数据总数
        $totalRows = $_count;
        $p=new \Page($totalRows, $listRows, "",$url);

        //分页栏每页显示的页数
        $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
        $pages = C('PAGE');
        //可以使用该方法前用C临时改变配置
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $_res = $mail->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where("mail_to_user.tid=".$uid)->order("mail.id DESC")->limit($p->firstRow,$p->listRows)->select();

        $temp = array();
        $userExtend = M("user_extend");
        if(!empty($_res))
        {
            foreach($_res as $val){
                $val["id"] = $val["id"];
                $val["title"] = $val["title"];
                $val["content"] = '<a style="color:#000" href="javascript:;" data-toggle="tooltip" title="'.strip_tags($val['content']).'" data-placement="right">'.mb_substr(strip_tags($val['content']),0,60,'utf-8').'</a>';
                $val["status"] = ($val['status']==0) ? '<div class="label bg-orange">未读</div>' : '<div class="label bg-orange"><font style="color:red">已读</font></div>';
                $val['sendtime'] = date("m-d H:i:s",$val['sendtime']);
                $val["sjusername"] = $user->where(array("id"=>$val["tid"]))->getField("username");
                $val["fjusername"] = $user->where(array("id"=>$val["uid"]))->getField("username");
                $val["fjuid"] = $val["uid"];
                $val['head'] = $userExtend->where(array("userid"=>$val["uid"]))->getField("img100Path");
                $val['havaData'] = true;
                $val['page'] = $page;
                $temp[] = $val;
            }
        }
        else
        {
            $val['havaData'] = false;
            $val['page'] = $page;
            $temp[] = $val;
        }
        echo json_encode($temp);
    }

    //  标记发件箱邮件已读
    public function uodateOutboxMessage()
    {
        $id = I("post.id");
        $mail = M("mail");
        $field = array(
            "mail_to_user.status",
            "mail_to_user.tid",
            "mail_to_user.uid",
            "mail_to_user.id as mailid",
            "mail.title",
            "mail.content",
            "mail.sendtime",
            "mail.id"
        );
        $result = $mail->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where(array("mail.id=".$id))->find();
        //更新邮件状态为已读
        M("mail_to_user")->where(array("id"=>$result["mailid"]))->save(array("status"=>1));
        echo true;
    }

    //  删除发件箱邮件
    public function deleteOutboxMessage()
    {
        $id = I("post.id");

        $mail = M("mail");
        $mail_to_user = M("mail_to_user");

        $mail->where(array("id"=>$id))->delete();
        $mail_to_user->where(array("mid"=>$id))->delete();
         echo true;
    }

    // 收件箱
    public function getInboxMessage()
    {
        //参数
        $uid = session("SESSION_ID");
        //模型
        $mail = M("mail");
        $user = M("user");

        $field = array(
            "mail_to_user.status",
            "mail_to_user.tid",
            "mail_to_user.uid",
            "mail_to_user.mid",
            "mail.title",
            "mail.content",
            "mail.sendtime",
            "mail.id"
        );

        $_count = $mail->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where("mail_to_user.uid=".$uid)->count();

        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        //$listRows = C("LISTROWS");
        $listRows = 20;
        $roolPage = C("ROOLPAGE");
        $url = "";
        //获取数据总数
        $totalRows = $_count;
        $p=new \Page($totalRows, $listRows, "",$url);

        //分页栏每页显示的页数
        $p->rollPage = (ceil($totalRows/$listRows)<=$roolPage) ? ceil($totalRows/$listRows) : $roolPage;
        $pages = C('PAGE');
        //可以使用该方法前用C临时改变配置
        foreach ($pages as $key => $value) {
            $p->setConfig($key, $value);
        }
        //分页显示
        $page = $p->show();
        $_res = $mail->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where("mail_to_user.uid=".$uid)->order("mail.id DESC")->limit($p->firstRow,$p->listRows)->select();

        $temp = array();
        if(!empty($_res))
        {
            foreach($_res as $val){
                $val["title"] = $val["title"];
                $val["content"] = '<a style="color:#000" href="javascript:;" data-toggle="tooltip" title="'.strip_tags($val['content']).'" data-placement="right">'.mb_substr(strip_tags($val['content']),0,60,'utf-8').'</a>';
                $val["status"] = ($val['status']==0) ? '<div class="label bg-orange">未读</div>' : '<div class="label bg-orange"><font style="color:red">已读</font></div>';
                $val['sendtime'] = date("m-d H:i:s",$val['sendtime']);
                $val["fjusername"] = $user->where(array("id"=>$val["uid"]))->getField("username");
                $val["sjusername"] = $user->where(array("id"=>$val["tid"]))->getField("username");
                $val["fjuid"] = $val["uid"];
                $val['havaData'] = true;
                $val['page'] = $page;
                $temp[] = $val;
            }
        }
        echo json_encode($temp);
    }

    //  标记收件箱邮件已读
    public function uodateInboxMessage()
    {
        $id = I("post.id");
        $mail = M("mail");
        $field = array(
            "mail_to_user.status",
            "mail_to_user.tid",
            "mail_to_user.uid",
            "mail_to_user.id as mailid",
            "mail.title",
            "mail.content",
            "mail.sendtime",
            "mail.id"
        );
        $result = $mail->join("mail_to_user on mail_to_user.mid=mail.id")->field($field)->where(array("mail.id=".$id))->find();
        //更新邮件状态为已读
        M("mail_to_user")->where(array("id"=>$result["mailid"]))->save(array("status"=>1));
        echo true;
    }

    //  删除收件箱邮件
    public function deleteInboxMessage()
    {
        $id = I("post.id");
        $mail = M("mail");
        $mail_to_user = M("mail_to_user");

        $mail->where(array("id"=>$id))->delete();
        $mail_to_user->where(array("mid"=>$id))->delete();
         echo true;
    }

    // 查询发送人的用户列表
    public function selectSendmailUser()
    {
        $uid = session("SESSION_ID");
        $userModel = M("user");
        $parent_id = session("SESSION_PARENTID");
        $userlist = $userModel->field("username,id")->where(array("parent_id"=>$uid))->select();
        $result['parent_id'] = $parent_id;
        $result['userlist'] = $userlist;
        echo json_encode($result);
    }

    // 发送邮件
    public function sendMail()
    {
        $uid = session("SESSION_ID");
        $tid = I("post.tid","");
        $title = I("post.title","");
        $content = I("post.content","");
        $roleid = session("SESSION_ROLE");
        if($title=="" || $content==""){
            echo "提交信息不完整";
            exit();
        }
        $data = array($uid,$tid,$roleid,$title,$content,1);
        sendMail($data);
        $content = array('type'=>"messagemail","to"=>$tid,'content'=>"您有一封信的邮件!");
        $result = self::sendWebSocketMsg($content);
        echo "邮件信息已发送";
    }

    // 邮件发送信息推送
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

    public function getPrizeMessage()
    {
        $type = I("post.type",1);
        $title = array("","中奖","充值","提款");
        $userid = session("SESSION_ID");
        $SystemMessage = new SystemMessage();
        $prizecount = $SystemMessage->where(array("userid"=>$userid,"type"=>1,"status"=>0))->count();
        $rechargecount = $SystemMessage->where(array("userid"=>$userid,"type"=>2,"status"=>0))->count();
        $drawcount = $SystemMessage->where(array("userid"=>$userid,"type"=>3,"status"=>0))->count();
        $totalCount = $prizecount+$rechargecount+$drawcount;
        $result = array();

        $_count = $SystemMessage->where(array("userid"=>$userid,"type"=>$type,"status"=>array("neq",2)))->order("date desc")->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = C("LISTROWS");;
        $roolPage = C("ROOLPAGE");
        $url = "";
        //获取数据总数
        $totalRows = $_count;
        $p=new \Page($totalRows, $listRows, "",$url);

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
        $list = $SystemMessage->where(array("userid"=>$userid,"type"=>$type,"status"=>array("neq",2)))->limit($p->firstRow,$p->listRows)->order("date desc")->select();

        if(!empty($list))
        {
            foreach($list as $item)
            {
                $temp['id'] = $item['id'];
                $temp['content'] = '<a style="color:#000" href="javascript:;" data-toggle="tooltip" title="'.strip_tags($item['content']).'" data-placement="right">'.strip_tags($item['content']).'</a>';
                $temp['isread'] = ($item['status']==1) ? true : false;
                $temp['status'] = ($item['status']==0) ? '<span class="green">未读</span>' : '<span class="red">已读</span>';
                $temp['type'] = $item['type'];
                $temp['title'] = $title[$item['type']];
                $temp['date'] = date("m/d H:i:s",$item['date']);
                $temp['count'] = array("prizecount"=>$prizecount,"rechargecount"=>$rechargecount,"drawcount"=>$drawcount,"cancelordercount"=>$cancelordercount,"totalCount"=>$totalCount);
                $temp['havaData'] = true;
                $temp['page'] = $page;
                $result[] = $temp;
            }
        }
        else
        {
            $temp['title'] = $title[$type];
            $temp['havaData'] = false;
            $temp['page'] = $page;
            $temp['type'] = $type;
            $temp['count'] = array("prizecount"=>$prizecount,"rechargecount"=>$rechargecount,"drawcount"=>$drawcount,"cancelordercount"=>$cancelordercount,"totalCount"=>$totalCount);
            $result[] = $temp;
        }
        echo json_encode($result);
    }

    public function uodateMessage()
    {
        $id = I("post.id");
        $SystemMessage = new SystemMessage();
        $SystemMessage->data = array("id"=>$id);
        echo $SystemMessage->updateMessage();
    }

    public function deleteMessage()
    {
        $id = I("post.id");
        $SystemMessage = new SystemMessage();
        $SystemMessage->data = array("id"=>$id);
        echo $SystemMessage->delMessage();
    }
}