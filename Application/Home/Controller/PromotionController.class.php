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
        $rows = parse_promotion_config($category_info['config']);
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