<?php

declare(strict_types=1);

namespace app\wechat;

use think\App;
use app\model\Conf as ConfModel;
use app\model\Wechat as WechatModel;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    protected $confModel;
    protected $wechatModel;
    protected $access_token;
    protected $wechat_appid;
    protected $wechat_appkey;
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    protected $module;
    protected $controller;
    protected $action;

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->module = "wechat";
        $this->controller = $this->request->controller() ? $this->request->controller() : "Index";
        $this->action = strtolower($this->request->action()) ? strtolower($this->request->action()) : "index";

        $this->confModel = new ConfModel();
        $this->wechatModel = new wechatModel();

        $configs = $this->confModel->select()->toArray();
        $c = [];
        foreach ($configs as $config) {
            $c[$config['conf_key']] = $config['conf_value'];
        }
        config($c, 'startadmin');
    }    
    /**
     * 初始化微信的相关配置
     *
     * @return void
     */
    protected function getWechatConfig()
    {
        $this->wechat_appid = config("startadmin.wechat_appid");
        $this->wechat_appkey = config("startadmin.wechat_appkey");
        if (!$this->wechat_appid || !$this->wechat_appkey) {
            die('Input wechat appid and appkey first!');
        }
    }
    /**
     * 调用微信授权
     *
     * @return void
     */
    protected function authorize()
    {
        $this->getWechatConfig();
        $this->access_token = $this->confModel->getAccessToken();
        $wechat_id = cookie('wechat_id');
        $wechat_ticket = cookie('wechat_ticket');
        if ($wechat_ticket == getTicket($wechat_id)) {
            $this->wechat = $this->wechatModel->where('wechat_id', $wechat_id)->find();
            if ($this->wechat) {
                $this->wechat = $this->wechat->toArray();
                return null;
            }
        }
        //生成授权所需要的回调地址 并重定向到Authorize控制器进行微信授权
        $callback = '/';
        if ($this->module != "index") {
            $callback .= strtolower($this->module) . '/';
        } else {
            if ($this->controller != "Index" && $this->action != "index") {
                $callback .= strtolower($this->module) . '/';
            }
        }
        if ($this->controller != "Index") {
            $callback .= strtolower($this->controller) . '/';
        } else {
            if ($this->action != "index") {
                $callback .= strtolower($this->controller) . '/';
            }
        }
        if ($this->action != "index") {
            $callback .= strtolower($this->action) . '/';
        }
        $i = 0;
        foreach (input('get.') as $k => $v) {
            if ($i == 0) {
                $callback .= "?";
            } else {
                $callback .= "&";
            }
            if (!in_array($k, ['code', 'state', 'from', 'isappinstalled'])) {
                $callback .= $k . "=" . $v;
            }
            $i++;
        }
        return redirect('/wechat/authorize?callback=' . urlencode($callback));
    }
    /**
     * 获取签名数据包
     *
     * @return void
     */
    protected function getSignPackage($url = '')
    {
        $jsapiTicket = $this->getJsApiTicket();
        if (!$url) {
            $url = getFullDomain() . $_SERVER['REQUEST_URI'];
        }
        $timestamp = time();
        $nonceStr = getRandString(16);

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array("appId" => config("startadmin.wechat_appid"), "nonceStr" => $nonceStr, "timestamp" => $timestamp, "url" => $url, "signature" => $signature, "rawString" => $string);
        return $signPackage;
    }

    /**
     * 获取JS API的票据
     *
     * @return void
     */
    private function getJsApiTicket()
    {
        $appData = $this->confModel->where('conf_key', 'WECHAT_JS_TICKET')->find();
        $jsapiTicket = "";
        if (time() > $appData['conf_int']) {
            //需要重新请求access_token
            $access_token = $this->confModel->getAccessToken();
            $retObj = httpGetFull("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi");
            $retObj = json_decode($retObj);

            $jsapiTicket = $retObj->ticket;
            $this->confModel->where('conf_key', "WECHAT_JS_TICKET")->update(array("conf_value" => $jsapiTicket, "conf_int" => (time() + 5000)));
        } else {
            //access_token有效
            $jsapiTicket = $appData['conf_value'];
        }
        return $jsapiTicket;
    }
}
