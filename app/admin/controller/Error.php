<?php

namespace app\admin\controller;

use app\admin\BaseController;
use think\facade\View;

class Error extends BaseController
{
    public function __call($method, $args)
    {
        if (strtolower($this->request->controller()) == 'user' && strtolower($method) == 'login') {
            cookie('access_token', null);
        }
        if (file_exists(app_path() . "/view/" . strtolower($this->request->controller()) . "/" . strtolower($method) . ".html")) {
            if (key_exists('callback', $args)) {
                View::assign('callback', $args['callback']);
            } else {
                View::assign('callback', '/admin');
            }
            return View::fetch();
        } else {
            echo app_path() . "/view/" . strtolower($this->request->controller()) . "/" . strtolower($method) . ".html";die;
            return 404;
        }
    }
}
