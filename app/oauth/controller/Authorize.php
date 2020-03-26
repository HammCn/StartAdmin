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
		$user = null;
		//登录用户
		if ($this->request->isPost()) {
			//校验参数
			if (!input("post.account")) {
				echo ('Input your account please!');
				die;
			}
			if (!input("post.password")) {
				echo ('Input your password please!');
				die;
			}
			$account = input("post.account");
			$password = input("post.password");
			//登录验证
			$user = $userModel->login($account, $password);
			if (empty($user)) {
				echo ('Account or password error!');
				die;
			}
			//生成一个临时code
			$code = sha1(time()) . rand(100000, 999999);
			//将之前的code全部设置失效
			$codeModel->where('code_user', $user['user_id'])->update([
				'code_status' => 1,
			]);
			//保存新的code
			$codeModel->insert([
				'code_user' => $user['user_id'],
				'code_code' => $code,
				'code_createtime' => time(),
				'code_updatetime' => time()
			]);
			//重定向回第三方页面
			return redirect(urldecode($redirect) . "?code=" . $code);
			die;
		}
		View::assign('app', $app);
		return View::fetch();
	}
}
