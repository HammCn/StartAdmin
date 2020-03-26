<?php

namespace app\thirdlogin\controller;

use think\facade\View;
use app\thirdlogin\BaseController;

class Index extends BaseController
{
	public function index()
	{
		$callback = urldecode(input("callback"));
		cookie("callback", $callback);
		View::assign('callback', $callback);
		return View::fetch();
	}
}
