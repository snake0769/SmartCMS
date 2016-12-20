<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 17:10
 * Name:
 */
namespace App\Service;

use App\Foundation\Facades\Map;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpParser\Error;

class RoleService extends Service
{

    /**@var User**/
    protected $baseModel;

    protected function __construct()
    {
        $this->baseModel = Role::class;
        parent::__construct();
    }


    public function save(array $attributes)
    {
        //如果属性未发生更改，则直接返回
        $ROLE = $this->baseModel;
        if(isset($attributes['id']) && self::diff($attributes['id'],$attributes)){
            return new $ROLE($attributes);
        }

        $permissions = array_pull($attributes,'permissions');
        $role =  parent::save($attributes);

        //保存基本Role数据后，保存Permissions数据
        if($role !== false && !empty($permissions)){
            $permissions = explode(",",$permissions);
            $role->removePermissions();
            $role->assignPermissions($permissions);
        }

        return $role;
    }


    /**
     * 根据传入的id，查询并对比数据是否相同；如果相同，返回true，否则返回false
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    protected static function diff($id, $attributes){
        if( !empty($id)){
            $role =  self::get($id);

            //对比权限属性
            $permissionIds = array_pull($attributes,'permissions');
            if( !$role->hasPermission($permissionIds) ){
                return false;
            }

            //移除_token属性
            unset($attributes['_token']);
            $role = $role->toArray();
            foreach($attributes as $key=>$value){
                if( strval($role[$key]) !== strval($attributes[$key])){
                    return false;
                }
            }
        }

        return true;
    }


}