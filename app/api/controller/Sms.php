<?php

namespace app\api\controller;

use think\App;
use app\model\Sms as SmsModel;
use app\model\Validate as ValidateModel;

class Sms
{
    protected $thisModel = null;
    public function __construct(App $app)
    {
        $this->thisModel = new SmsModel();
    }

    public function send()
    {
        //验证图形验证码
        $validateModel = new ValidateModel();
        $error = $validateModel->validateImgCode(input('token'), input('code'));
        if ($error) {
            return $error;
        }
        if (input("phone")) {
            $phone = input('phone');
            $code = cache("SMS_" . $phone);
            if ($code) {
                return jerr('发送短信太频繁，请稍候再试');
            }

            $code = rand(100000, 999999);
            $error = $this->thisModel->sendSms($phone, $code);
            if ($error) {
                return $error;
            }
            cache('SMS_' . $phone, $code, 300);
            return jok('短信验证码已经发送至你的手机');
        } else {
            return jerr("手机号为必填信息，请填写后提交");
        }
    }
    public function __call($method, $args)
    {
        return $this->index();
    }
}
