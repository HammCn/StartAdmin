<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Wechat as WechatModel;

class Wechat extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //查询列表时允许的字段
        $this->selectList = "*";
        //查询详情时允许的字段
        $this->selectDetail = "*";
        //筛选字段
        $this->searchFilter = [
            "wechat_id" => "=",
            "wechat_nick" => "like"
        ];
        $this->model = new WechatModel();
    }
}
