<?php

namespace app\wechat\controller;

use app\wechat\BaseController;
use think\facade\View;

class Error extends BaseController
{
    public function __call($method, $args)
    {
        if (file_exists(app_path() . "/view/" . strtolower($this->request->controller()) . "/" . $method . ".html")) {
            return View::fetch();
        } else {
            return 404;
        }
    }
}
