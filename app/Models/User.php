<?php

namespace App\Models;

use App\Foundation\Facades\Map;
use App\Foundation\ModelReflects;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class User extends Authenticatable
{

    use Activatable,SoftDeletes;
    use ModelReflects;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'nickname', 'email', 'password',
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
        return $this->belongsToMany(Role::class,"role_user");
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
     * 判断用户是否具有某个角色,传入Collection、Role Id字符串或数组
     * @param Collection|string $roles
     * @param boolean $perfectMatch 是否需要完全匹配
     * @return bool
     */
    public function hasRole($roles, $perfect=true, $field='id')
    {
        //传入Role （默认）Id字符串或数组
        if (is_string($roles) || is_int($roles) ){
            return $this->roles->contains($field, $roles);
        }
        //传入Role （默认）id数组或传入Collection
        elseif( (is_array($roles) && count($roles)> 0) || ($roles instanceof Collection && $roles->count()>0) ){
            foreach($roles as $role){
                $value = ($roles instanceof Collection && $roles->count()>0) ? $role->$field:$role;

                if($perfect  && $roles->count()>1){
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
     * 判断用户是否具有某个角色,传入Collection、Permission Id字符串或数组
     * @param $permissions
     * @param string $field 指明传入的值对应id或name字段
     * @return bool
     */
    public function hasPermission($permissions, $perfect=true, $field='id')
    {
        $PERMISSION = Map::model('Permission');
        //传入Permission (默认)Id字符串或数组
        if(is_string($permissions) || is_int($permissions)){
            $permission = $PERMISSION::where($field,$permissions)->first();
            if(!empty($permission)){
                return $this->hasRole($permission->roles,false,$field);
            }
            else
                return false;
        }
        //传入Permission (默认)Ids数组或Collection
        elseif( (is_array($permissions) && count($permissions)> 0) || ($permissions instanceof Collection && $permissions->count()>0)){
            foreach($permissions as $permission){
                $value = ($permissions instanceof Collection && $permissions->count()>0) ? $permission->$field:$permission;
                $permission = $PERMISSION::where($field,$value)->first();

                if(!empty($permission)){
                    if($perfect && $permissions->count()>1){
                        if( !$this->hasRole($permission->roles,false,$field) )
                            return false;
                    }else{
                        if( $this->hasRole($permission->roles,false,$field) )
                            return true;
                    }
                }

            }

        }

        return false;
    }

    /**
     * 给用户分配角色，传入Role Id字符串或数组
     * @param string|array $roles
     * @return User
     */
    public function assignRoles($roles)
    {
        $ROLE = Map::model('Role');
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

}
