<?php

namespace app\wechat\controller;

use app\wechat\BaseController;

class Test extends BaseController
{
    public function index()
    {
        $response = $this->easyWeChat->oauth->scopes(['snsapi_userinfo'])
            ->redirect();
        return $response->send();
    }
}
