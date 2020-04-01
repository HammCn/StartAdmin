<?php

namespace app\oauth\controller;

use think\facade\View;
use app\oauth\BaseController;
use app\model\App as AppModel;
use app\model\User as UserModel;
use app\model\Code as CodeModel;

class Authorize extends BaseController
{
	public function index()
	{
		$appModel = new AppModel();
		$userModel = new UserModel();
		$codeModel = new CodeModel();
		//传入参数校验开始
		if (!input("client_id")) {
			echo "client_id missing";
			die();
		}
		if (!input("redirect")) {
			echo "redirect missing";
			die();
		}
		//传入参数校验结束
		$client_id = input("client_id");
		$redirect = input("redirect");
		//校验APP信息
		$app = $appModel->where("app_id", $client_id)->find();
		if (empty($app)) {
			echo "App not found";
			die();
		}
		if ($app['app_status'] == 1) {
			echo "App Not Allowed!";
			die();
		}
		View::assign('app', $app);
		View::assign('redirect', $redirect);
		return View::fetch();
	}
}
