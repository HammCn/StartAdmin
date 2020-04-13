<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\TMP_controller as TMP_controllerModel;

class TMP_controller extends BaseController
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
            "tmp_controller_id" => "=",
            //tmp_searchFilter
        ];
        $this->insertFields = [
            //允许添加的字段列表
            //tmp_Fields
        ];
        $this->updateFields = [
            //允许更新的字段列表
            //tmp_Fields
        ];
        $this->insertRequire = [
            //添加时必须填写的字段
            // "字段名称"=>"该字段不能为空"
            //tmp_require
        ];
        $this->updateRequire = [
            //修改时必须填写的字段
            // "字段名称"=>"该字段不能为空"
            //tmp_require
        ];
        $this->model = new TMP_controllerModel();
    }
}
