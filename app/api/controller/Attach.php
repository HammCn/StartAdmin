<?php

namespace app\api\controller;

use think\App;
use think\facade\Filesystem;
use think\exception\ValidateException;
use app\api\BaseController;
use app\model\Attach as AttachModel;

class Attach extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);
        //筛选字段
        $this->searchFilter = [
            "attach_id" => "=", //相同筛选
            "attach_key" => "like", //相似筛选
            "attach_value" => "like", //相似筛选
            "attach_desc" => "like", //相似筛选
            "attach_readonly" => "=", //相似筛选
        ];
        $this->model = new AttachModel();
    }
    /**
     * 上传图片
     *
     * @return void
     */
    public function uploadImage()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        try {
            $file = request()->file('file');
            try {
                validate(['file' => 'filesize:' . config("startadmin.upload_max_image") . '|fileExt:' . config("startadmin.upload_image_type")])
                    ->check(['file' => $file]);
                $saveName = Filesystem::putFile('image', $file);
                $attach_data = array(
                    'attach_path' => $saveName,
                    'attach_type' => $file->extension(),
                    'attach_size' => $file->getSize(),
                    'attach_user' => $this->user['user_id']
                );
                $attach_id = $this->insertRow($attach_data);
                $attach_data = $this->getRowByPk($attach_id);
                if (input("?extend")) {
                    $attach_data['extend'] = input("extend");
                }
                return jok('上传成功！', $attach_data);
            } catch (ValidateException $e) {
                return jerr($e->getMessage());
            }
        } catch (\Exception $error) {
            return jerr('上传文件失败，请检查你的文件！');
        }
    }
    /**
     * 上传文件
     *
     * @return void
     */
    public function uploadFile()
    {
        $error = $this->access();
        if ($error) {
            return $error;
        }
        try {
            $file = request()->file('file');
            try {
                validate(['file' => 'filesize:' . config("startadmin.upload_max_file") . '|fileExt:' . config("startadmin.upload_file_type")])
                    ->check(['file' => $file]);
                $saveName = Filesystem::putFile('normal', $file);
                $attach_data = array(
                    'attach_path' => $saveName,
                    'attach_type' => $file->extension(),
                    'attach_size' => $file->getSize(),
                    'attach_user' => $this->user['user_id']
                );
                $attach_id = $this->insertRow($attach_data);
                $attach_data = $this->getRowByPk($attach_id);
                if (input("?extend")) {
                    $attach_data['extend'] = input("extend");
                }
                return jok('上传成功！', $attach_data);
            } catch (ValidateException $e) {
                return jerr($e);
            }
        } catch (\Exception $error) {
            return jerr('上传文件失败，请检查你的文件！');
        }
    }
}
