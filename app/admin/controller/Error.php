<?php

namespace app\admin\controller;

use app\admin\BaseController;
use think\facade\View;

class Error extends BaseController
{
    /**
     * 监听所有请求 渲染对应控制器下方法的页面
     */
    public function __call($method, $args)
    {
        if (strtolower($this->request->controller()) == 'user' && strtolower($method) == 'login') {
            cookie('access_token', null);
        }
        if (key_exists('callback', $args)) {
            View::assign('callback', $args['callback']);
        } else {
            View::assign('callback', '/admin');
        }
        return View::fetch();
    }
}
