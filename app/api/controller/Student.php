<?php

namespace app\api\controller;

use think\App;
use app\api\BaseController;
use app\model\Student as StudentModel;

class Student extends BaseController
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
            "student_id" => "=",
            "student_name"=>"like","student_age"=>"like",
        ];
        $this->insertFields = [
            //允许添加的字段列表
            "student_name","student_age",
        ];
        $this->updateFields = [
            //允许更新的字段列表
            "student_name","student_age",
        ];
        $this->insertRequire = [
            //添加时必须填写的字段
            // "字段名称"=>"该字段不能为空"
            "student_name"=>"姓名必须填写",
        ];
        $this->updateRequire = [
            //修改时必须填写的字段
            // "字段名称"=>"该字段不能为空"
            "student_name"=>"姓名必须填写",
        ];
        $this->thisModel = new StudentModel();
    }
}
