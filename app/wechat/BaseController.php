<?php

declare(strict_types=1);

namespace app\wechat;

use think\App;
use EasyWeChat\Factory;
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

    protected $easyWeChat;
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

        $this->wechat_appid = config("startadmin.wechat_appid");
        $this->wechat_appkey = config("startadmin.wechat_appkey");
        if (!$this->wechat_appid || !$this->wechat_appkey) {
            die('Input wechat appid and appkey first!');
        }
        $this->wechat_config = [
            'app_id' => $this->wechat_appid,
            'secret' => $this->wechat_appkey,
            'token' => config('startadmin.wechat_token') ?? 'StartAdmin',
            'aes_key' => config('startadmin.wechat_aes_key') ?? 'StartAdmin',
            //必须添加部分
            'http' => [ // 配置
                'verify' => false,
                'timeout' => 4.0,
            ],
        ];
        $this->easyWeChat = Factory::officialAccount($this->wechat_config);
    }
    /**
     * 微信服务登录 $this->wechat将为用户数据
     *
     * @param  mixed $openid
     * @return void
     */
    protected function updateWechatUserInfo($openid)
    {
        $user = $this->easyWeChat->user->get($openid);
        if (array_key_exists("errcode", $user)) {
            return false;
        } else {
            $nickname = $user['nickname'];
            $sex = $user['sex'];
            $headimgurl = str_replace("http://", 'https://', $user['headimgurl']);
            $province = $user['province'];
            $city = $user['city'];
            $country = $user['country'];
            $province = $user['province'];
            $this->wechat = $this->wechatModel->where('wechat_openid', $openid)->find();
            if (!$this->wechat) {
                //注册
                $data = ["wechat_openid" => $openid, "wechat_nick" => $nickname, "wechat_head" => $headimgurl, "wechat_sex" => $sex, "wechat_country" => $country, "wechat_city" => $city, "wechat_province" => $province, "wechat_createtime" => time(), "wechat_updatetime" => time()];
                $this->wechatModel->insert($data);
            } else {
                //更新
                $this->wechatModel->where('wechat_openid', $openid)->update(["wechat_nick" => $nickname, "wechat_head" => $headimgurl, "wechat_sex" => $sex, "wechat_country" => $country, "wechat_city" => $city, "wechat_province" => $province,  "wechat_updatetime" => time()]);
            }
            $this->wechat = $this->wechatModel->where('wechat_openid', $openid)->find();
            return $this->wechat;
        }
    }
    /**
     * 调用微信授权
     *
     * @return void
     */
    protected function authorize()
    {
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
}
