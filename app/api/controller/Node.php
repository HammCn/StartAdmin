<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Node as NodeModel;

class Node extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "node_id" => "=", //相同筛选
            "node_system" => "=", //相同筛选
            "node_show" => "=", //相同筛选
            "node_title" => "like", //相似筛选
            "node_desc" => "like", //相似筛选
            "node_module" => "like", //相似筛选
            "node_controller" => "like", //相似筛选
            "node_action" => "like", //相似筛选
        ];
        $this->insertFields = [
            "node_title", "node_desc", "node_module", "node_action", "node_controller", "node_icon", "node_show", "node_pid", "node_order"
        ];
        $this->updateFields = [
            "node_title", "node_desc", "node_module", "node_action", "node_controller", "node_icon", "node_show", "node_pid", "node_order"
        ];
        $this->insertRequire = [
            'node_title' => "节点名称必须填写",
            'node_module' => "节点模块必须填写",
        ];
        $this->updateRequire = [
            'node_title' => "节点名称必须填写",
            'node_module' => "节点模块必须填写",
        ];
        $this->thisModel = new NodeModel();
    }

    public function add()
    {
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        foreach ($this->insertRequire as $k => $v) {
            if (!input($k)) {
                return jerr($v);
            }
        }
        $data = [];
        foreach (input() as $k => $v) {
            if (in_array($k, $this->insertFields)) {
                $data[$k] = $v;
            }
        }
        $data['node_module'] = strtolower($data['node_module']);
        $data['node_controller'] = input("node_controller") ? strtolower($data['node_controller']) : "";
        $data['node_action'] = input("node_action") ? strtolower($data['node_action']) : "";
        $data[$this->table . "_updatetime"] = time();
        $data[$this->table . "_createtime"] = time();
        $this->thisModel->insert($data);
        return jok('用户添加成功');
    }
    public function update()
    {
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
        $data['node_module'] = strtolower($data['node_module']);
        $data['node_controller'] = input("node_controller") ? strtolower($data['node_controller']) : "";
        $data['node_action'] = input("node_action") ? strtolower($data['node_action']) : "";
        $data[$this->table . "_updatetime"] = time();
        $this->thisModel->where($this->pk, $this->pk_value)->update($data);
        return jok('节点信息更新成功');
    }

    /**
     * 禁用用户
     *
     * @return void
     */
    public function disable()
    {
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
                return jerr("系统节点不允许操作！");
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
        return jok("禁用节点成功");
    }

    /**
     * 启用用户
     *
     * @return void
     */
    public function enable()
    {
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
                return jerr("系统节点不允许操作！");
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
        return jok("启用节点成功");
    }

    /**
     * 删除用户
     *
     * @return void
     */
    public function delete()
    {
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
                return jerr("系统节点不允许操作！");
            }
            $this->thisModel->where($map)->where($this->table . "_system", 0)->delete();
            //删除对应ID的授权记录
            $this->authModel->where("auth_node", $this->pk_value)->delete();
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->where($this->table . "_system", 0)->delete();
            //删除对应ID的授权记录
            $this->authModel->where("auth_node", 'in', $list)->delete();
        }
        return jok('删除节点成功');
    }

    public function getList()
    {
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $order = $this->table . "_order desc," . $this->pk . " asc";
        $map = [
            "node_pid" => 0
        ];
        $datalist = $this->thisModel->where($map)->order($order)->select();
        $subMap = [];
        $filter = input();
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
                        array_push($subMap, [$k, 'like', "%" . urldecode($v) . "%"]);
                        break;
                    case "=":
                        array_push($subMap, [$k, '=', urldecode($v)]);
                        break;
                    default:
                }
            }
        }
        for ($i = 0; $i < count($datalist); $i++) {
            $subDatalist = $this->thisModel->field($this->selectList)->where($subMap)->where($this->table . "_pid", $datalist[$i][$this->pk])->order($order)->select();
            $datalist[$i]['sub'] = $subDatalist;
        }
        return jok('success', [
            'data'  => $datalist,
            'map'   => $map
        ]);
    }
    /**
     * 显示到菜单中
     *
     * @return void
     */
    public function show_menu()
    {
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $map = [];
        if (isInteger($this->pk_value)) {
            $this->thisModel->where($this->pk, $this->pk_value)->update([
                $this->table . "_show" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->update([
                $this->table . "_show" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("显示成功");
    }

    /**
     * 从菜单中隐藏
     *
     * @return void
     */
    public function hide_menu()
    {
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $map = [];
        if (isInteger($this->pk_value)) {
            $this->thisModel->where($this->pk, $this->pk_value)->update([
                $this->table . "_show" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->update([
                $this->table . "_show" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("隐藏成功");
    }
}
