<?php

declare(strict_types=1);

namespace app\api;

use think\App;
use think\facade\View;

use app\model\User as UserModel;
use app\model\Access as AccessModel;
use app\model\Auth as AuthModel;
use app\model\Node as NodeModel;
use app\model\Group as GroupModel;
use app\model\Conf as ConfModel;
use app\model\Log as LogModel;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    protected $thisModel = null;
    //搜索字段
    protected $selectList = '*';
    protected $selectDetail = '*';
    //筛选字段
    protected $searchFilter = [];
    //更新字段
    protected $updateFields = [];
    //更新时的必须字段
    protected $updateRequire = [];
    //添加字段
    protected $insertFields = [];
    //添加时的必须字段
    protected $insertRequire = [];
    //页码
    protected $page = 1;

    //主键key
    protected $pk = '';
    //表名称
    protected $table = '';
    //主键value
    protected $pk_value = 0;


    //模型
    protected $userModel;
    protected $accessModel;
    protected $authModel;
    protected $nodeModel;
    protected $groupModel;
    protected $confModel;
    protected $logModel;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;
    protected $plat = 'all';
    protected $version = 0;


    protected $module;
    protected $controller;
    protected $action;
    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->module = "api";
        $this->controller = $this->request->controller() ? $this->request->controller() : "Index";
        $this->action = strtolower($this->request->action()) ? strtolower($this->request->action()) : "index";
        View::assign('controller', strtolower($this->controller));
        View::assign('action', strtolower($this->action));

        $this->table = strtolower($this->controller);
        $this->pk = $this->table . "_id";
        $this->pk_value = input($this->pk);

        $this->userModel = new UserModel();
        $this->accessModel = new AccessModel();
        $this->authModel = new AuthModel();
        $this->nodeModel = new NodeModel();
        $this->groupModel = new GroupModel();
        $this->confModel = new ConfModel();
        $this->logModel = new LogModel();

        $configs = $this->confModel->select()->toArray();
        $c = [];
        foreach ($configs as $config) {
            $c[$config['conf_key']] = $config['conf_value'];
        }
        config($c, 'startadmin');
    }
    /**
     * 检测版本号
     *
     * @return void
     */
    protected function checkVersion()
    {
        if (!input("plat")) {
            return jerr("plat missing", 500);
        }
        $this->plat = input('plat');
        if (!input("version")) {
            return jerr("version missing", 500);
        }
        $this->version = input('version');
    }
    /**
     * 检测登录态
     *
     * @return void
     */
    protected function checkLogin()
    {
        //获取access_token
        if (input("?access_token")) {
            $access_token = input("access_token");
            $this->user = $this->userModel->getUserByAccessToken($access_token);
            if (!$this->user) {
                return jerr("登录过期，请重新登录", 400);
            } else {
                if ($this->user['user_status'] == 1) {
                    return jerr("你的账户被禁用，登录失败", 401);
                } else {
                    return null;
                }
            }
        } else {
            return jerr("AccessToken为必要参数", 400);
        }
    }
    /**
     * 检测授权
     *
     * @return void
     */
    protected function checkAccess()
    {
        if (!$this->user['user_group']) {
            return jerr("用户没有所属的用户组", 403);
        }
        $where = [
            "group_id" => $this->user['user_group'],
        ];
        $this->group = $this->groupModel->where($where)->find();
        if (!$this->group) {
            return jerr("用户组信息查询失败", 403);
        }
        if ($this->group['group_status'] == 1) {
            return jerr("你所在的用户组[" . $this->group['group_name'] . "]被禁用", 403);
        }
        $this->node = $this->nodeModel->where(['node_module' => $this->module, 'node_controller' => strtolower($this->controller), 'node_action' => $this->action])->find();
        if (!$this->node) {
            return jerr("请勿访问没有声明的API节点！", 503);
        }
        if ($this->node['node_status'] == 1) {
            return jerr("你访问的节点[" . $this->node['node_title'] . "]被禁用", 503);
        }

        $log = [
            "log_user" => $this->user['user_id'],
            "log_node" => $this->node['node_id'],
            "log_createtime" => time(),
            "log_ip" => get_client_ip(),
            "log_browser" => getBrowser(),
            "log_os" => getOs(),
            "log_updatetime" => time(),
            "log_gets" => urlencode(json_encode(input("get."))),
            "log_posts" => urlencode(json_encode(input("post."))),
            "log_cookies" => urlencode(json_encode($_COOKIE))
        ];
        $this->logModel->insert($log);
        if ($this->group['group_id'] > 1) {
            //其他用户
            $where = [
                "auth_group" => $this->user["user_group"],
                "auth_node" => $this->node['node_id'],
            ];
            $auth = $this->authModel->auth($this->group['group_id'], $this->node['node_id']);
            if (!$auth) {
                return jerr("你没有权限访问[" . $this->node['node_title'] . "]这个接口", 403);
            }
        }
        return null;
    }
    public function add()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        foreach ($this->insertRequire as $k => $v) {
            if (!input($k)) {
                return jerr($v);
            }
        }
        $data = [];
        foreach (input() as $k => $v) {
            if (in_array($k, $this->insertFields)) {
                $data[$k] = $v;
            }
        }
        $data[$this->table . "_updatetime"] = time();
        $data[$this->table . "_createtime"] = time();
        $this->thisModel->insert($data);
        return jok('添加成功');
    }
    public function update()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "参数必须填写");
        }
        $map[$this->pk] = $this->pk_value;
        $item = $this->thisModel->where($map)->find();
        if (empty($item)) {
            return jerr("数据查询失败");
        }
        foreach ($this->updateRequire as $k => $v) {
            if (!input($k)) {
                return jerr($v);
            }
        }
        $data = [];
        foreach (input() as $k => $v) {
            if (in_array($k, $this->updateFields)) {
                $data[$k] = $v;
            }
        }
        $data[$this->table . "_updatetime"] = time();
        $this->thisModel->where($this->pk, $this->pk_value)->update($data);
        return jok('修改成功');
    }
    /**
     * 禁用用户
     *
     * @return void
     */
    public function disable()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "参数必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->thisModel->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            $this->thisModel->where($map)->update([
                $this->table . "_status" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->update([
                $this->table . "_status" => 1,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("禁用成功");
    }

    /**
     * 启用
     *
     * @return void
     */
    public function enable()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "参数必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->thisModel->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            $this->thisModel->where($map)->update([
                $this->table . "_status" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->update([
                $this->table . "_status" => 0,
                $this->table . "_updatetime" => time(),
            ]);
        }
        return jok("启用成功");
    }

    /**
     * 通用删除接口
     *
     * @return void
     */
    public function delete()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "必须填写");
        }
        if (isInteger($this->pk_value)) {
            $map = [$this->pk => $this->pk_value];
            $item = $this->thisModel->where($map)->find();
            if (empty($item)) {
                return jerr("数据查询失败");
            }
            $this->thisModel->where($map)->delete();
        } else {
            $list = explode(',', $this->pk_value);
            $this->thisModel->where($this->pk, 'in', $list)->delete();
        }
        return jok('删除成功');
    }
    /**
     * 获取列表
     *
     * @return void
     */
    public function getList()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        $map = [];
        $filter = input();
        foreach ($filter as $k => $v) {
            if ($k == 'filter') {
                $k = input('filter');
                $v = input('keyword');
            }
            if ($v === '' || $v === null) {
                continue;
            }
            if (array_key_exists($k, $this->searchFilter)) {
                switch ($this->searchFilter[$k]) {
                    case "like":
                        array_push($map, [$k, 'like', "%" . $v . "%"]);
                        break;
                    case "=":
                        array_push($map, [$k, '=', $v]);
                        break;
                    default:
                }
            }
        }
        $order = strtolower($this->controller) . "_id desc";
        if (input('order')) {
            $order = urldecode(input('order'));
        }
        $dataList = $this->thisModel->getListByPage($map, $order);
        return jok('数据获取成功', $dataList);
    }
    public function detail()
    {
        $error = $this->checkVersion();
        if ($error) {
            return $error;
        }
        $error = $this->checkLogin();
        if ($error) {
            return $error;
        }
        $error = $this->checkAccess();
        if ($error) {
            return $error;
        }
        if (!input($this->pk)) {
            return jerr($this->pk . "必须填写");
        }
        $map = [
            $this->pk => input($this->pk),
        ];
        $item = $this->thisModel->field($this->selectDetail)->where($map)->find();
        if (empty($item)) {
            return jerr("没有查询到数据");
        }
        return jok('数据加载成功', $item);
    }
    /**
     * 验证图形验证码
     *
     * @return void
     */
    protected function validateImgCode()
    {
        if (!input('code')) {
            return jerr("Code missing");
        }
        if (!input('token')) {
            return jerr("Token missing");
        }
        if (!input('time')) {
            return jerr("time missing");
        }
        $code = input('code');
        $time = input('time');
        $token = sha1(sha1($code . (env('CAPTCHA_SALT') ?? 'StartAdmin') . $time) . $time);
        if ($token != input('token')) {
            return jerr("验证码错误，请重新输入");
        }
        if (time() > $time + 60) {
            return jerr("验证码超时，请重新输入");
        }
        return null;
    }
    public function __call($method, $args)
    {
        return jerr("API接口不存在", 404);
    }
}
