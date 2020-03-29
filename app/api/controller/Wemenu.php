<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Wemenu as WemenuModel;

class Wemenu extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //查询列表时允许的字段
        $this->selectList = "*";
        //查询详情时允许的字段
        $this->selectDetail = "*";
        //筛选字段
        $this->searchFilter = [
            "wemenu_id" => "=",
            "wemenu_name" => "like", "wemenu_type" => "like", "wemenu_url" => "like", "wemenu_appid" => "like", "wemenu_key" => "like", "wemenu_page" => "like", "wemenu_pid" => "=",
        ];
        $this->insertFields = [
            //允许添加的字段列表
            "wemenu_name", "wemenu_type", "wemenu_url", "wemenu_appid", "wemenu_key", "wemenu_page", "wemenu_pid",
        ];
        $this->updateFields = [
            //允许更新的字段列表
            "wemenu_name", "wemenu_type", "wemenu_url", "wemenu_appid", "wemenu_key", "wemenu_page", "wemenu_pid",
        ];
        $this->insertRequire = [
            //添加时必须填写的字段
            // "字段名称"=>"该字段不能为空"
            "wemenu_name" => "菜单名称必须填写",
        ];
        $this->updateRequire = [
            //修改时必须填写的字段
            // "字段名称"=>"该字段不能为空"
            "wemenu_name" => "菜单名称必须填写",
        ];
        $this->thisModel = new WemenuModel();
    }
    public function add()
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
        if ($data['wemenu_pid'] == 0) {
            $parentCount = $this->thisModel->where('wemenu_pid', 0)->count();
            if ($parentCount >= 3) {
                return jerr("父菜单最多允许三个，添加失败！");
            }
        }
        $data[$this->table . "_updatetime"] = time();
        $data[$this->table . "_createtime"] = time();
        $this->thisModel->insert($data);
        return jok('添加成功');
    }
    /**
     * 获取列表
     *
     * @return void
     */
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
        $dataList = $this->thisModel->where('wemenu_pid', 0)->select()->toArray();
        for ($i = 0; $i < count($dataList); $i++) {
            $itemList = $this->thisModel->where("wemenu_pid", $dataList[$i]['wemenu_id'])->select()->toArray();
            $dataList[$i]['sub'] = $itemList;
        }
        return jok('数据获取成功', $dataList);
    }
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
            $this->thisModel->where($map)->delete();
            $this->thisModel->where('wemenu_pid', $this->pk_value)->delete();
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->delete();
            $this->thisModel->where('wemenu_pid', 'in', $list)->delete();
        }
        return jok('删除成功');
    }
    public function publish()
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
        $dataList = $this->thisModel->where('wemenu_pid', 0)->select()->toArray();
        $wechatMenu = [];
        foreach ($dataList as $parent) {
            $children = $this->thisModel->where('wemenu_pid', $parent['wemenu_id'])->select()->toArray();

            $menu = [
                'name' => urlencode($parent['wemenu_name']),
            ];
            if (count($children) > 0) {
                //has childen
                $subMenu = [];
                foreach ($children as $sub) {
                    $tempMenu = [];
                    $tempMenu['type'] = urlencode($sub['wemenu_type']);
                    $tempMenu['name'] = urlencode($sub['wemenu_name']);
                    switch ($sub['wemenu_type']) {
                        case 'view':
                            $tempMenu['url'] = $sub['wemenu_url'];
                            break;
                        case 'miniprogram':
                            $tempMenu['url'] = urlencode($sub['wemenu_url']);
                            $tempMenu['appid'] = urlencode($sub['wemenu_appid']);
                            $tempMenu['pagepath'] = urlencode($sub['wemenu_page']);
                            break;
                        default:
                            $tempMenu['key'] = urlencode($sub['wemenu_key']);
                    }
                    array_push($subMenu, $tempMenu);
                }
                $menu['sub_button'] = $subMenu;
            } else {
                $menu['type'] = $parent['wemenu_type'];
                switch ($parent['wemenu_type']) {
                    case 'view':
                        $menu['url'] = urlencode($parent['wemenu_url']);
                        break;
                    case 'miniprogram':
                        $menu['url'] = urlencode($parent['wemenu_url']);
                        $menu['appid'] = urlencode($parent['wemenu_appid']);
                        $menu['pagepath'] = urlencode($parent['wemenu_page']);
                        break;
                    default:
                        $menu['key'] = urlencode($parent['wemenu_key']);
                }
            }
            array_push($wechatMenu, $menu);
        }
        $access_token = $this->confModel->getAccessToken();
        $ret = httpPostFull('https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $access_token, urldecode(json_encode(['button' => $wechatMenu])));
        // print_r($ret);
        // print_r(urldecode(json_encode(['button' => $wechatMenu])));
        return jok('菜单已成功发布到微信');
    }
}