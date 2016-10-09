<?php

namespace App\Models;


use App\Foundation\Facades\Map;
use Illuminate\Database\Eloquent\Collection;

class Role extends BaseModel
{

    protected $fillable = ["name","label"];


    /**
     * 从Role数组提取label字段并组成一个字符串返回
     * @param $roles
     * @return string
     */
    public static function drawLabel($roles){
        $labels = "";
        foreach($roles as $role){
            $labels .= trim($role->name.",");
        }
        if($labels !== ""){
            $labels = substr($labels,0,strlen($labels)-1);
        }
        return $labels;
    }

    public function users(){
        return $this->belongsToMany(User::class,"role_user");
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,"permission_role");
    }

    /**
     * 给用户分配权限，传入permission Id字符串或数组
     * @param string|array $permissions
     * @return Role
     */
    public function assignPermissions($permissions)
    {
        $PERMISSION = Map::model('Permission');

        //传入permission id字符串
        if(is_string($permissions) || is_int($permissions)){
            if( !$this->hasPermission($permissions) ){
                $this->permissions()->save(
                    $PERMISSION::whereIn('id',$permissions)->firstOrFail()
                );
            }
        }
        //传入permission id数组
        elseif(is_array($permissions) && count($permissions) > 0){
            //重新分配角色
            foreach($permissions as $permissionId){
                if( !$this->hasPermission($permissionId) ){
                    $this->permissions()->save(
                        $PERMISSION::where('id',$permissionId)->firstOrFail()
                    );
                }
            }
        }
    }

    /**
     * 移除用户权限,可传入permission id字符串或数组，默认移除所有角色
     * @param string null $permissions
     */
    public function removePermissions($permissions=null){
        if(empty($permissions)){
            $this->permissions()->detach();
        }else{
            if(is_string($permissions) || is_int($permissions)){
                $permissions = [$permissions];
            }
            $this->permissions()->detach($permissions);
        }
    }


    /**
     * 判断用户是否具有某权限，传入Collection、Permission Id字符串或数组
     * @param $permission
     * @param string $field 指明传入的值对应id或name字段
     * @return boolean
     */
    public function hasPermission($permission,$field='id')
    {
        //传入Permission (默认)Ids字符串
        if (is_string($permission) || is_int($permission)) {
            return $this->permissions->contains($field, $permission);
        }
        //传入Permission (默认)Ids数组
        elseif( is_array($permission) && count($permission)> 0){
            foreach($permission as $permissionId){
                if( !$this->permissions->contains($field, $permissionId) )
                    return false;
            }
            return true;
        }
        //传入Collection
        elseif($permission instanceof Collection && $permission->count()>0){
            return $permission->intersect($this->permissions)->count() == $permission->count();
        }

        return false;
    }



}
