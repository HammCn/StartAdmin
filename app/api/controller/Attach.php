<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Attach as AttachModel;

class Attach extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "attach_id" => "=", //相同筛选
            "attach_key" => "like", //相似筛选
            "attach_value" => "like", //相似筛选
            "attach_desc" => "like", //相似筛选
            "attach_readonly" => "=", //相似筛选
        ];
        $this->thisModel = new AttachModel();
    }

}
