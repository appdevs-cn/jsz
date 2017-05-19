<?php

namespace Home\Controller;
use Think\Controller;

use Home\Model\AgGameModel as AgGame;


class ChessController extends CommonController
{
    public function index()
    {
        $this->menu = "live";
        // 查询用户是否已经开通ag游戏
        $agUserInfo = M("ag_user")->where(array("ag_username"=>"jsz".session("SESSION_NAME")))->find();
        $this->IsCreateAg = (empty($agUserInfo)) ? 0 : 1;

        // 查询用户是否已经开通EBET游戏
        $ebetUserInfo = M("ebet_user")->where(array("ebet_username"=>"ebet_".session("SESSION_NAME")))->find();
        $this->IsCreateEbet = (empty($ebetUserInfo)) ? 0 : 1;
        
        $this->display();
    }

    // 获取Ebet的游戏连接
    public function EbetGameUrl()
    {
        // Ebet连接
        $ebet_res = M("ebet_user")->where(array("ebet_username"=>"ebet_".session("SESSION_NAME")))->getField("ebet_token");
        $ebet_token = json_decode($ebet_res, true);
        if(!empty($ebet_res))
        {
            $ebetUrl = "http://jsz.g6trad.club/h5/ukd69k?ts=".time()."&username=ebet_".session("SESSION_NAME")."&accessToken=".$ebet_token['accessToken'];
        }
        else
        {
            $ebetUrl = "http://jsz.g6trad.club/h5/ukd69k";
        }
        echo $ebetUrl;
    }

    // 获取AG的游戏连接地址
    public function AgGameUrl()
    {
        $gameType = I("get.type");
        if(empty($gameType)) exit();
        $AgGame = new AgGame();
        // 更改游戏点击数
        M("ag_game")->where(array("GI_gametype"=>$gameType))->setInc("click",1);
        $url = $AgGame->getGameUrl(session("SESSION_NAME"),$gameType);
        header("Location: $url");
    }
}



?>