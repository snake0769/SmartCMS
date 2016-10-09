<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 17:10
 * Name:
 */
namespace App\Service;

use App\Exceptions\Error;
use App\Foundation\Facades\Map;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserService extends Service
{

    //protected static $models = ['user'=>User::class];

    protected static $baseModel = User::class;


    public static function save(array $attributes)
    {
        //如果属性未发生更改，则直接返回
        $USER = Map::model('User');
        if(isset($attributes['id']) && self::diff($attributes['id'],$attributes)){
            return new $USER($attributes);
        }
        if(isset($attributes['password'])){
            $attributes['password'] = bcrypt($attributes['password']);
        }


        $roles = array_pull($attributes,"roles");
        if(isset($attributes['username'])){
            $username = $attributes['username'];
            if($username !== ""){
                if(! self::isUserNameExists($username)){
                    $user =  parent::save($attributes);
                }else{
                    return Error::USER_DUMPLICATED_USERNAME;
                }
            }
        }else{
            $user =  parent::save($attributes);
        }

        //保存基本User数据后，保存Role数据
        if($user !== false && !empty($roles)){
            $roles = explode(',',$roles);
            $user->removeRoles();
            $user->assignRoles($roles);
        }

        return $user;
    }


    /**
     * 启用或停用用户
     * @param int $id
     * @param boolean $active
     */
    public static function activeUser($id,$active){
    }

    /**
     * 用户名是否存在
     * @param string $username
     * @return boolean
     */
    protected static function isUserNameExists($username){
        $USER = self::$baseModel;
        $rs = $USER::where('username','=',$username)->exists();
        return $rs;
    }

    /**
     * 根据传入的id，查询并对比数据是否相同；如果相同，返回true，否则返回false
     * @param int $id
     * @param array $attributes
     * @return bool
     */
    protected static function diff($id, $attributes){
        if( !empty($id)){
           $user =  self::get($id);

            //对比角色属性
            $roleIds = array_pull($attributes,'roles');
            if( !$user->hasRole($roleIds) ){
                return false;
            }

            //移除_token属性
            unset($attributes['_token']);
            $user = $user->toArray();
            foreach($attributes as $key=>$value){
                if( strval($user[$key]) !== strval($attributes[$key])){
                    return false;
                }
            }
        }

        return true;
    }
}