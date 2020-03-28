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
        $retStr = httpGetFull("https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . config("startadmin.wechat_appid") . "&secret=" . config("startadmin.wechat_appkey") . "&code={$code}&grant_type=authorization_code");
        $retObj = json_decode($retStr);
        if (isset($retObj->errcode)) {
            return redirect($callback);
        } else {
            $access_token = $retObj->access_token;
            $openid = $retObj->openid;
            $retStr = httpGetFull("https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$openid}&lang=zh_CN");
            $retObj = json_decode($retStr);
            if (isset($retObj->errcode)) {
                return redirect($callback);
            } else {
                $nickname = urlencode($retObj->nickname);
                $sex = $retObj->sex;
                $headimgurl = str_replace("http://", 'https://', $retObj->headimgurl);
                $province = $retObj->province;
                $city = $retObj->city;
                $country = $retObj->country;
                $wechat = $this->wechatModel->where('wechat_openid', $openid)->find();
                $wechat_id = 0;
                if (!$wechat) {
                    //注册
                    $data = array("wechat_openid" => $openid, "wechat_nick" => $nickname, "wechat_head" => $headimgurl, "wechat_sex" => $sex, "wechat_country" => $country, "wechat_city" => $city, "wechat_province" => $province, "wechat_createtime" => time(), "wechat_updatetime" => time());
                    $wechat_id = $this->wechatModel->insertGetId($data);
                } else {
                    //更新
                    if (!empty($wechat["wechat_remark"])) {
                        //如果设置了个性名称 不修改昵称
                        $nickname = $wechat['wechat_remark'];
                    }
                    $this->wechatModel->where('wechat_openid', $openid)->update(array("wechat_nick" => $nickname, "wechat_head" => $headimgurl, "wechat_sex" => $sex, "wechat_country" => $country, "wechat_city" => $city, "wechat_province" => $province,  "wechat_updatetime" => time()));
                    $wechat_id = $wechat['wechat_id'];
                }
                //301到原来的页面
                cookie('wechat_id', $wechat_id, 3600000);
                cookie('wechat_ticket', getTicket($wechat_id), 3600000);
                return redirect($callback);
            }
        }
    }
}
