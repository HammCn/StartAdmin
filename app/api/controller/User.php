<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\User as model;
use app\model\Sms as SmsModel;

class User extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //查询字段
        $this->selectList = "*";
        $this->selectDetail = "*";
        //筛选字段
        $this->searchFilter = [
            "user_id" => "=",
            "user_account" => "like",
            "user_name" => "like",
            "user_truename" => "like",
            "user_status" => "=",
        ];
        $this->insertFields = [
            "user_account", "user_password", "user_name", "user_idcard", "user_email", "user_group", "user_truename"
        ];
        $this->updateFields = [
            "user_account", "user_password", "user_name", "user_idcard", "user_email", "user_group", "user_truename"
        ];
        $this->insertRequire = [
            'user_name' => "用户昵称必须填写",
            'user_account' => "用户帐号必须填写",
            'user_password' => "密码必须填写",
            'user_group' => "用户组必须填写",
        ];
        $this->updateRequire = [
            'user_name' => "用户昵称必须填写",
            'user_account' => "用户帐号必须填写",
            'user_group' => "用户组必须填写",
        ];
        $this->excelField = [
            "id" => "编号",
            "account" => "帐号",
            "name" => "昵称",
            "idcard" => "身份证",
            "email" => "邮箱",
            "createtime" => "创建时间",
            "updatetime" => "修改时间"
        ];
        $this->model = new model();
    }
    public function add()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $error = $this->validateInsertFields();
        if ($error) {
            return $error;
        }
        $data = $this->getInsertDataFromRequest();
        $data['user_ipreg'] = "127.0.0.1";
        $user = $this->model->getUserByAccount($data["user_account"]);
        if ($user) {
            return jerr("帐号已存在，请重新输入");
        }
        $salt = getRandString(4);
        $password = $data["user_password"];
        $password = encodePassword($password, $salt);
        $data["user_salt"] = $salt;
        $data["user_password"] = $password;
        $this->insertRow($data);
        return jok('用户添加成功');
    }
    public function update()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!$this->pk_value) {
            return jerr($this->pk . "必须填写");
        }
        if (!isInteger($this->pk_value)) {
            return jerr("修改失败,参数错误");
        }
        $item = $this->model->where($this->pk, $this->pk_value)->find();
        if (empty($item)) {
            return jerr("数据查询失败");
        }
        if (intval($this->pk_value) == 1) {
            return jerr("无法修改超管用户信息");
        }
        foreach ($this->updateRequire as $k => $v) {
            if (!input($k)) {
                return jerr($v);
            }
        }
        $data = [];
        foreach (input('post.') as $k => $v) {
            if (in_array($k, $this->updateFields)) {
                $data[$k] = $v;
            }
        }
        $user = $this->model->getUserByAccount($data["user_account"]);
        if ($user && $user[$this->pk] != $item[$this->pk]) {
            return jerr("帐号已存在，请重新输入");
        }
        if (input('new_password')) {
            //设置密码
            $salt = getRandString(4);
            $password = input('new_password');
            $password = encodePassword($password, $salt);
            $data["user_salt"] = $salt;
            $data["user_password"] = $password;
        }
        if ($this->user['user_group'] != 1) {
            //除超级管理员组外 其他任何组不允许修改用户组
            unset($data['user_group']);
        }
        $data[$this->table . "_updatetime"] = time();
        $this->model->where($this->pk, $this->pk_value)->update($data);
        return jok('用户信息更新成功');
    }

    /**
     * 禁用用户
     *
     * @return void
     */
    public function disable()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!$this->pk_value) {
            return jerr($this->pk . "参数必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->model->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            if ($item["user_group"] == 1) {
                return jerr("超级管理员不允许操作！");
            }
            $this->model->where($map)->update([
                $this->table . "_status" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->model->where($this->pk, 'in', $list)->where("user_group > 1")->update([
                $this->table . "_status" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("禁用用户成功");
    }

    /**
     * 启用用户
     *
     * @return void
     */
    public function enable()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!$this->pk_value) {
            return jerr($this->pk . "参数必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->model->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            if ($item["user_group"] == 1) {
                return jerr("超级管理员不允许操作！");
            }
            $this->model->where($map)->update([
                $this->table . "_status" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->model->where($this->pk, 'in', $list)->where("user_group > 1")->update([
                $this->table . "_status" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("启用用户成功");
    }

    /**
     * 删除用户
     *
     * @return void
     */
    public function delete()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!$this->pk_value) {
            return jerr($this->pk . "必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->model->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            //
            if ($item["user_group"] == 1) {
                return jerr("超级管理员不允许操作！");
            }
            $this->model->where($map)->delete();
        } else {
            $list = explode(',', $this->pk_value);
            //批量删除只允许删除用户组不为1的用户
            $this->model->where($this->pk, 'in', $list)->where("user_group > 1")->delete();
        }
        return jok('删除用户成功');
    }
    public function detail()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!$this->pk_value) {
            return jerr($this->pk . "必须填写");
        }
        $item = $this->model->field($this->selectDetail)->where($this->pk, $this->pk_value)->find();
        if (empty($item)) {
            return jerr("没有查询到数据");
        }
        return jok('数据加载成功', $item);
    }
    public function getList()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $map = [];
        $filter = input('post.');
        foreach ($filter as $k => $v) {
            if ($k == 'filter') {
                $k = input('filter');
                $v = input('keyword');
            }
            if ($v === '' || $v === null) {
                continue;
            }
            if (array_key_exists($k, $this->searchFilter)) {
                switch ($this->searchFilter[$k]) {
                    case "like":
                        array_push($map, [$k, 'like', "%" . $v . "%"]);
                        break;
                    case "=":
                        array_push($map, [$k, '=', $v]);
                        break;
                    default:
                }
            }
        }
        $order = strtolower($this->controller) . "_id desc";
        if (input('order')) {
            $order = urldecode(input('order'));
        }
        if (input('per_page')) {
            $this->model->per_page = intval(input('per_page'));
        }
        $dataList = $this->model->getListByPage($map, $order, $this->selectList);
        return jok('用户列表获取成功', $dataList);
    }
    public function login()
    {
        if (!input("user_account")) {
            return jerr('请确认帐号是否正确填写');
        }
        if (!input("user_password")) {
            return jerr('请确认密码是否正确填写');
        }
        $plat = input("plat");
        $user_account = input("user_account");
        $user_password = input("user_password");
        //登录获取用户信息
        $user = $this->model->login($user_account, $user_password);
        if ($user) {
            //创建一个新的授权
            $access = $this->accessModel->createAccess($user['user_id'], $plat);
            if ($access) {
                setCookie('access_token', $access['access_token'], time() + 3600, '/');
                return jok('登录成功', ['access_token' => $access['access_token']]);
            } else {
                return jerr('登录系统异常');
            }
        } else {
            return jerr('帐号或密码错误');
        }
    }
    /**
     * 用户注册接口
     *
     * @return void
     */
    public function reg()
    {
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input("phone")) {
            return jerr("手机号不能为空！");
        }
        $phone = input("phone");
        if (!input("code")) {
            return jerr("短信验证码不能为空！");
        }
        $code = input("code");
        if (!input("password")) {
            return jerr("密码不能为空！");
        }
        $password = input("password");
        $name = $phone;
        if (input("name")) {
            $name = input("name");
        }
        $smsModel = new SmsModel();
        if ($smsModel->validSmsCode($phone, $code)) {
            $user = $this->model->where([
                "user_account" => $phone
            ])->find();
            if ($user) {
                return jerr("该手机号已经注册！");
            }
            $result = $this->model->reg($phone, $password, $name);
            if ($result) {
                return jok("用户注册成功");
            } else {
                return jerr("注册失败，请重试！");
            }
        } else {
            return jerr("短信验证码已过期，请重新获取");
        }
    }
    public function motifyPassword()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!input("oldPassword")) {
            return jerr("你必须要输入你的原密码！");
        }
        if (!input("newPassword")) {
            return jerr("你必须输入一个新的密码！");
        }
        $old_password = input("oldPassword");
        $new_password = input("newPassword");
        if (strlen($new_password) < 6 || strlen($new_password) > 16) {
            return jerr("新密码因为6-16位！");
        }
        if ($this->user['user_password'] != encodePassword($old_password, $this->user['user_salt'])) {
            return jerr("原密码输入不正确，请重试！");
        }
        $result = $this->model->motifyPassword($this->user['user_id'], $new_password);
        if ($result) {
            return jok("密码已重置，请使用新密码登录");
        } else {
            return jerr("注册失败，请重试！");
        }
    }

    /**
     * 重置密码
     *
     * @return void
     */
    public function resetPassword()
    {
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input("phone")) {
            return jerr("手机号不能为空！");
        }
        if (!input("code")) {
            return jerr("短信验证码不能为空！");
        }
        if (!input("password")) {
            return jerr("密码不能为空！");
        }
        $phone = input("phone");
        $code = input("code");
        $password = input("password");
        $smsModel = new SmsModel();
        if ($smsModel->validSmsCode($phone, $code)) {
            $user = $this->model->where([
                "user_account" => $phone
            ])->find();
            if (!$user) {
                return jerr("该手机号尚未注册！");
            }
            $result = $this->model->motifyPassword($user['user_id'], $password);
            if ($result) {
                return jok("密码已重置，请使用新密码登录");
            } else {
                return jerr("注册失败，请重试！");
            }
        } else {
            return jerr("短信验证码已过期，请重新获取");
        }
    }

    /**
     * 获取我的信息
     *
     * @return void
     */
    public function getMyInfo()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $myInfo = $this->user;
        foreach (['user_password', 'user_salt', 'user_accesstoken', 'user_tokentime', 'user_status'] as $key) {
            unset($myInfo[$key]);
        }
        return jok('数据获取成功', $myInfo);
    }
    public function updateMyInfo()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        if (!input("user_name")) {
            return jerr("你确定飘到连名字都可以不要了吗？");
        }
        $data = [
            "user_name" => input("user_name"),
            "user_truename" => input("user_truename"),
            "user_email" => input("user_email"),
            "user_idcard" => input("user_idcard"),
        ];
        $this->model->where("user_id", $this->user['user_id'])->update($data);
        return jok("资料更新成功");
    }
}
