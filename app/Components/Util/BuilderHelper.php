<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/20
 * Time: 10:33
 */

namespace app\Components\Util;



trait BuilderHelper
{

    /**
     * 根据传入的order数组，进行排序
     * @param $order string|array 排序数组，如果格式非法则使用默认排序，默认以created_at示例：[{column:'id',dir:'asc'}]
     * @param $defCol string 默认排序字段
     * @param $defDir string 默认排序方向
     * @return self
     */
    public function order($order,$defCol='created_at',$defDir='desc'){
        if(is_string($order)){
            $order = json_decode($order);
        }

        if(is_array($order) && count($order) > 0){
            foreach($order as $item){
                $this->orderBy($item->column,$item->dir);
            }
        }else{
            $this->orderBy($defCol,$defDir);
        }

        return $this;
    }
}