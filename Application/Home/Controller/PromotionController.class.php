<?php
namespace Home\Controller;

class PromotionController extends CommonController
{
    public function index()
    {
        $this->menu = "promotion";
        $this->display();
    }
}