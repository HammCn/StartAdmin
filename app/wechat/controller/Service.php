<?php

namespace app\wechat\controller;

use app\wechat\BaseController;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\Kernel\Messages\Voice;
use EasyWeChat\Kernel\Messages\Video;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
// use EasyWeChat\Kernel\Messages\Article;
// use EasyWeChat\Kernel\Messages\Media;

class Service extends BaseController
{
    protected $wechat;
    public function index()
    {
        $this->easyWeChat->server->push(function ($message) {
            $this->openid = $message['FromUserName'];
            $this->wechat = $this->updateWechatUserInfo($this->openid);
            switch ($message['MsgType']) {
                case 'event':
                    switch ($message['Event']) {
                        case 'subscribe':
                            if ($message['EventKey']) {
                                return $this->doScanQrcode($message['EventKey']);
                            } else {
                                return 'Hello World';
                            }
                            break;
                        case 'SCAN':
                            return $this->doScanQrcode($message['EventKey']);
                            break;
                        case 'LOCATION':
                            $Latitude = $message['Latitude'];
                            $Longitude = $message['Longitude'];
                            $Precision = $message['Precision'];
                            break;
                        case 'CLICK':
                            return '你点的菜单ID为：' . $message['EventKey'];
                            break;
                        case 'VIEW':
                            return '你访问的URL为：' . $message['EventKey'];
                            break;
                        case 'unsubscribe':
                            break;
                        default:
                    }
                    return '不支持的事件' . $message['Event'];
                    break;
                case 'text':
                    $keyword = $message['Content'];
                    return $keyword;
                    break;
                case 'image':
                    $mediaId = $message['MediaId'];
                    return new Image($mediaId);
                    break;
                case 'voice':
                    $mediaId = $message['MediaId'];
                    return new Voice($mediaId);
                    break;
                case 'link':
                    return '标题:' . $message['Title']  . PHP_EOL . '描述:' . $message['Description'] . PHP_EOL .  '链接:' . $message['Url'];
                    break;
                case 'video':
                    //TODO
                    $mediaId = $message['MediaId'];
                    // $thumbMediaId = $message['ThumbMediaId'];
                    new Video($mediaId, [
                        'title' => '啊啊大青蛙',
                        'description' => '...',
                    ]);
                    break;
                case 'location':
                    return '纬度:' . $message['Location_X'] . PHP_EOL . '经度:' . $message['Location_Y'] . PHP_EOL . '缩放:' . $message['Scale'] . PHP_EOL .  '位置:' . $message['Label'];
                    break;
                default:
            }
            $items = [
                new NewsItem([
                    'title'       => "不支持的消息类型" . $message['MsgType'],
                    'description' => '...',
                    'url'         => "https://sa.hamm.cn",
                    'image'       => "",
                    // ...
                ]),
            ];
            return new News($items);
        });
        $response = $this->easyWeChat->server->serve();
        return $response->getContent();
    }
    /**
     * 处理二维码扫描事件
     *
     * @param  mixed $eventKey
     * @return void
     */
    protected function doScanQrcode($eventKey)
    {
        return "该二维码暂时无法解析";
    }
}
