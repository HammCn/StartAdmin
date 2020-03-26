<?php

namespace app\model;

use app\model\BaseModel;

class Access extends BaseModel
{
    /**
     * 创建一个新的授权
     *
     * @param [int] UserID
     * @param [plat] 授权平台
     * @return 授权信息|false
     */
    public function createAccess($access_user, $access_plat)
    {
        $this->where([
            "access_user" => $access_user,
            "access_plat" => $access_plat
        ])->update([
            'access_status' => 1
        ]);
        $access_token = sha1(time()) . rand(100000, 99999) . sha1(time());
        $access_id = $this->insertGetId([
            "access_user" => $access_user,
            "access_plat" => $access_plat,
            "access_token" => $access_token,
            "access_ip" => request()->ip(),
            "access_createtime" => time(),
            "access_updatetime" => time()
        ]);
        $access = $this->where("access_id", $access_id)->find();
        return $access ?? false;
    }
}
