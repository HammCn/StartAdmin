<?php

namespace app\wechat\controller;

use app\wechat\BaseController;

class Service extends BaseController
{
    protected $ServiceToken = 'StartAdmin';
    protected $wechat;
    public function index()
    {
        $this->getWechatConfig();
        $postStr = file_get_contents("php://input");
        if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $this->openid = trim($postObj->FromUserName);
            $this->appLongid = trim($postObj->ToUserName);
            $this->wechat = $this->login($this->openid);
            $msgType = trim($postObj->MsgType);
            if ($msgType == "text") {
                $keyword = trim($postObj->Content);
                switch ($keyword) {
                    case "首页":
                        break;
                    default:
                        return $this->echoText($keyword);
                }
                exit;
            } else if ($msgType == "event") {
                $event = trim($postObj->Event);
                if ($event == "subscribe") {
                    $eventKey = trim($postObj->EventKey);
                    if (empty($eventKey)) {
                        return $this->echoText("Hello StartAdmin");
                    } else {
                        $eventKey = str_replace("qrscene_", "", $eventKey);
                        $this->doScanQrcode($eventKey);
                    }
                } else if ($event == "CLICK") {
                    $eventKey = trim($postObj->EventKey);
                    switch ($eventKey) {
                        case '菜单名称':
                            break;
                        default:
                            return $this->echoText($eventKey);
                    }
                } else if ($event == "SCAN") {
                    $eventKey = trim($postObj->EventKey);
                    $this->doScanQrcode($eventKey);
                } else if ($event == "scancode_waitmsg") {
                    $scanResult = trim($postObj->ScanCodeInfo->ScanResult);
                    return $this->echoText($scanResult);
                } else if ($event == "LOCATION") {
                    return $this->echoText("位置已经获取到了！");
                } else {
                    return $this->echoText("暂不支持的事件" . $event);
                }
            } else if ($msgType == "voice") {
                //语音
                $keyword = trim($postObj->Recognition);
                $MediaId = ($postObj->MediaId);
                $textTemp = "<xml><ToUserName><![CDATA[" . $this->openid . "]]></ToUserName><FromUserName><![CDATA[" . $this->appLongid . "]]></FromUserName><CreateTime>" . time() . "</CreateTime><MsgType><![CDATA[voice]]></MsgType><Voice><MediaId><![CDATA[" . $MediaId . "]]></MediaId></Voice></xml>";
                echo $textTemp;
                exit;
            } else if ($msgType == "image") {
                //图片
                $MediaId = ($postObj->MediaId);
                $textTemp = "<xml><ToUserName><![CDATA[" . $this->openid . "]]></ToUserName><FromUserName><![CDATA[" . $this->appLongid . "]]></FromUserName><CreateTime>" . time() . "</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[" . $MediaId . "]]></MediaId></Image></xml>";
                echo $textTemp;
                exit;
            } else {
                return $this->echoText("暂不支持的消息类型" . $msgType);
            }
        } else {
            if ($this->checkSignature() || env('APP_DEBUG')) {
                return input('get.echostr');
            } else {
                return 'Bad Token!';
            }
        }
    }
    /**
     * 微信接入签名验证
     *
     * @return bool
     */
    private function checkSignature()
    {
        $signature = input('get.signature');
        $timestamp = input('get.timestamp');
        $nonce = input('get.nonce');

        $tmpArr = array($this->ServiceToken, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
    /**
     * 处理二维码扫描事件
     *
     * @param  mixed $eventKey
     * @return void
     */
    protected function doScanQrcode($eventKey)
    {
        $qrData = json_decode(urldecode($eventKey));
        switch ($qrData->type) {
            case "desk":
                //TODO
                //                $qrData->id;
                break;
            default:
                return $this->echoText("该二维码暂时无法解析");
                exit;
        }
    }
    /**
     * 微信服务登录 $this->wechat将为用户数据
     *
     * @param  mixed $openid
     * @return void
     */
    protected function login($openid)
    {
        $retStr = httpGetFull("https://api.weixin.qq.com/cgi-bin/user/info?access_token=" . $this->access_token . "&openid={$openid}&lang=zh_CN");
        $retObj = json_decode($retStr);
        if (property_exists($retObj, "errorcode")) {
            return null;
        } else {
            $nickname = $retObj->nickname;
            $sex = $retObj->sex;
            $headimgurl = str_replace("http://", 'https://', $retObj->headimgurl);
            $province = $retObj->province;
            $city = $retObj->city;
            $country = $retObj->country;
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
        }
    }

    /**
     * 输出文本消息到微信
     *
     * @param  mixed $msg
     * @return void
     */
    protected function echoText($msg = "【系统错误】\n\n输出参数错误！")
    {
        return "<xml><ToUserName><![CDATA[" . $this->openid . "]]></ToUserName><FromUserName><![CDATA[" . $this->appLongid . "]]></FromUserName><CreateTime>" . time() . "</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[" . $msg . "]]></Content></xml>";
    }
}
