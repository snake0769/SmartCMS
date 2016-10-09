<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 14:12
 * Name:
 */
namespace App\Service;

use App\Exceptions\Error;
use App\Exceptions\ServiceException;
use App\Foundation\DatabaseMapper;
use App\Foundation\ModelReflects;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Service
{

    /** 与服务关联的BaseModel类 */
    protected static $baseModel = null;


    /**
     * 设置Model类数组
     * @param $models
     */
    /*public function setModels($models){
        if(is_array($models) && count($models) > 0){
            foreach($models as $class){
                if( !class_exists($class) ){
                    throw new ServiceException(self::ERR_001);
                }
            }

            static::$models = $models;
        }
    }*/


    /**
     * 获取Model类数组
     * @return array
     */
    /*public function getModels(){
        return static::$models;
    }*/



    /**
     * 设置BaseModel类
     * @param $model
     */
    public function setBaseModel($model){
        if(!class_exists($model)){
            throw new ServiceException(Error::RUNTIME_CLASS_NOT_FOUND);
        }
        static::$baseModel = $model;
    }


    /**
     * 获取BaseModel类
     * @return array
     */
    public function getBaseModel(){
        return static::$baseModel;
    }

    /**
     * 添加或更新，则要求传入的model必须有id字段，基于Service实现类的baseModel。
     * 如果操作成功，则返回相应的model实例,否则返回false
     * @param array $attributes
     * @return mixed
     */
    public static function save(array $attributes){
        //根据attributes是否包含id值，执行插入或更新操作，并称“保存”操作
        $model = new static::$baseModel($attributes);
        $MODEL = static::$baseModel;
        $id = empty($attributes['id']) ? -1:$attributes['id'];
        $model = $MODEL::updateOrCreate(["id"=>$id],$attributes);

        if($model === false)
            return false;
        else
            return $model;
    }

    /**
     * 获取指定id的信息，基于Service实现类的baseModel
     * @param int null $id
     * @param int null $active
     * @param array|string $relations
     * @return User | Collection
     */
    public static function get($id=null,$relations=null){
        if(empty($id))
            return self::select($relations);
        else{
            $collection =  self::select($relations,["id"=>$id]);
            if($collection !== null){
                return $collection[0];
            }else{
                return null;
            }
        }
    }


    /**
     * 查询，基于Service实现类的baseModel
     * @param array $with
     * @param array $where
     * @param string $limit
     * @param string $columns
     * @param array $orderBy
     * @param int $pageNo
     * @param int $nums
     * @return mixed
     */
    public static function select($with=null,$where=null,$columns=['*'],$orderBy=['id'=>'desc'],$pageNo=null,$nums=15){
        $MODEL = static::$baseModel;
        $query = null;

        $query = self::buildQuery($with,$where,$columns,$orderBy);

        //page
        if($pageNo !== null && $nums > 0){
            if($pageNo > 0 && is_int($pageNo))
                $query = $query ? $query->offset(($pageNo-1) * $nums)->limit($nums) : $MODEL::offset(($pageNo-1) * $nums)->limit($nums);
        }

        return $query->get();
    }


    /**
     * 创建查询器
     * @param string $model
     * @param array $with
     * @param array $where
     * @param string $limit
     * @param string $columns
     * @param array $orderBy
     * @return Builder
     */
    public static function buildQuery($with=null, $where=null, $columns=['*'], $orderBy=['id'=>'desc']){
        $MODEL = static::$baseModel;
        $query = null;

        //where
        if($where !== null && is_array($where)){
            foreach($where as $col=>$val){
                if(is_array($val))
                    $query = $query ? $query->where($val['column'], $val['operation'], $val['value']) : $MODEL::where($val['column'], $val['operation'], $val['value']);
                else
                    $query = $query ? $query->where($col,'=',$val):$MODEL::where($col,'=',$val);
            }
        }

        //columns
        if(is_array($columns) && in_array('*',$columns)){
            $query = $query ? $query->select($columns) : $MODEL::select($columns);
        }

        //orderBy
        if(is_array($orderBy) && count($orderBy)>0){
            foreach($orderBy as $column=> $direction){
                $query = $query ? $query->orderBy($column,$direction) : $MODEL::orderBy($column,$direction);
            }
        }

        //with relations
        if($with !== null){
            if(is_array($with)){
                foreach($with as $withItem){
                    $query = $query ? $query->with($withItem) : $MODEL::with($withItem);
                }
            }
            else
                $query = $query ? $query->with($with) : $MODEL::with($with);

        }

        return $query;
    }


    /**
     * 软删除指定id记录，成功则返回操作记录id，否则返回false或错误码
     * @param string $id
     * @return int|boolean
     */
    public static function delete($id){
        $MODEL = static::$baseModel;

        if(strpos($id,',')){
            $ids = explode(',',$id);
            $MODEL::whereIn('id',$ids)->delete();
        }else{
            $model = $MODEL::find($id);
            if(!empty($model) && !$model->trashed()){
                $rs = $model->delete();
                return $rs;
            }else{
                return Error::USER_NOT_EXISTED;
            }
        }

    }

}