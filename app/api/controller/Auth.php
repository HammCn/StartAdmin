<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Auth as AuthModel;

class Auth extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "auth_id" => "=", //相同筛选
        ];
        $this->thisModel = new AuthModel();
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
        $this->thisModel->cleanAuth();
        return jok('授权信息清理成功');
    }
}
