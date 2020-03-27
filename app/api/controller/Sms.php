<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Sms as SmsModel;

class Sms extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "sms_id" => "=", //相同筛选
            "sms_key" => "like", //相似筛选
            "sms_value" => "like", //相似筛选
            "sms_desc" => "like", //相似筛选
            "sms_readonly" => "=", //相似筛选
        ];
        $this->thisModel = new SmsModel();
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
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->delete();
        }
        return jok('删除短信验证码成功');
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
        $map = [];
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
        $dataList = $this->thisModel->getListByPage($map, $order);
        return jok('短信验证码列表获取成功', $dataList);
    }
    public function __call($method, $args)
    {
        return $this->index();
    }
}
