<?php

namespace app\oauth\controller;

use app\oauth\BaseController;
use app\model\App as AppModel;
use app\model\User as UserModel;
use app\model\Code as CodeModel;
use app\model\Access as AccessModel;

class Accesstoken extends BaseController
{
	public function index()
	{
		$appModel = new AppModel();
		$userModel = new UserModel();
		$codeModel = new CodeModel();
		$AccessModel = new AccessModel();
		//校验参数开始
		if (!input("client_id")) {
			return  jerr("client_id missing!");
		}
		if (!input("client_secret")) {
			return  jerr("client_secret missing!");
		}
		if (!input("code")) {
			return  jerr("Code missing!");
		}
		//校验参数结束
		$client_id = input("client_id");
		$client_secret = input("client_secret");
		$code = input("code");
		//获取APP信息
		$app = $appModel->where("app_id", $client_id)->where("app_secret", $client_secret)->find();
		if (empty($app)) {
			return  jerr("client_id or client_secret error!");
		}
		$code = $codeModel->where([
			'code_code' => $code,
			'code_status' => 0
		])->order('code_createtime desc')->find();
		if (!$code) {
			return  jerr("code error!");
		}
		if (time() - $code['code_createtime'] > 300) {
			//code超过5分钟
			return  jerr("code out of time!");
		}
		$access = $AccessModel->createAccess($code['code_user'], $client_id);
		if (!$access) {
			return  jerr("Access error!");
		}
		//设置所有code失效
		$codeModel->where('code_user', $code['code_user'])->update([
			'code_status' => 1,
		]);
		return jok("success", $access['access_token']);
	}
}
