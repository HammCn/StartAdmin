<?php

namespace app\model;

use AlibabaCloud\Client\AlibabaCloud;
use app\model\BaseModel;

class Sms extends BaseModel
{
    /**
     * 发送短信
     *
     * @param string 手机号码
     * @param string 验证码
     * @return void
     */
    public static function sendSms($phone, $code)
    {
        AlibabaCloud::accessKeyClient('LTAI4Ffh4KFujekzpX7YugGY', 'c4EK5A6BcR72kAEztnZ3fR14CPCeIR')->regionId('cn-hangzhou')->asDefaultClient();
        $success = false;
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone,
                        'SignName' => "鱼师傅",
                        'TemplateCode' => "SMS_174020628",
                        'TemplateParam' => '{"code":"' . $code . '"}',
                    ],
                ])
                ->request();
            $success = true;
        } catch (ClientException $e) {
            $success = false;
        } catch (ServerException $e) {
            $success = false;
        }
        return $success;
    }

    /**
     * 校验短信验证码
     *
     * @param string 手机号码
     * @param string 验证码
     * @return void
     */
    public function validSmsCode($phone, $code)
    {
        $sms = $this->where([
            'sms_phone' => $phone,
            'sms_timeout' => [">", time()],
            'sms_code' => $code
        ])->order('sms_createtime desc')->find();
        if ($sms) {
            return true;
        } else {
            return false;
        }
    }
}
