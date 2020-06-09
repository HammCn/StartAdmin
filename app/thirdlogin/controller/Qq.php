<?php

namespace app\thirdlogin\controller;

use app\thirdlogin\BaseController;

class Qq extends BaseController
{
	public $appid = "";
	public $appkey = "";
	public $oauthUrl = "https://graph.qq.com/oauth2.0/authorize";
	public $accesstokenUrl = "https://graph.qq.com/oauth2.0/token";
	public $openidUrl = "https://graph.qq.com/oauth2.0/me";
	public $userinfoUrl = "https://graph.qq.com/user/get_user_info";
	public function index()
	{
		$callback = urldecode(input("callback"));
		cookie("callback", $callback);
		return $this->login();
		die;
	}
	public function login()
	{
		return redirect($this->oauthUrl . '?client_id=' . $this->appid . "&response_type=code&scope=get_user_info&display=pc&state=1&redirect_uri=" . urlencode(getFullDomain() . "/thirdlogin/qq/callback"));
	}
	public function callback()
	{
		if (!input("code")) {
			echo 'Code missing';
		} else {
			$code = input("code");
			$url = $this->accesstokenUrl . "?grant_type=authorization_code&client_id=" . $this->appid . "&client_secret=" . $this->appkey . "&code=" . $code . "&redirect_uri=" . urlencode(getFullDomain() . "/thirdlogin/qq/callback");
			$ret = curlHelper($url);
			$ret = $ret['body'];
			if (strpos($ret, "callback") !== false) {
				return $this->login();
				die;
			} else {
				$retArr = array();
				parse_str($ret, $retArr);
				$access_token = $retArr['access_token'];
				//$refresh_token = $retArr ['refresh_token'];
				//$expires_in = $retArr ['expires_in'];
				$url = $this->openidUrl . "?access_token=" . $access_token;
				$ret = curlHelper($url);
				$ret = $ret['body'];
				if (strpos($ret, "callback") === false) {
					return $this->login();
					die;
				} else {
					$ret = str_replace("callback(", "", $ret);
					$ret = str_replace(");", "", $ret);
					$retObj = json_decode($ret);

					$openid = $retObj->openid;
					$url = $this->userinfoUrl . "?access_token=" . $access_token . "&oauth_consumer_key=" . $this->appid . "&openid=" . $openid;
					$retObj = json_decode(curlHelper($url)['body']);
					//print_r($retObj);die;
					if ($retObj->ret !== 0) {
						return $this->login();
						die;
					} else {
						print_r($retObj);
					}
				}
			}
		}
	}
}
