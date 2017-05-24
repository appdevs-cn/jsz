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
        $description = $category_info['description'];
        $description = '<p>'.implode('</p><p>', explode("\n", $description)) . '</p>';
        $comment = $category_info['comment'];
        $comment = '<p>' . implode('</p><p>', explode("\n", $comment)) . '</p>';
        $this->assign('title', $promotion['title']);
        $this->assign('subtitle', $promotion['subtitle']);
        $this->assign('description', $description);
        $this->assign('comment', $comment);
        $this->display();
    }
}