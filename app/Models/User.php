<?php

namespace App\Models;

use app\Components\Database\ModelHelper;
use app\Components\Database\QueryBuilder;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Activatable,SoftDeletes,QuickQuery;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','username', 'nickname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles(){
        return $this->belongsToMany(Role::class,"role_user",'user_id','role_id');
    }

    public function permissions(){
        $roles = $this->roles;
        $permissions = new Collection();
        foreach($roles as $role){
            $rolePms = $role->permissions->keyBy('name');
            $permissions = $permissions->keyBy('name');
            $intersectPms = $permissions->intersect($rolePms);
            if($intersectPms->count()>0){
                $items = $permissions->except($intersectPms);
            }else{
                $items = $rolePms;
            }
            $permissions = $permissions->merge($items);
        }

        return $permissions;
    }


    /**
     * 搜索用户列表
     * @param $params
     * @param $page
     * @param $perPage
     * @return array
     */
    public static function getList($params,$page,$perPage=20){
        $cols = ['id','username','nickname','created_at','active'];

        /** @var $query QueryBuilder*/
        $query = self::with(['roles'=>['id','name']]);
        if(!array_value_empty('start_date',$params)){
            $query->where('created_at','>=',$params['start_date']);
        }
        if(!array_value_empty('end_date',$params)){
            $query->where('created_at','<=',$params['end_date']);
        }

        //登录名、昵称作为关键字查找
        if(!array_value_empty('keyword',$params)){
            $query->where(function($query)use($params){
                $query->where('username','like','%'.$params['keyword'].'%')
                    ->orWhere('nickname','like','%'.$params['keyword'].'%');
            });

        }

        if(array_key_exists('order',$params)){
            $query->order($params['order']);
        }

        $total = $query->count();
        $items = $query->paginate($perPage,$cols,'page',$page)->items();

        return ['total'=>$total,'items'=>$items,'page'=>$page,'perPage'=>$perPage];
    }

    /**
     * 判断用户是否具有某个角色,传入Collection、Role Id字符串或数组
     * @param Collection|string $roles
     * @return bool
     */
    public function hasRole($roles,$field='id')
    {
        //传入Role （默认）Id字符串或数组
        if (is_string($roles) || is_int($roles) ){
            return $this->roles->contains($field, $roles);
        }
        //传入Role （默认）id数组或传入Collection
        elseif( (is_array($roles) && count($roles)> 0) || ($roles instanceof Collection && $roles->count()>0) ){
            foreach($roles as $role){
                $value = ($roles instanceof Collection && $roles->count()>0) ? $role->id:$role;

                if($roles->count()>1){
                    if( !$this->roles->contains($field, $value) )
                        return false;
                }else{
                    if( $this->roles->contains($field, $value) )
                        return true;
                }

            }
        }

        return false;

    }



    /**
     * 判断用户是否具有指定权限,可以指定多个权限
     * @param $permissions string|Permission[]|Collection Permission名称或id、Permission数组
     * @param $field string 表明传入的权限属性
     * @return bool
     * @throws BusinessException
     */
    public function hasPermission($permissions,$field='id')
    {
        $PERMISSION = \Map::getClass(Permission::class,self::class);

        //把各种类型$permissions参数,统一转换获得Permission数组
        if(!is_array($permissions)){
            if(is_string($permissions)){
                $permissions = $PERMISSION::with('roles')->where($field,$permissions)->get();
            }else{
                $permissions = [$permissions];
            }
        }elseif(is_array($permissions) && count($permissions) >0 && is_string($permissions[0])){
            $permissions = $PERMISSION::with('roles')->whereIn($field,$permissions)->get();
        }

        foreach ($permissions as $permission) {
            if (!$this->hasRole($permission->roles, $field))
                return false;
        }

        return true;
    }

    /**
     * 给用户分配角色，传入Role Id字符串或数组
     * @param string|array $roles
     * @return User
     */
    public function assignRoles($roles)
    {
        $ROLE = \Map::getClass(Role::class,self::class);
        //传入Role id字符串
        if(is_string($roles) || is_int($roles)){
            if( !$this->hasRole($roles) ){
                $this->roles()->save(
                    $ROLE::whereIn('id',$roles)->firstOrFail()
                );
            }
        }
        //传入Role id数组
        elseif(is_array($roles) && count($roles) > 0){
            //重新分配角色
            foreach($roles as $roleId){
                if( !$this->hasRole($roleId) ){
                    $this->roles()->save(
                        $ROLE::where('id',$roleId)->firstOrFail()
                    );
                }
            }
        }

        return $this;
    }

    /**
     * 移除用户角色,可传入role id字符串或数组，默认移除所有角色
     * @param string null $roles
     * @return User
     */
    public function removeRoles($roles=null){
        if(empty($roles)){
            $this->roles()->detach();
        }else{
            if(is_string($roles) || is_int($roles)){
                $roles = [$roles];
            }
            $this->roles()->detach($roles);
        }
    }

    /**
     * 重设用户密码
     * @param $password string 原生不被加密的新密码
     * @return bool|int
     */
    public function resetPassword($password){
        if(empty($password)){
           return false;
        }
        $this->password = bcrypt($password);
        return $this->update();
    }

    /**
     * 是否超级用户
     * @return bool
     */
    public function isSuperUser(){
        return boolval($this->is_super);
    }

}
