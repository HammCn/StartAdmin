<?php

namespace app\api\controller;

use think\App;
use think\facade\Db;
use app\api\BaseController;
use app\model\Validate as ValidateModel;

class System extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
    }
    /**
     * 代码生成主逻辑
     *
     * @return void
     */
    public function build()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $table = strtolower(input("controller"));
        if (empty($table)) {
            jerr("请输入控制器名称");
        }
        $prefix = config('database.connections.mysql.prefix');
        $database = config('database.connections.mysql.database');
        if (empty(input("nodeTitle"))) {
            jerr("请输入节点名称");
        }
        $files = scandir(__DIR__);
        if (in_array(ucfirst($table) . ".php", $files)) {
            jerr("控制器已存在，生成失败");
        }
        $tableExist =  Db::query('SHOW TABLES LIKE "' . $prefix . $table . '"');
        if (count($tableExist) > 0) {
            jerr("生成失败，该表已存在");
        }
        $fieldBlackList = [$table . '_id', $table . '_status', $table . '_createtime', $table . '_updatetime'];
        $fieldList = input('fieldList');
        foreach ($fieldBlackList as $item) {
            unset($fieldList[$item]);
        }

        //创建表开始
        $sql = "CREATE TABLE `" . $prefix . $table . "` (`" . $table . "_id` INT(9) NOT NULL AUTO_INCREMENT ";
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            if (empty($field['name'])) {
                continue;
            } else {
                $field['name'] = preg_replace("/[^a-zA-Z]/", "", $field['name']);
            }
            if (empty($field['type'])) {
                $field['type'] = 'varchar';
            } else {
                $field['type'] = preg_replace("/[^a-zA-Z]/", "", $field['type']);
            }
            if (empty($field['length'])) {
                $field['length'] = '255';
            } else {
                $field['length'] = preg_replace("/[^0-9,\,]/", "", $field['length']);
            }
            if (empty($field['desc'])) {
                $field['desc'] = $field['name'];
            }
            switch (strtolower($field['type'])) {
                case 'text':
                case 'longtext':
                    $sql .= " ,`" . $table . "_" . strtolower($field['name']) . "` " . $field['type'] . " NULL DEFAULT NULL";
                    break;
                case 'int':
                case 'bigint':
                case 'tinyint':
                case 'double':
                case 'float':
                case 'decimal':
                    if (isset($field['default']) && is_numeric($field['default'])) {
                        //整数 已设置默认值
                        $sql .= " ,`" . $table . "_" . strtolower($field['name']) . "` " . $field['type'] . "(" . $field['length'] . ")" . " NOT NULL DEFAULT '" . $field['default'] . "'";
                    } else {
                        $sql .= " ,`" . $table . "_" . strtolower($field['name']) . "` " . $field['type'] . "(" . $field['length'] . ")" . " NOT NULL DEFAULT 0";
                    }
                    break;
                default:
                    if (isset($field['default']) && strtolower($field['default']) != "null" && strtolower($field['default']) != '') {
                        //整数 已设置默认值
                        $sql .= " ,`" . $table . "_" . strtolower($field['name']) . "` " . $field['type'] . "(" . $field['length'] . ")" . " NOT NULL DEFAULT '" . $field['default'] . "'";
                    } else {
                        $sql .= " ,`" . $table . "_" . strtolower($field['name']) . "` " . $field['type'] . "(" . $field['length'] . ")" . " NOT NULL DEFAULT ''";
                    }
                    break;
            }
            if (isset($field['desc'])) {
                $sql .= " comment '" . $field['desc'] . "'";
            }
        }
        $sql .= " ,`" . $table . "_status` INT(9) NOT NULL DEFAULT '0' comment '状态'";
        $sql .= " ,`" . $table . "_createtime` INT(9) NOT NULL DEFAULT '0' comment '创建时间'";
        $sql .= " ,`" . $table . "_updatetime` INT(9) NOT NULL DEFAULT '0' comment '修改时间'";
        $sql .= " , PRIMARY KEY (`" . $table . "_id`)) ENGINE = InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='" . input("nodeTitle") . "表'";
        //创建表结束
        Db::execute($sql);
        //添加节点开始
        $data = [
            "node_title" => "获取" . input("nodeTitle") . "详情接口",
            "node_controller" => $table,
            "node_action" => "detail",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => "添加" . input("nodeTitle") . "接口",
            "node_controller" => $table,
            "node_action" => "add",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => "修改" . input("nodeTitle") . "接口",
            "node_controller" => $table,
            "node_action" => "update",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => "删除" . input("nodeTitle") . "接口",
            "node_controller" => $table,
            "node_action" => "delete",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => "禁用" . input("nodeTitle") . "接口",
            "node_controller" => $table,
            "node_action" => "disable",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => "启用" . input("nodeTitle") . "接口",
            "node_controller" => $table,
            "node_action" => "enable",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => "获取" . input("nodeTitle") . "列表接口",
            "node_controller" => $table,
            "node_action" => "getList",
            "node_pid" => 4,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        $data = [
            "node_title" => input("nodeTitle") . "管理",
            "node_module" => "admin",
            "node_controller" => $table,
            "node_action" => "index",
            "node_pid" => 0,
            "node_show" => 1,
            "node_createtime" => time(),
            "node_updatetime" => time(),
        ];
        $this->nodeModel->insert($data);
        //添加节点完成

        //开始生成控制器
        $url = __DIR__ . "/../../builder/templateController.php";
        $file = file_get_contents($url);
        $file = str_replace("TMP_controller", ucfirst($table), $file);
        $file = str_replace("tmp_controller", $table, $file);

        $tmp_Fields = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            $tmp_Fields .= '"' . $table . "_" . $field['name'] . '",';
        }
        $file = str_replace("//tmp_Fields", $tmp_Fields, $file);

        $tmp_require = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            if ($field['require']) {
                $tmp_require .= '"' . $table . "_" . $field['name'] . '"=>"' . ($field['desc'] ?? $field['name']) . '必须填写",';
            }
        }
        $file = str_replace("//tmp_require", $tmp_require, $file);

        $tmp_searchFilter = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            $tmp_searchFilter .= '"' . $table . "_" . $field['name'] . '"=>"like",';
        }
        $file = str_replace("//tmp_searchFilter", $tmp_searchFilter, $file);

        $myfile = fopen(__DIR__ . "/" . ucfirst($table) . ".php", "w") or die("Unable to open file!");
        fwrite($myfile, $file);
        fclose($myfile);
        //生成控制器完毕

        //开始生成数据模型
        $url = __DIR__ . "/../../builder/templateModel.php";
        $file = file_get_contents($url);
        $file = str_replace("TMP_controller", ucfirst($table), $file);
        $file = str_replace("tmp_controller", $table, $file);
        $myfile = fopen(__DIR__ . "/../../model/" . ucfirst($table) . ".php", "w") or die("Unable to open file!");
        fwrite($myfile, $file);
        fclose($myfile);
        //生成数据模型结束

        if (!is_dir(__DIR__ . "/../../admin/view/" . $table)) {
            mkdir(__DIR__ . "/../../admin/view/" . $table, 0777, true);
        }

        //开始生成update
        $url = __DIR__ . "/../../builder/templateView.html";
        $file = file_get_contents($url);
        $file = str_replace("tmp_title", input("nodeTitle"), $file);
        $file = str_replace("tmp_controller", $table, $file);

        $tmp_add = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            $tmp_add .= '
            <el-form-item prop="' . $table . "_" . $field['name'] . '" label="' . $field['desc'] . '" :label-width="formLabelWidth">
                <el-input size="medium" autocomplete="off" v-model="formAdd.' . $table . "_" . $field['name'] . '"></el-input>
            </el-form-item>';
        }
        $file = str_replace("tmp_add", $tmp_add, $file);

        $tmp_update = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            $tmp_update .= '
            <el-form-item prop="' . $table . "_" . $field['name'] . '" label="' . $field['desc'] . '" :label-width="formLabelWidth">
                <el-input size="medium" autocomplete="off" v-model="formEdit.' . $table . "_" . $field['name'] . '"></el-input>
            </el-form-item>';
        }
        $file = str_replace("tmp_update", $tmp_update, $file);

        $tmp_table = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            $tmp_table .= '
            <el-table-column prop="' . $table . "_" . $field['name'] . '" label="' . $field['desc'] . '">
            </el-table-column>';
        }
        $file = str_replace("tmp_table", $tmp_table, $file);

        $tmp_select = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            $tmp_select .= '
            <el-option value="' . $table . "_" . $field['name'] . '" label="' . $field['desc'] . '">
            </el-option>';
        }
        $file = str_replace("tmp_select", $tmp_select, $file);

        $tmp_rules = '';
        foreach ($fieldList as $field) {
            $field['desc'] = empty($field['desc']) ? $field['name'] : $field['desc'];;
            if ($field['require']) {
                $tmp_rules .= $table . "_" . $field['name'] . ": [ { required: true, message: '请输入" . ($field['desc'] ?? $field['name']) . "', trigger: 'blur' }],";
            }
        }
        $file = str_replace("tmp_rules", $tmp_rules, $file);



        $myfile = fopen(__DIR__ . "/../../admin/view/" . $table . "/index.html", "w") or die("Unable to open file!");
        fwrite($myfile, $file);
        fclose($myfile);
        jok('生成成功');
    }
    public function getCaptcha()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        $validateModel = new ValidateModel();
        $imgData = $validateModel->getImg();
        $code = strtoupper($validateModel->getCode());
        $token = sha1($code .  time()) . rand(100000, 999999);
        cache($token, $code, 60);
        jok('验证码生成成功', [
            'img' => $imgData,
            'token' => $token
        ]);
    }
}
