<?php

namespace app\model;

use think\facade\Db;

use app\model\BaseModel;

class Log extends BaseModel
{
    public function getListByPage($maps, $order)
    {
        $resource = $this->view('log', '*')->view('user', '*', 'user.user_id = log.log_user', 'left')->view('node', '*', 'node.node_id=log.log_node', 'left');
        foreach ($maps as $map) {
            switch (count($map)) {
                case 1:
                    $resource = $resource->where($map[0]);
                    break;
                case 2:
                    $resource = $resource->where($map[0], $map[1]);
                    break;
                case 3:
                    $resource = $resource->where($map[0], $map[1], $map[2]);
                    break;
                default:
            }
        }

        return $resource->order($order)->paginate($this->per_page);
    }
    public function getLogStatus()
    {
        $datalist = $this->field('count(log_id) as visitcount,log_node')->view('log', '*')->view('user', '*', 'user.user_id = log.log_user', 'left')->view('node', '*', 'node.node_id=log.log_node', 'left')->group("log_node")->order("visitcount desc")->select();
        return $datalist;
    }
    /**
     * 删除访问日志
     *
     * @return void
     */
    public function cleanLog()
    {
        Db::execute("truncate table " . config('database.connections.mysql.prefix') . "log");
        return true;
    }
}
