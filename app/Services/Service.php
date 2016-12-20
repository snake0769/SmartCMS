<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 14:12
 * Name:
 */
namespace App\Service;

use app\Components\Database\DataTablesHelper;
use app\Components\Database\ServiceHelper;
use App\Exceptions\BusinessException;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class Service
{
    use DataTablesHelper;

    /** 与服务关联的BaseModel类 */
    protected $baseModel;
    /** @var array 服务子类唯一实例存储数组 */
    protected static $instances = [];

    protected function __construct()
    {
        $this->baseModel = \Map::getClass($this->baseModel,self::class);
    }

    /**
     * 获取Service实例
     * @return static
     */
    public static function instance(){
        $class = get_called_class();
        if(!array_key_exists($class, self::$instances)){
            self::$instances[$class] = new $class;
        }

        return self::$instances[$class];
    }

    /**
     * 添加或更新，更新则要求传入的model必须有id字段，基于Service实现类的baseModel。
     * 如果操作成功，则返回相应的model实例,否则返回false
     * @param array $attributes
     * @return BaseModel|false
     */
    public function save(array $attributes){
        //根据attributes是否包含id值，执行插入或更新操作，并称“保存”操作
        /**@var $MODEL BaseModel*/
        $MODEL = $this->baseModel;
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
     * @param array|string $relations
     * @return User | Collection
     */
    public function one($id,$relations=null){
        if(empty($id))
            return $this->all($relations);
        else{
            $collection =  $this->all($relations,["id"=>$id]);
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
     * @param array $columns
     * @param array $orderBy
     * @return mixed
     */
    public function all($with = null,$where=null, $columns=['*'], $orderBy=['created_at'=>'desc']){
        $query = null;
        $query = $this->query($with,$where,$columns,$orderBy);
        return $query->get();
    }


    /**
     * 创建查询器
     * @param array|string $with
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return Builder
     */
    public function query($with=null, $where=null, $columns=['*'], $orderBy=['created_at'=>'desc']){
        $MODEL = $this->baseModel;
        /**@var $query Builder**/
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
            $query = $query ? $query->with($with) : $MODEL::with($with);
        }

        return $query;
    }


    /**
     * 软删除指定id记录，成功则返回操作记录id，否则返回false或错误码
     * @param string $id
     * @return bool|null
     * @throws BusinessException
     */
    public function delete($id){
        /**@var $MODEL BaseModel*/
        $MODEL = $this->baseModel;

        if(strpos($id,',')){
            $ids = explode(',',$id);
            $MODEL::whereIn('id',$ids)->delete();
        }else{
            $model = $MODEL::find($id);
            if(!empty($model) && !$model->trashed()){
                $rs = $model->delete();
                return $rs;
            }else{
                throw new BusinessException('指定id模型不存在');
            }
        }

    }

}