<?php

namespace app\oauth\controller;

use app\oauth\BaseController;
use app\model\User as UserModel;

class User extends BaseController
{
	public function getUserInfo()
	{
		if (!input("access_token")) {
			return jerr("AccessToken missing!");
		}
		$access_token = input("access_token");
		$UserModel = new UserModel();
		$user = $UserModel->getUserByAccessToken($access_token);
		if (!$user) {
			return jerr("AccessToken error!");
		}
		return jok("success", $user);
	}
}
