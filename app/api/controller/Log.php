<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Log as LogModel;

class Log extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "log_id" => "=", //相同筛选
        ];
        $this->model = new LogModel();
    }
    /**
     * 清除访问日志
     *
     * @return void
     */
    public function clean()
    {
        $this->model->cleanLog();
        jok('访问日志清理成功');
    }
    /**
     * 访问统计
     *
     * @return void
     */
    public function state()
    {
        $datalist = $this->model->getLogStatus();
        jok('数据读取成功', $datalist);
    }
}
