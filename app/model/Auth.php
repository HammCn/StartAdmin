<?php

namespace app\model;

use think\facade\Db;
use app\model\Node as NodeModel;

use app\model\BaseModel;

class Auth extends BaseModel
{
    /**
     * 判断用户组是否获得某节点的授权
     *
     * @param int 用户组ID
     * @param int 节点ID
     * @return void
     */
    public function auth($auth_group, $auth_node)
    {
        $auth = $this->where([
            "auth_group" => $auth_group,
            "auth_node" => $auth_node
        ])->find();
        if ($auth) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据用户组 获取管理后台菜单
     *
     * @param int 用户组ID
     * @return void
     */
    public function getAdminMenuListByUserId($group_id)
    {
        $NodeModel = new NodeModel();
        if ($group_id == 1) {
            $list =  $NodeModel
                ->where([
                    "node_pid"   =>  0,
                    "node_show"   =>  1
                ])
                ->order("node_order desc,node_id asc")
                ->select();
            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['subList'] = $this->getSubAdminListByPid($list[$i]['node_id'], $group_id);
            }
            return $list;
        } else {
            $join = [
                ["auth auth", "node.node_id=auth.auth_node"]
            ];
            $list = $NodeModel
                ->alias("node")
                ->join($join)
                ->where([
                    "node_pid"   =>  0,
                    "node_show"   =>  1,
                    "auth_group"    =>$group_id
                ])
                ->order("node_order desc,node_id asc")
                ->select();
            for ($i = 0; $i < count($list); $i++) {
                $list[$i]['subList'] = $this->getSubAdminListByPid($list[$i]['node_id'], $group_id);
            }
            return $list;
        }
    }

    /**
     * 根据节点ID 获取用户组的子菜单
     *
     * @param int 节点ID
     * @param int 用户组ID
     * @return void
     */
    public function getSubAdminListByPid($node_id, $group_id = 1)
    {
        $NodeModel = new NodeModel();
        if ($group_id == 1) {
            return $NodeModel
                ->where([
                    // "node_module"   =>  "admin",
                    "node_pid"   =>  $node_id,
                    "node_show"   =>  1
                ])
                ->order("node_order desc,node_id asc")
                ->select();
        } else {
            $join = [
                ["auth auth", "node.node_id=auth.auth_node"]
            ];
            return $NodeModel
                ->alias("node")
                ->join($join)
                ->where([
                    // "node_module"   =>  "admin",
                    "node_pid"   =>  $node_id,
                    "node_show"   =>  1
                ])
                ->order("node_order desc,node_id asc")
                ->select();
        }
    }
    /**
     * 删除授权记录
     *
     * @return void
     */
    public function cleanAuth()
    {
        Db::execute("truncate table " . config('database.connections.mysql.prefix') . "auth");
        return true;
    }
}
