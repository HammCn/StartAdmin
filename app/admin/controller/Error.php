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
        // 判断是否是登录/注册/找回密码
        // 否则进行accesss授权验证 如错误 直接返回
        if (!(strtolower($this->controller) == "User" && in_array(strtolower($this->action), ['login', 'resetPassword', 'reg']))) {
            cookie('access_token', null);
            $error = $this->access();
            print_r($error);die;
            if ($error) {
                return $error;
            }
        }
        if (key_exists('callback', $args)) {
            View::assign('callback', $args['callback']);
        } else {
            View::assign('callback', '/admin');
        }
        return View::fetch();
    }
}
