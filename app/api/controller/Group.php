<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Group as GroupModel;

class Group extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "group_id" => "=", //相同筛选
            "group_name" => "like", //相似筛选
        ];
        $this->insertFields = [
            "group_name", "group_desc"
        ];
        $this->updateFields = [
            "group_name", "group_desc"
        ];
        $this->insertRequire = [
            'group_name' => "組名稱必須填寫"
        ];
        $this->updateRequire = [
            'group_name' => "組名稱必須填寫"
        ];
        $this->thisModel = new GroupModel();
    }
    public function update()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "必须填写");
        }
        if (!isInteger($this->pk_value)) {
            return jerr("修改失败,参数错误");
        }
        $map[$this->pk] = $this->pk_value;
        $item = $this->thisModel->where($map)->find();
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
        foreach (input() as $k => $v) {
            if (in_array($k, $this->updateFields)) {
                $data[$k] = $v;
            }
        }
        if (!input($this->table . "_name")) {
            return jerr("组名称必须填写");
        }
        $data[$this->table . "_updatetime"] = time();
        $this->thisModel->where($this->pk, $this->pk_value)->update($data);
        return jok('用户组信息更新成功');
    }

    /**
     * 禁用用户
     *
     * @return void
     */
    public function disable()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "参数必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->thisModel->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            if ($item[$this->table . "_system"] == 1) {
                return jerr("系统用户组不允许操作！");
            }
            $this->thisModel->where($map)->where($this->pk . " > 1")->update([
                $this->table . "_status" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->where($this->table . "_system", 0)->update([
                $this->table . "_status" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("禁用用户组成功");
    }

    /**
     * 启用用户
     *
     * @return void
     */
    public function enable()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "参数必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->thisModel->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            if ($item[$this->table . "_system"] == 1) {
                return jerr("系统用户组不允许操作！");
            }
            $this->thisModel->where($map)->where($this->pk . " > 1")->update([
                $this->table . "_status" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->where($this->table . "_system", 0)->update([
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("启用用户组成功");
    }

    /**
     * 删除用户
     *
     * @return void
     */
    public function delete()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->thisModel->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            if ($item[$this->table . "_system"] == 1) {
                return jerr("系统用户组不允许操作！");
            }
            $this->thisModel->where($map)->where($this->table . "_system", 0)->delete();
            //删除对应ID的授权记录
            $this->authModel->where([
                "auth_group" => $this->pk_value
            ])->delete();
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->where($this->table . "_system", 0)->delete();
            //删除对应ID的授权记录
            foreach ($list as $item) {
                $group = $this->thisModel->where("group_id", $item)->find();
                if ($group[$this->table . "_system"] == 1) {
                    continue;
                }
                $this->authModel->where([
                    "auth_group" => $item
                ])->delete();
            }
        }
        return jok('删除用户组成功');
    }

    /**
     * 为用户组授权节点
     *
     * @return void
     */
    public function authorize()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "必须填写");
        }
        if (!isInteger($this->pk_value)) {
            return jerr("修改失败,参数错误");
        }
        $map[$this->pk] = $this->pk_value;
        $item = $this->thisModel->where($map)->find();
        if (empty($item)) {
            return jerr("用户组信息查询失败，授权失败");
        }
        $this->authModel->where([
            "auth_group" => $this->pk_value
        ])->delete();
        if ($item[$this->pk] == 1) {
            return jerr("超级管理组无需授权！");
        }
        $node_ids = explode(",", input("node_ids"));
        foreach ($node_ids as $node_id) {
            if (intval($node_id) == 0) {
                continue;
            }
            $this->authModel->insert([
                "auth_group" => $this->pk_value,
                "auth_node" => $node_id,
                "auth_createtime" => time(),
                "auth_updatetime" => time()
            ]);
        }
        return jok('用户组授权成功');
    }
    /**
     * 获取用户组拥有的权限
     *
     * @return void
     */
    public function getAuthorize()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "必须填写");
        }
        if (!isInteger($this->pk_value)) {
            return jerr("修改失败,参数错误");
        }
        $map[$this->pk] = $this->pk_value;
        $item = $this->thisModel->where($map)->find();
        if (empty($item)) {
            return jerr("用户组信息查询失败，授权失败");
        }
        $myAuthorizeList = $this->authModel->where("auth_group", $this->pk_value)->select();
        return jok('ok', $myAuthorizeList);
    }
    public function getList()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $dataList = $this->thisModel->select();
        return jok('用户组列表获取成功', $dataList);
    }
    public function __call($method, $args)
    {
        return $this->index();
    }
}
