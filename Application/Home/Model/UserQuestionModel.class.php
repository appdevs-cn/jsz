<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 上午10:43
 */

namespace Home\Model;


use Think\Model;
use Home\Model\QuestionModel as question;
use Home\Model\UserModel as User;

class UserQuestionModel extends Model
{
    protected $trueTableName = 'user_question';

    protected $_validate = array(
        array("userid","require","用户ID不能为空",self::MUST_VALIDATE)
    );

    public $question;
    public $User;

    /**
     * 获取用户的密保问题
     *
     * @return string
     */
    public function getQuestion()
    {
        $question = new question();
        $data = $this->data;
        $res = $this->where(array("userid"=>$data['data']['userid']))->field("question_id")->find();
        if(empty($res)) return "";
        return $question->where(array("id"=>$res['question_id']))->getField('name');
    }

    /**
     * 查看用户是否设置密保问题
     *
     * @return bool
     */
    public function checkUserQuestion()
    {
        $uid = session("SESSION_ID");
        $res = $this->where(array("userid"=>$uid))->field("id")->find();
        return (empty($res) ? false : true);
    }

    /**
     * 设置用户的密保问题
     *
     * @return bool
     */
    public function setQuestionModel()
    {
        $this->User = new User();
        $data = $this->data;
        $question = $data["data"]["question"];
        $answer = $data["data"]["answer"];
        $SecurityPassword = $data["data"]["SecurityPassword"];
        $uid = session("SESSION_ID");
        $info = $this->User->field("acc_password,safe_key")->where(array("id"=>$uid))->find();
        $acc_password = $info["acc_password"];
        $SecurityPasswordMd5 = md5(md5($SecurityPassword . C("PASSWORD_HALT")) . $info["safe_key"]);
        if($acc_password==$SecurityPasswordMd5)
        {
            // 查询用户是否已经设置过密保问题
            $isHave = $this->where(array('userid'=>$uid))->find();
            if(!empty($isHave))
            {
                return false;
            }
            if($this->add(array("userid"=>$uid,"question_id"=>$question,"answer"=>$answer,"status"=>1)))
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}