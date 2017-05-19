<?php
/**
 * Created by PhpStorm.
 * User: yifanfengshun
 * Date: 2016/11/7
 * Time: 下午1:53
 */

namespace Home\Model;


use Think\Model;

class CommonModel extends Model
{
    protected $autoCheckFields = false;

    /**
     * 输入检测
     */
    public function checkInputValue()
    {
        if (isset($_POST) && !empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $val = remove_xss($value);
                $_POST[$key] = $val;
            }
        }
        if (isset($_GET) && !empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $val = remove_xss($value);
                $_GET[$key] = $val;
            }
        }
        if (isset($_GET) && !empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $_GET[$key] = dowith_sql($value);
            }
        }
        if (isset($_POST) && !empty($_POST)) {
            foreach ($_POST as $key => $value) {
                $_POST[$key] = dowith_sql($value);
            }
        }
    }
    
    /**
     * 发送数据到websocket $content = array("type"=>"","to"=>"","content"=>"")
     *
     * @param $content
     */
    public function sendWebSocketMsg($content)
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

    /**
     * 检查用户是否在线
     */
    public function isUserOnline($uid)
    {
        if($this->sendWebSocketMsg(array("type"=>"isUserOnline","to"=>$uid,"content"=>""))=="ok")
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}