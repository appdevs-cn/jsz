<?php

namespace Home\Controller;
use Think\Controller;
use Home\Model\MgGameModel as MgGame;
use Home\Model\PtGameModel as PtGame;
use Home\Model\EbetGameModel as EbetGame;
use Home\Model\AgGameModel as AgGame;
class ComputerGameController extends CommonController
{
    public function index()
    {
        $this->menu="bandit";
        $this->type = I("get.type","other");
        $this->display();
    }

    // 获取MG游戏列表
    public function SearchMgGame()
    {
        $ChineseGameName = I("post.ChineseGameName");
        if(!empty($ChineseGameName))
        {
            $map['ChinesGameName'] = array("like","%".$ChineseGameName."%");
            // 更改游戏点击数
            M("mg_game")->where(array("ChinesGameName"=>$ChineseGameName))->setInc("clickRate",1);
        }

        $map['type'] = 1;

        $mgUserInfo = M("mg_user")->where(array("mg_username"=>"jsz_".session("SESSION_NAME")))->find();
        if(empty($mgUserInfo))
            $IsCreate = 0;
        else
            $IsCreate = 1;
        $MgGame = M("mg_game");
        $_count =  $MgGame->where($map)->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 40;
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
        $list = $MgGame->where($map)->limit($p->firstRow,$p->listRows)->order("clickRate desc,id desc")->select();
        $xReturn = array();
        
        foreach($list as $item)
        {
            $item['page'] = $page;
            $item['IsCreate'] = $IsCreate;
            $xReturn[] = $item;
        }
        echo json_encode($xReturn);
    }

    // 获取PT游戏列表
    public function SearchPtGame()
    {
        $ChineseGameName = I("post.ChineseGameName");
        if(!empty($ChineseGameName))
        {
            $map['ChinesGameName'] = array("like","%".$ChineseGameName."%");
            // 更改游戏点击数
            M("pt_game")->where(array("ChinesGameName"=>$ChineseGameName))->setInc("click",1);
        }

        $ptUserInfo = M("pt_user")->where(array("EBJSZ_username"=>"EBJSZ_".session("SESSION_NAME")))->find();
        if(empty($ptUserInfo))
            $IsCreate = 0;
        else
            $IsCreate = 1;
        $PtGame = M("pt_game");
        $_count =  $PtGame->where($map)->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 40;
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
        $list = $PtGame->where($map)->limit($p->firstRow,$p->listRows)->order("click desc,id desc")->select();
        $xReturn = array();
        
        foreach($list as $item)
        {
            $item['page'] = $page;
            $item['IsCreate'] = $IsCreate;
            $xReturn[] = $item;
        }
        echo json_encode($xReturn);
    }

    // 获取AG游戏列表
    public function SearchAgGame()
    {
        $ChineseGameName = I("post.ChineseGameName");
        if(!empty($ChineseGameName))
        {
            $map['ChinesGameName'] = array("like","%".$ChineseGameName."%");
            // 更改游戏点击数
            M("ag_game")->where(array("ChinesGameName"=>$ChineseGameName))->setInc("click",1);
        }

        $ptUserInfo = M("ag_user")->where(array("ag_username"=>"jsz".session("SESSION_NAME")))->find();
        if(empty($ptUserInfo))
            $IsCreate = 0;
        else
            $IsCreate = 1;
        $AgGame = M("ag_game");
        $_count =  $AgGame->where($map)->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 40;
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
        $list = $AgGame->where($map)->limit($p->firstRow,$p->listRows)->order("click desc,id desc")->select();
        $xReturn = array();
        
        foreach($list as $item)
        {
            $item['page'] = $page;
            $item['IsCreate'] = $IsCreate;

            if(strpos($item['picurl'],"xin")===false)
            {
                $item['picurl'] = "xin/".$item['picurl'];
            }
            if(file_exists("/home/www/www.jsz.com/Jsz/Resourse/Home/images/ag/".$item['picurl']))
            {
                $item['picurl'] = $item['picurl'];
            }
            else
            {
                $item['picurl'] = str_replace('gif','jpg',$item['picurl']);
            }
            $xReturn[] = $item;
        }
        echo json_encode($xReturn);
    }

    // 下载图片到本地
    public static function DownLoadImage($imgPath,$id)
    {
        $imagePathArray = explode('/', $imgPath);
        $filename = array_pop($imagePathArray);
        M("pt_game")->where(array("id"=>$id))->save(array("picname"=>$filename));
        $file = "/home/www/www.jsz.com/Jsz/Resourse/Home/images/pt/".$filename;
        //开始捕捉 
        ob_start(); 
        readfile($imgPath);
        $img=ob_get_contents(); 
        ob_end_clean();

        $fp2 = fopen($file , "a"); 
        fwrite($fp2, $img); 
        fclose($fp2);
        return true;
    }

    // 获取MG游戏连接地址
    public function MgGame()
    {
        $MgGame = new MgGame();
        $gameid = I("post.gameid");
        $response = $MgGame->getGameUrl($gameid);
        if(!empty($response))
        {
            $parseUrl = json_decode($response['result'], true);
            // 更改游戏点击数
            M("mg_game")->where(array("FlashGameID"=>$gameid))->setInc("clickRate",1);
            echo $parseUrl['gameUrl'];
        }
        else
        {
            echo "";
        }
    }

    // 获取PT游戏连接地址
    public function PtGame()
    {
        $PtGame = new PtGame();
        $PtGame->KillUserSession(session("SESSION_NAME"));
        $gameid = I("post.gameid");
        // 更改游戏点击数
        M("pt_game")->where(array("FL"=>$gameid))->setInc("click",1);
        echo $PtGame->GetLaunghGame($gameid);
    }

    // 创建MG游戏玩家
    public function CreateMgAccount()
    {
        $userInfo = M("user")->where(array("username"=>session("SESSION_NAME")))->find();
        $MgGame = new MgGame();
        echo $MgGame->createMgUser($userInfo['username'],$userInfo['id'],$userInfo['parent_id'],$userInfo['parent_path'],$userInfo['group_id'],$userInfo['reg_time'],$userInfo['reg_ip']);
    }

    // 创建EBET游戏玩家
    public function CreateEbetAccount()
    {
        $userInfo = M("user")->where(array("username"=>session("SESSION_NAME")))->find();
        $EbetGame = new EbetGame();
        echo $EbetGame->createEbetUser($userInfo['username'],$userInfo['id'],$userInfo['parent_id'],$userInfo['parent_path'],$userInfo['group_id'],$userInfo['reg_time'],$userInfo['reg_ip']);
    }

    // 创建AG游戏玩家
    public function CreateAgAccount()
    {
        $userInfo = M("user")->where(array("username"=>session("SESSION_NAME")))->find();
        $AgGame = new AgGame();
        echo $AgGame->createAgUser($userInfo['username'],$userInfo['id'],$userInfo['parent_id'],$userInfo['parent_path'],$userInfo['group_id'],$userInfo['reg_time'],$userInfo['reg_ip']);
    }

    // 创建PT游戏玩家
    public function CreatePtAccount()
    {
        $userInfo = M("user")->where(array("username"=>session("SESSION_NAME")))->find();
        $PtGame = new PtGame();
        echo $PtGame->createPtUser($userInfo['username'],$userInfo['id'],$userInfo['parent_id'],$userInfo['parent_path'],$userInfo['group_id'],$userInfo['reg_time'],$userInfo['reg_ip']);
    }

    // 获取MG游戏用户信息
    public function MGUser()
    {
        $MgGame = new MgGame();
        $MgGame->searchUser("MGTestUser");
    }
}



?>