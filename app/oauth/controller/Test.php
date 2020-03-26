<?php

namespace app\oauth\controller;

use think\app;
use app\oauth\BaseController;

class Test extends BaseController
{
    protected $client_id = "1";
    protected $client_secret = "123456";
    public function __construct(App $app)
    {
        parent::__construct($app);
    }
    public function index()
    {
        if (input("access_token")) {
            $access_token = input("access_token");
            $ret = httpGetFull(getFullDomain() . "/oauth/user/getUserinfo/?access_token=" . $access_token);
            $retObj = json_decode($ret);
            if ($retObj->code != 200) {
                return $this->login();
            } else {
                print_r($retObj->data);
                echo '<br><hr><br><a href="/oauth/test">重新登录</a>';
            }
        } else {
            return $this->login();
        }
    }
    public function callback()
    {
        if (input("code")) {
            $code = input("code");
            $ret = httpGetFull(getFullDomain() . "/oauth/accesstoken/?client_id=" . $this->client_id . "&client_secret=" . $this->client_secret . "&code=" . $code);
            $retObj = json_decode($ret);
            if ($retObj->code != 200) {
                return $this->login();
            } else {
                $access_token = $retObj->data;
                echo 'access_token is : ' . $access_token . '<br><br><a href="/oauth/test/?access_token=' . $access_token . '">查看个人信息</a>';
            }
        } else {
            echo "Code Missing!";
        }
    }
    protected function login()
    {
        return redirect(getFullDomain() . "/oauth/authorize/?client_id=1&redirect=" . urlencode(getFullDomain() . "/oauth/test/callback/"));
    }
}
