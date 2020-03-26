<?php

namespace app\thirdlogin\controller;

use think\App;
use app\thirdlogin\BaseController;

class Weibo extends BaseController
{
	public $appid = "";
	public $appkey = "";
	public $oauthUrl = "https://api.weibo.com/oauth2/authorize";
	public $accesstokenUrl = "https://api.weibo.com/oauth2/access_token";
	public $loginUrl = "";
	public function __construct(App $app)
	{
		parent::__construct($app);
		$this->loginUrl = $this->oauthUrl . '?client_id=' . $this->appid . "&response_type=code&scope=all&display=default&state=1&redirect_uri=" . urlencode(getFullDomain() . "/thirdlogin/weibo/callback");
	}
	public function index()
	{
		$callback = urldecode(input("callback"));
		cookie("callback", $callback);
		return $this->login();
		die;
	}
	public function publish()
	{
		if (!input("text")) {
			echo 'empty text!';
			die;
		}
		$keyword = input("text");
		$keyword .= "https://hamm.cn";
		$access_token = cookie("weibo_access_token");
		$uid = cookie("weibo_uid");
		if (empty($access_token)) {
			$this->login();
		} else {
			$url = "https://api.weibo.com/2/statuses/share.json";
			$data = "access_token=" . $access_token . "&status=" . urlencode($keyword);
			//echo $url;die;
			$retObj = json_decode(httpPostFull($url, $data));
			print_r($retObj);
			echo "<br>";
			echo "<a href='/thirdlogin/weibo/publish/?text=Hello World!'>publish weibo</a> <a href='/thirdlogin/weibo/login/'>relogin</a>";
		}
	}
	public function login()
	{
		cookie("weibo_access_token", "");
		return redirect($this->loginUrl);
	}
	public function callback()
	{
		if (!input("?get.code")) {
			echo 'Code missing';
		} else {
			$code = input("get.code");
			$url = $this->accesstokenUrl;
			parse_str("grant_type=authorization_code&client_id=" . $this->appid . "&client_secret=" . $this->appkey . "&code=" . $code . "&redirect_uri=" . urlencode($this->callbackUrl), $data);
			$data = "grant_type=authorization_code&client_id=" . $this->appid . "&client_secret=" . $this->appkey . "&code=" . $code . "&redirect_uri=" . urlencode($this->callbackUrl);
			$retObj = json_decode(httpPost($url, $data));
			echo "Code:" . $code . "<br>";
			//print_r($retObj);
			if (!property_exists($retObj, "access_token")) {
				echo "Code error , please <a href='/thirdlogin/weibo/login'>relogin</a>";
			} else {
				$access_token = $retObj->access_token;
				// cookie("weibo_access_token",$access_token);
				$uid = $retObj->uid;
				$url = "https://api.weibo.com/2/users/show.json?access_token=" . $access_token . "&uid=" . $uid;
				//echo $url;die;
				$retObj = json_decode(httpGetFull($url));
				// print_r($retObj);
				if (!property_exists($retObj, "id")) {
					$this->login();
				} else {
					// echo "Name:".$retObj->name."<br>";
					// echo "description:".$retObj->description."<br>";
					// echo "<img src='".$retObj->profile_image_url."' width='100px'/><br>";
					// echo "Following:".$retObj->friends_count."<br>";
					// echo "Fans:".$retObj->followers_count."<br>";
					// echo "Weibo:".$retObj->statuses_count."<br>";
					// echo "URL:".$retObj->profile_url."<br>";
					// echo "weihao:".$retObj->weihao."<br>";
					// echo "<br>";
					print_r($retObj);
				}
			}
		}
	}
}
