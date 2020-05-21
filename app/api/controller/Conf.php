<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Conf as ConfModel;

class Conf extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "conf_id" => "=", //相同筛选
            "conf_key" => "like", //相似筛选
            "conf_value" => "like", //相似筛选
            "conf_desc" => "like", //相似筛选
        ];
        $this->insertFields = [
            "conf_key", "conf_value", "conf_desc"
        ];
        $this->updateFields = [
            "conf_key", "conf_value", "conf_desc"
        ];
        $this->insertRequire = [
            'conf_key' => "配置名称必须填写"
        ];
        $this->updateRequire = [
            'conf_key' => "配置名称必须填写"
        ];
        $this->model = new ConfModel();
    }
    /**
     * 读取基本配置
     *
     * @return void
     */
    public function getBaseConfig()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $datalist = $this->model->where('conf_key', 'in', 'app_name')->order($this->pk . " asc")->select();
        return jok('', $datalist);
    }
    /**
     * 更新基础配置
     *
     * @return void
     */
    public function updateBaseConfig()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        foreach (input("post.") as $k => $v) {
            $map["conf_key"] = $k;
            $item = $this->model->where($map)->find();
            if (empty($item)) {
                continue;
            }
            $this->model->where("conf_key", $k)->update(["conf_value" => $v]);
        }
        return jok("配置修改成功");
    }
}
