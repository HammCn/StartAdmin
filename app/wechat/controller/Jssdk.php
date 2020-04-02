<?php

namespace app\wechat\controller;

use app\wechat\BaseController;

class Jssdk extends BaseController
{
    protected $ServiceToken = 'StartAdmin';
    protected $wechat;
    public function index()
    {
        $this->easyWeChat->jssdk->setUrl(input('url'));
        $ret = $this->easyWeChat->jssdk->buildConfig([

            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'onVoicePlayEnd',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'translateVoice',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard',
            'openAddress'
        ], false, false, false);
        return $ret;
    }
}
