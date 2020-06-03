<?php

namespace app\model;

use app\model\BaseModel;

class Conf extends BaseModel
{
    /**
     * 更新配置
     *
     * @param string 配置key
     * @param string 配置值
     * @param int 整形配置
     * @param string 配置描述
     * @return void
     */
    public function updateConf($key, $value, $int = 0, $desc = null)
    {
        $data = [];
        if ($desc) {
            $data['conf_desc'] = $desc;
        }
        $data['conf_value'] = $value;
        $this->where([
            "conf_key"    => $key,
            "conf_readonly" => 0,
        ])->update($data);
    }

    /**
     * 获取AccessToken
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        $conf = $this->where('conf_key', 'WECHAT_ACCESS_TOKEN')->find();
        $access_token = "";
        if (time() > $conf['conf_int']) {
            $retObj = curlHelper("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . config("startadmin.wechat_appid") . "&secret=" . config("startadmin.wechat_appkey"));
            $retObj = $retObj['body'];
            $retObj = json_decode($retObj);
            if (property_exists($retObj, "errcode")) {
                return false;
            }
            $access_token = $retObj->access_token;
            $this->where('conf_key', "WECHAT_ACCESS_TOKEN")->update(["conf_value" => $access_token, "conf_int" => time() + 5000]);
        } else {
            //access_token有效
            $access_token = $conf['conf_value'];
        }
        return $access_token;
    }
}
