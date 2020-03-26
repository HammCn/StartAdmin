<?php

namespace app\model;

use think\Model;

/**
 * BaseModel 数据模型基类
 */
class BaseModel extends Model
{
    /**
     * 分页获取条数
     *
     * @var int 分页获取条数
     */
    public $per_page = 10;
    /**
     * 分页获取数据
     *
     * @param  array 筛选数组
     * @param  string 排序方式
     * @return void
     */
    public function getListByPage($maps, $order)
    {
        $resource = $this;
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
}
