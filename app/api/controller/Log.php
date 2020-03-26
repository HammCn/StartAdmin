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
        $this->thisModel = new LogModel();
    }
    /**
     * 清除访问日志
     *
     * @return void
     */
    public function clean()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $this->thisModel->cleanLog();
        return jok('访问日志清理成功');
    }
    /**
     * 访问统计
     *
     * @return void
     */
    public function state()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $datalist = $this->thisModel->getLogStatus();
        return jok('数据读取成功', $datalist);
    }
}
