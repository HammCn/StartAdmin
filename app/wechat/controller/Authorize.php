<?php

namespace app\wechat\controller;

use app\wechat\BaseController;

class Authorize extends BaseController
{
    public function index()
    {
        $callback = '/wechat/user';
        if (input('callback')) {
            $callback = urldecode(input('callback'));
        }
        $callbackWechat = urlencode(getFullDomain() . "/wechat/authorize/callback/");
        return redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->wechat_appid . "&redirect_uri=" . $callbackWechat . "&response_type=code&scope=snsapi_userinfo&state=" . urlencode($callback) . "#wechat_redirect");
    }
    public function callback()
    {
        $callback = '/wechat';
        if (input('state')) {
            $callback = urldecode(input('state'));
        }
        if (!input('code')) {
            return redirect($callback);
        }
        $code = input('code');
        $retStr = httpGetFull("https://api.weixin.qq.com/sns/oauth2/access_token?appid=" .  $this->wechat_appid . "&secret=" . $this->wechat_appkey . "&code={$code}&grant_type=authorization_code");
        $retObj = json_decode($retStr);
        if (isset($retObj->errcode)) {
            return redirect($callback);
        } else {
            $access_token = $retObj->access_token;
            $openid = $retObj->openid;
            if (!$this->updateWechatUserInfo($openid)) {
                return redirect($callback);
            }
            cookie('wechat_id', $this->wechat['wechat_id'], 3600000);
            cookie('wechat_ticket', getTicket($this->wechat['wechat_id']), 3600000);
            return redirect($callback);
        }
    }
}
