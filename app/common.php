<?php

/**
 * 输出正常JSON
 *
 * @param string 提示信息
 * @param array  输出数据
 * @return json
 */
function jok($msg = 'success', $data = null)
{
    if ($data) {
        return json(["code" => 200, "msg" => $msg, 'data' => $data]);
    } else {
        return json(["code" => 200, "msg" => $msg]);
    }
}
/**
 * 输出错误JSON
 *
 * @param string 错误信息
 * @param int 错误代码
 * @return json
 */
function jerr($msg = 'error', $code = 500)
{
    return json(["code" => $code, "msg" => $msg]);
}
/**
 * 密码+盐 加密
 *
 * @param string 明文密码
 * @param string 盐
 * @return string
 */
function encodePassword($password, $salt)
{
    return sha1($password . $salt . $password . $salt);
}
/**
 * 密码校验 6-16
 *
 * @param string 明文密码
 * @return boolean 是否校验通过
 */
function isPassword($password)
{
    return preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?!.*\s).{6,}/', $password);
}
/**
 * 获取随机字符
 *
 * @param int $len
 * @return void
 */
function getRandString($len)
{
    $string = '';
    $randString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    for ($i = 0; $i < $len; $i++) {
        $string .= $randString[rand(0, strlen($randString) - 1)];
    }
    return $string;
}
/**
 * 获取随机字母
 *
 * @param int 长度
 * @return string
 */
function getRandChar($len)
{
    $string = '';
    $randString = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    for ($i = 0; $i < $len; $i++) {
        $string .= $randString[rand(0, strlen($randString) - 1)];
    }
    return $string;
}
/**
 * 遍历类的方法
 *
 * @param string 指定的类名称
 * @return array
 */
function getClassMethods($class)
{
    $array_result = [];
    $array_all = get_class_methods($class);
    if ($parent_class = get_parent_class($class)) {
        $array_parent = get_class_methods($parent_class);
        $array_result = array_diff($array_all, $array_parent);
    } else {
        $array_result = $array_all;
    }
    return $array_result;
}
/**
 * 获取包含协议和端口的域名
 *
 * @return string
 */
function getFullDomain()
{
    return ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['REQUEST_SCHEME']) . "://" . $_SERVER['HTTP_HOST'];
}
/**
 * 获取客户端IP
 *
 * @return string
 */
function get_client_ip()
{
    foreach (array(
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'REMOTE_ADDR'
    ) as $key) {
        if (array_key_exists($key, $_SERVER)) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                if ((bool) filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_IPV4
                    // FILTER_FLAG_NO_PRIV_RANGE |
                    // FILTER_FLAG_NO_RES_RANGE
                )) {
                    return $ip;
                }
            }
        }
    }
    return null;
}

/**
 * 取文本中间
 *
 * @param string 原始字符串
 * @param string 左边字符串
 * @param string 右边字符串
 * @return string
 */
function getSubstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    $right = strpos($str, $rightStr, $left);
    if ($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right - $left - strlen($leftStr));
}
/**
 * 获取操作系统
 *
 * @return string
 */
function  getOs()
{
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return 'Other';
    }
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'windows nt')) {
        $platform = 'Windows';
    } elseif (strpos($agent, 'macintosh')) {
        $platform = 'MacOS';
    } elseif (strpos($agent, 'ipod')) {
        $platform = 'iPod';
    } elseif (strpos($agent, 'ipad')) {
        $platform = 'iPad';
    } elseif (strpos($agent, 'iphone')) {
        $platform = 'iPhone';
    } elseif (strpos($agent, 'android')) {
        $platform = 'Android';
    } elseif (strpos($agent, 'unix')) {
        $platform = 'Unix';
    } elseif (strpos($agent, 'linux')) {
        $platform = 'Linux';
    } else {
        $platform = 'Other';
    }
    return $platform;
}
/**
 * 获取浏览器
 *
 * @return void
 */
