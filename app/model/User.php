<?php

namespace app\model;

use app\model\BaseModel;

class User extends BaseModel
{
    /**
     * 用户登录
     *
     * @param string 帐号
     * @param string 密码
     * @return void
     */
    public function login($user_account, $user_password)
    {
        $user = $this->where([
            "user_account" => $user_account,
        ])->find();
        if ($user) {
            //判断密码是否正确
            $salt = $user['user_salt'];
            $password = $user['user_password'];
            if ($password != encodePassword($user_password, $salt)) {
                return false;
            }
            return $user->toArray() ?? false;
        } else {
            return false;
        }
    }

    /**
     * 用户注册
     *
     * @param string 手机号
     * @param string 密码
     * @param string 昵称
     * @return void
     */
    public function reg($phone, $password, $name)
    {
        $salt = getRandString(4);
        $password = encodePassword($password, $salt);
        return $this->insert([
            "user_account" => $phone,
            "user_password" => $password,
            "user_salt" => $salt,
            "user_name" => $name,
            "user_group" => config('startadmin.default_group') ?? 0,
            "user_ipreg" => request()->ip(),
            "user_createtime" => time(),
            "user_updatetime" => time()
        ]);
    }
    public function getListByPage($maps, $order, $field = "*")
    {
        $resource = $this->view('user', $field)->view('group', '*', 'group.group_id = user.user_group', 'left');
        foreach ($maps as $map) {
            switch (count($map)) {
                case 1:
                    $resource = $resource->where($map[0]);
                    break;
                case 2:
                    $resource = $resource->where($map[0], $map[1]);
                    break;
                case 3:
                    $resource = $resource->where($map[0], $map[1], $map[2]);
                    break;
                default:
            }
        }
        return $resource->order($order)->paginate($this->per_page);
    }

    /**
     * 重置密码
     *
     * @param string UID
     * @param string 密码
     * @return void
     */
    public function motifyPassword($user_id, $password)
    {
        $access = new Access();
        $access->where('access_user', $user_id)->delete();
        $salt = getRandString(4);
        $password = encodePassword($password, $salt);
        return $this->where([
            "user_id" => $user_id
        ])->update([
            "user_password" => $password,
            "user_salt" => $salt,
        ]);
    }

    /**
     * 通过帐号获取access_token 慎用 仅在确保手机号有效的前提下使用
     *
     * @param  string 帐号
     * @return void
     */
    public function loginByAccount($user_account)
    {
        $user = $this->where([
            "user_account" => $user_account
        ])->find();
        if ($user) {
            return $user->toArray() ?? false;
        } else {
            return false;
        }
    }
    /**
     * AccessToken获取用户信息
     *
     * @param string access_token
     * @return void
     */
    public function getUserByAccessToken($access_token)
    {
        $Access = new Access();
        $access = $Access->where([
            "access_token" => $access_token,
            "access_status" => 0
        ])->find();
        if ($access) {
            $user = $this->where("user_id", $access['access_user'])->find();
            return $user->toArray() ?? false;
        } else {
            return false;
        }
    }
    /**
     * 帐号获取用户信息
     *
     * @param string 帐号
     * @return void
     */
    public function getUserByAccount($user_account)
    {
        $user = $this->where([
            "user_account" => $user_account
        ])->find();
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }
}
