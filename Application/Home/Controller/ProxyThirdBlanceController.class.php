<?php
namespace Home\Controller;

use Home\Controller\CommonController;
use Home\Model\MgGameModel as MgGame;
use Home\Model\PtGameModel as PtGame;
use Home\Model\AgGameModel as AgGame;
use Home\Model\EbetGameModel as EbetGame;

class ProxyThirdBlanceController extends CommonController
{
    public function index()
    {
        $this->proxymanager = "thirdgame";
        $this->reportmanager = "blance";
        $this->menu = "proxy";
        $this->display();
    }

    // 获取用户第三方游戏的余额
    public function ProxySearchBlance()
    {
        $MgGame = new MgGame();
        $PtGame = new PtGame();
        $AgGame = new AgGame();
        $EbetGame = new EbetGame();

        $username = I("post.username");
        $uid = session("SESSION_ID");
        if(!empty($username))
        {
            $map['username'] = $username;
        }
        $map['_string'] = "FIND_IN_SET(".$uid.",parent_path)";

        $UserModel = M("user");

        $_count = $UserModel->where($map)->field("username")->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 15;
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
        $list = $UserModel->where($map)->field("username")->limit($p->firstRow,$p->listRows)->order("id desc")->select();
        $xReturn = array();
        foreach($list as $item)
        {
            $temp = array();
            $temp['username'] = $item['username'];
            $temp['mg_blance'] = $MgGame->searchUserBalance($item['username']);
            $temp['ag_blance'] = $AgGame->SearchUserBlance($item['username']);
            $temp['pt_blance'] = $PtGame->SearchUserBlance($item['username']);
            $temp['ebet_blance'] = $EbetGame->searchUserBalance($item['username']);
            $temp['page'] = $page;
            $xReturn[] = $temp;
        }
        echo json_encode($xReturn);
    }
}




?>