<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/15
 * Time: 15:14
 */

namespace app\Components\Database;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

trait ModelHelper
{

    /**
     * 便捷地解析字段数组
     * @param $columns array 字段数组，支持一唯和二维数组。示例：['user'=>'id,name','role'=>['id','name']]
     * @return array 示例：['user.id','user.name','role.id','role.name']
     */
    public static function resolveColumns(array $columns){
        $result = '';
        if(is_array($columns)){
            foreach($columns as $table => $cols){
                $cols = is_string($cols) ? explode(',',$cols) : $cols ;
                if(!is_array($cols)){
                    throw new \InvalidArgumentException('$columns参数类型或格式错误');
                }

                array_map(function($col)use(&$result,$table){
                    $result[] = $table.'.'.$col;
                },$cols);
            }
        }

        return $result;
    }

    /**
     * 返回带有表名的完整字段名
     * @param $column
     * @return string
     */
    public static function column($column){
        return static::table().'.'.$column;
    }

    /**
     * 判断数组或集合内是否包含指定字段值的元素
     * @param $models array|Collection model数组或集合,其结构必须为key-value
     * @param $field string 指定字段
     * @param $value mixed 指定字段值
     * @return boolean
     */
    public static function contains($models,$field,$value){
        if(is_array($models) || $models instanceof Collection){
            foreach($models as $model){
                if($model instanceof Model){
                    $model = $model->toArray();
                }

                if(array_key_exists($field,$model) && $model[$field] == $value){
                    return true;
                }
            }
        }else{
            return false;
        }
    }

}