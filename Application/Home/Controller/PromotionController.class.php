<?php
namespace Home\Controller;

class PromotionController extends CommonController
{
    public function index()
    {
        $title = I('post.title');
        if($title) {
            $map['title'] = array("like","%".$title."%");
        }
        $this->menu = "promotion";
        $map['status'] = 0;
        $count = M('Promotion')->where($map)->count();
        import("Class.Page");
        //分页循环变量
        $listvar = 'list';
        //每页显示的数据量
        $listRows = 8;//C("LISTROWS");
        $roolPage = C("ROOLPAGE");
        $url = "";
        //获取数据总数
        $totalRows = $count;
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
        $promotions = M('Promotion')->where($map)->limit($p->firstRow,$p->listRows)->order("mtime desc")->select();
        //$this->assign('promotions', $promotions);
        //$this->assign('page', $page);
        //$this->display();
        $data = [
            'list' => $promotions,
            'page' => $page,
        ];
        echo json_encode($data);
    }

    public function show()
    {
        $pid = I('get.pid');
        $promotion = M('Promotion')->where("id = $pid")->find();
        $category_info = M('PromotionCategory')->where("id = {$promotion['cid']}")->find();
        $description = $category_info['description'];
        $description = '<p>'.implode('</p><p>', explode("\n", $description)) . '</p>';
        $comment = $category_info['comment'];
        $comment = '<p>' . implode('</p><p>', explode("\n", $comment)) . '</p>';
        $rows = parse_promotion_config($category_info['config']);
        $level2vip = [
            '4' => '白银VIP',
            '5' => '黄金VIP',
            '6' => '钻石VIP',
        ];
        foreach($rows as &$row) {
            foreach($row as $key => &$value) {
                switch ($key) {
                    case 'level':
                        if($value > 3) {
                            $value = $level2vip[$value];
                        }elseif ($value == 0) {
                            $value = '所有会员';
                        }
                        break;
                    case 'max_count':
                        if($value == 0) {
                            $value = '不封顶';
                        }
                        break;
                    case 'return_rate':
                        $value = sprintf("%.2f", $value * 100) . '%';
                        break;
                }
            }
        }
        $keys = config_key_en_2_zn(array_keys($rows[0]));
        $this->assign('title', $promotion['title']);
        $this->assign('subtitle', $promotion['subtitle']);
        $this->assign('description', $description);
        $this->assign('comment', $comment);
        $this->assign('keys', $keys);
        $this->assign('rows', $rows);
        $this->display();
    }
}