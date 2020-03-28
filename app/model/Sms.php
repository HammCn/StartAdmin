<?php

namespace app\model;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
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
        $alisms_appid = config('startadmin.alisms_appid');
        $alisms_appkey = config('startadmin.alisms_appkey');
        $alisms_sign = config('startadmin.alisms_sign');
        $alisms_template = config('startadmin.alisms_template');
        AlibabaCloud::accessKeyClient($alisms_appid, $alisms_appkey)->regionId('cn-hangzhou')->asDefaultClient();
        $success = false;
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->scheme('https')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $phone,
                        'SignName' => $alisms_sign,
                        'TemplateCode' => $alisms_template,
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
     * @return bool
     */
    public function validSmsCode($phone, $code)
    {
        $sms = $this->where('sms_phone', $phone)
            ->where('sms_timeout', ">", time())
            ->where('sms_code', $code)
            ->where('sms_status', 0)
            ->order('sms_createtime desc')->find();
        if ($sms) {
            $this->where('sms_id', $sms['sms_id'])->update([
                'sms_status' => 1,
                'sms_updatetime' => time()
            ]);
            return true;
        } else {
            return false;
        }
    }
}
