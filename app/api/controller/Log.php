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
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $this->model->cleanLog();
        return jok('访问日志清理成功');
    }
    /**
     * 访问统计
     *
     * @return void
     */
    public function state()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $datalist = $this->model->getLogStatus();
        return jok('数据读取成功', $datalist);
    }
}
