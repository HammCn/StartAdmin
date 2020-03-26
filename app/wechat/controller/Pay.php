<?php

namespace app\wechat\controller;

use think\facade\View;
use app\wechat\BaseController;

class Pay extends BaseController
{
    public function index()
    {
        //这里只是一个例子 如果你的页面需要微信授权登录时，请在对应的控制器里直接调用这个方法即可
        $error = $this->authorize();
        if ($error) {
            return $error;
        }
        View::assign('wechat', $this->wechat);
        return View::fetch();
    }
    public function getWechatOrder()
    {
        $error = $this->authorize();
        if ($error) {
            return $error;
        }
        //预生成微信订单 下发JSON到客户端
    }
    public function callback()
    {
        //微信支付回调
    }
}
