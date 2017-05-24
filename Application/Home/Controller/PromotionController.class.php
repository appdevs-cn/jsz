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