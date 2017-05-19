<?php

namespace Home\Controller;

use Think\Controller;
use Home\Model\AgGameModel as AgGame;
class FrameController extends Controller
{
    // 嵌套AG游戏页面
    public function AG()
    {
        $gameType = I("get.gameType");
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        //setcookie("aggame11", session("SESSION_NAME")."-".$gameType, time()+3600, "/", "www.jszapi.com",true);
        $FRAMEURL = C("FRAMEURL")."/Frame/jumpAg";
        header("Set-Cookie: cookiename=cookieValue; expires=" . gmstrftime("%A, %d-%b-%Y %H:%M:%S GMT", time() + (86400 * 365)) .  '; path=/; domain=jszapi.com');
        header("Location: $FRAMEURL");
    }

    public function jumpAg()
    {
        $this->display("AG");
    }

    // 获取AG的游戏连接地址
    public function AgGameUrl()
    {
        echo $_COOKIE["cookiename"];die;
        $gameType = I("get.type");
        if(empty($gameType)) exit();
        $AgGame = new AgGame();
        // 更改游戏点击数
        M("ag_game")->where(array("GI_gametype"=>$gameType))->setInc("click",1);
        $url = $AgGame->getGameUrl(session("SESSION_NAME"),$gameType);
        header("Location: $url");
    }

    // 嵌套MG游戏页面
    public function MG()
    {
        $url = I("post.url");
        echo httpRequest($url);
    }

    // 嵌套PT游戏页面
    public function PT()
    {
        $url = I("post.url");
        echo httpRequest($url);
    }

    // 嵌套EBET游戏页面
    public function EBET()
    {
        $url = I("post.url");
        echo httpRequest($url);
    }
}




?>