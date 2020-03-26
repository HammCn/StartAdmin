<?php

namespace app\wechat\controller;

use app\wechat\BaseController;

class Jssdk extends BaseController
{
    protected $ServiceToken = 'StartAdmin';
    protected $wechat;
    public function getJsPackage()
    {
        $jsPackage = $this->getSignPackage(input('url'));
        return json($jsPackage);
    }
}
