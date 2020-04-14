<?php

declare(strict_types=1);

namespace app\admin;

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
    protected $module;
    protected $controller;
    protected $action;

    //模型
    protected $userModel;
    protected $accessModel;
    protected $authModel;
    protected $nodeModel;
    protected $groupModel;
    protected $confModel;
    protected $logModel;

    //主键key
    protected $pk = '';
    //表名称
    protected $table = '';
    //主键value
    protected $pk_value = '';
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
        $this->module = "admin";
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
     * 后台简单的身份判断
     *
     * @return void
     */
    protected function access()
    {
        $callback = "/admin";
        if (strtolower($this->controller) != "index") {
            $callback .= "/" . strtolower($this->controller);
        }
        if ($this->action != "index") {
            $callback .= "/" . $this->action;
        }
        $access_token = cookie('access_token');
        if (!$access_token) {
            return redirect('/admin/user/login/?callback=' . urlencode($callback));
        }
        View::assign("access_token", $access_token);
        $this->user = $this->userModel->getUserByAccessToken($access_token);
        if (!$this->user) {
            return redirect('/admin/user/login/?callback=' . urlencode($callback));
        }
        if ($this->user['user_status']  > 0) {
            return $this->error("抱歉，你的帐号已被禁用，暂时无法登录系统！");
        }
        cookie("access_token", $access_token);
        View::assign('userInfo', $this->user);
        $this->group = $this->groupModel->where('group_id', $this->user['user_group'])->find();
        if ($this->group) {
            if ($this->group['group_id'] != 1 && $this->group['group_status'] == 1) {
                return $this->error("抱歉，你所在的用户组已被禁用，暂时无法登录系统");
            } else {
                View::assign('menuList', $this->authModel->getAdminMenuListByUserId($this->group['group_id']));
                $node = $this->nodeModel->where(['node_module' => $this->module, 'node_controller' => strtolower($this->controller), 'node_action' => $this->action])->find();
                View::assign('node', $node);
            }
        } else {
            return $this->error("抱歉，没有查到你的用户组信息，暂时无法登录系统");
        }
    }
    protected function error($msg)
    {
        echo $msg;
        die;
    }
}
