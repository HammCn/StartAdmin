<?php

namespace app\api\controller;

use think\App;
use EasyWeChat\Factory;
use app\api\BaseController;
use app\model\Weapp as WeappModel;

class Weapp extends BaseController
{
    protected $weapp_appid;
    protected $weapp_appkey;
    protected $easyWeApp;
    public function __construct(App $app)
    {
        parent::__construct($app);
        //查询列表时允许的字段
        $this->selectList = "*";
        //查询详情时允许的字段
        $this->selectDetail = "*";
        //筛选字段
        $this->searchFilter = [
            "weapp_id" => "=",
            "weapp_openid" => "like",
        ];
        $this->insertFields = [
            //允许添加的字段列表
            "weapp_openid",
        ];
        $this->updateFields = [
            //允许更新的字段列表
            "weapp_openid",
        ];
        $this->insertRequire = [
            //添加时必须填写的字段
            // "字段名称"=>"该字段不能为空"

        ];
        $this->updateRequire = [
            //修改时必须填写的字段
            // "字段名称"=>"该字段不能为空"

        ];
        $this->thisModel = new WeappModel();
    }
    /**
     * 初始化微信小程序配置
     *
     * @return void
     */
    private function initWeAppConfig()
    {
        $this->weapp_appid = config('startadmin.weapp_appid'); //小程序APPID
        $this->weapp_appkey = config("startadmin.weapp_appkey"); //小程序的APPKEY
        if (!$this->weapp_appid || !$this->weapp_appkey) {
            return jerr("请先配置微信小程序appid和secret");
        }
        $weapp_config = [
            'app_id' => $this->weapp_appid,
            'secret' => $this->weapp_appkey,
            //必须添加部分
            'http' => [ // 配置
                'verify' => false,
                'timeout' => 4.0,
            ],
        ];
        $this->easyWeApp = Factory::miniProgram($weapp_config);
        return null;
    }
    /**
     * 微信小程序登录
     *
     * @return void
     */
    public function wxAppLogin()
    {
        $error = $this->initWeAppConfig();
        if ($error) {
            return $error;
        }
        if (input("?code")) {
            $code = input("code");
            $ret = $this->easyWeApp->auth->session($code);
            if (array_key_exists("session_key", $ret)) {
                $session_key = $ret['session_key'];
                $openid = $ret['openid'];
                return jok('', [
                    "session_key" => $session_key,
                    "openid" => $openid
                ]);
            } else {
                return jerr("获取session_key失败");
            }
        } else {
            return jerr("你应该传code给我");
        }
    }
    /**
     * 微信小程序手机号解密
     *
     * @return void
     */
    public function wxPhoneDecodeLogin()
    {
        $error = $this->initWeAppConfig();
        if ($error) {
            return $error;
        }
        if (input("?iv") && input("?encryptedData") && input("?session_key")) {
            $iv = input("iv");
            $encryptedData = input("encryptedData");
            $session_key = input("session_key");
            try {
                $decryptedData = $this->easyWeApp->encryptor->decryptData($session_key, $iv, $encryptedData);

                if (array_key_exists("phoneNumber", $decryptedData)) {
                    return jok('success', [
                        'phone' => $decryptedData['phoneNumber']
                    ]);
                } else {
                    return jerr("解密出了问题");
                }
            } catch (\Exception $e) {
                return jerr($e->getMessage());
            }
        } else {
            return jerr("是不是所有的参数都POST过来了");
        }
    }
}