function  getBrowser()
{
    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        return 'Unknown';
    }
    $agent = $_SERVER["HTTP_USER_AGENT"];
    if (strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) //ie11判断
    {
        return "IE";
    } else if (strpos($agent, 'Firefox') !== false) {
        return "Firefox";
    } else if (strpos($agent, 'Chrome') !== false) {
        return "Chrome";
    } else if (strpos($agent, 'Opera') !== false) {
        return 'Opera';
    } else if ((strpos($agent, 'Chrome') == false) && strpos($agent, 'Safari') !== false) {
        return 'Safari';
    } else {
        return 'Unknown';
    }
}
/**
 * 是否手机请求
 *
 * @return boolean
 */
function is_mobile_request()
{
    $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';
    $mobile_browser = '0';
    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|uc|qq|wechat|micro|messenger|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))
        $mobile_browser++;
    if ((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') !== false))
        $mobile_browser++;
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        $mobile_browser++;
    if (isset($_SERVER['HTTP_PROFILE']))
        $mobile_browser++;
    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
        'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
        'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
        'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
        'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
        'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
        'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
        'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
        'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-'
    );
    if (in_array($mobile_ua, $mobile_agents))
        $mobile_browser++;
    if (strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)
        $mobile_browser++;
    // Pre-final check to reset everything if the user is on Windows  
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
        $mobile_browser = 0;
    // But WP7 is also Windows, with a slightly different characteristic  
    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)
        $mobile_browser++;
    if ($mobile_browser > 0)
        return true;
    else
        return false;
}
/**
 * 是否是整数
 *
 * @param string 输入内容
 * @return boolean
 */
function isInteger($input)
{
    return (ctype_digit(strval($input)));
}
/**
 * 获取一个key摘要
 *
 * @param string 原始key
 * @return string
 */
function getTicket($key)
{
    return sha1($key . (env('SYSTEM_SALT') ?? 'StartAdmin') . $key);
}
/**
 * CURL POST
 *
 * @param string 请求地址
 * @param array POST数据
 * @param array 请求头
 * @param string COOKIES
 * @param boolean 是否返回header
 * @param boolean 是否后台请求
 * @param integer 超时时间
 * @param array 使用代理
 * @return mixed 
 */
function httpPostFull($url, $data = null, $header = [], $cookies = "", $returnHeader = false, $isBackGround = false, $timeout = 0, $proxy = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    curl_setopt($ch, CURLOPT_POST, 1);
    if ($timeout) {
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,$timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    }
    if (!empty($proxy)) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
        curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "taras:taras-ss5");
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $isBackGround ? 0 : 1);
    curl_setopt($ch, CURLOPT_HEADER, $returnHeader ? 1 : 0);
    $output = curl_exec($ch);
    if ($timeout) {
        if ($output === FALSE) {
            if (curl_errno($ch) == CURLE_OPERATION_TIMEOUTED) {
                $output = 'TIMEOUT';
            } else {
                $output = 'ERROR';
            }
        }
    }
    curl_close($ch);
    return $output;
}

/**
 * CURL GET
 *
 * @param string 请求地址
 * @param array 请求头
 * @param string COOKIES
 * @param boolean 是否返回header
 * @param boolean 是否后台请求
 * @param integer 超时时间
 * @param array 使用代理
 * @return mixed 
 */
function httpGetFull($url, $header = [], $cookies = "", $returnHeader = false, $isBackGround = false, $timeout = 0, $proxy = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_COOKIE, $cookies);
    if ($timeout) {
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    }
    if (!empty($proxy)) {
        curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']);
        curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, "taras:taras-ss5");
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $isBackGround ? 0 : 1);
    curl_setopt($ch, CURLOPT_HEADER, $returnHeader ? 1 : 0);
    $output = curl_exec($ch);
    if ($timeout) {
        if ($output === FALSE) {
            if (in_array(curl_errno($ch), [28])) {
                $output = 'TIMEOUT';
            } else {
                $output = 'ERROR';
            }
        }
    }
    curl_close($ch);
    return $output;
}
/**
 * 请求并返回HEADER
 *
 * @param string 请求地址
 * @return mixed
 */
function httpGetWithHeader($url)
{
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
    //打印获得的数据
    return $output;
}

/**
 * 后台请求URL
 *
 * @param string 请求地址
 * @return mixed
 */
function httpBackground($url)
{
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);   //只需要设置一个秒的数量就可以  
    //执行并获取HTML文档内容
    curl_exec($ch);
    //释放curl句柄
    curl_close($ch);
}
