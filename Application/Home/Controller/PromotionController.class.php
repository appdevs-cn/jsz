<?php
namespace Home\Controller;

class PromotionController extends CommonController
{
    public function index()
    {
        $this->menu = "promotion";
        $this->display();
    }

    public function show()
    {
        $pid = I('get.pid');
        $promotion = M('Promotion')->where("id = $pid")->find();
        $category_info = M('PromotionCategory')->where("id = {$promotion['cid']}")->find();
        $this->assign('title', $promotion['title']);
        $this->assign('subtitle', $promotion['subtitle']);
        $this->assign('description', $category_info['description']);
        $this->display();
    }
}