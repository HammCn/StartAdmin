<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Code as CodeModel;

class Code extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "code_id" => "=", //相同筛选
        ];
        $this->thisModel = new CodeModel();
    }
}
