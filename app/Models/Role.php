<?php

namespace App\Models;


use app\Components\Database\QueryBuilder;
use App\Foundation\Facades\Map;
use Illuminate\Database\Eloquent\Collection;

class Role extends BaseModel
{

    protected $fillable = ["id","name","label","description"];


    public function users(){
        return $this->belongsToMany(User::class,"role_user",'role_id','user_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,"permission_role",'role_id','permission_id');
    }

    /**
     * 给用户分配权限，传入permission Id字符串或数组
     * @param string|array $permissions
     * @return Role
     */
    public function assignPermissions($permissions)
    {
        if(is_string($permissions) || is_int($permissions)){
            $permissions = explode(',',$permissions);
        }
        //分配权限
        if(is_array($permissions)){
            $this->permissions()->attach($permissions);
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


    /**
     * 搜索角色列表
     * @param $params
     * @param $page
     * @param $perPage
     * @return array
     */
    public static function getList($params,$page,$perPage=20){
        $cols = ['id','name','label','description','created_at'];

        /** @var $query QueryBuilder*/
        $query = self::with(['users'=>['id','username','nickname']]);
        if(!array_value_empty('start_date',$params)){
            $query->where('created_at','>=',$params['start_date']);
        }
        if(!array_value_empty('end_date',$params)){
            $query->where('created_at','<=',$params['end_date']);
        }

        //登录名、昵称作为关键字查找
        if(!array_value_empty('keyword',$params)){
            $query->where('name','like','%'.$params['keyword'].'%');
        }

        if(array_key_exists('order',$params)){
            $query->order($params['order']);
        }

        $total = $query->count();
        $items = $query->paginate($perPage,$cols,'page',$page)->items();

        return ['total'=>$total,'items'=>$items,'page'=>$page,'perPage'=>$perPage];
    }

    /**
     * 判断角色是否有用户归属
     * @param $id string|array 单个角色id传入string,多个角色id传入数组
     * @return array|false 如果没有归属,返回false,否则返回归属信息数组
     */
    public static function belongToUser($id){
        if(!is_array($id)){
            $id = [$id];
        }

        $query = \DB::table('role_user');
        $result = $query->whereIn('role_id',$id)->get();

        return empty($result) ? false : $result;
    }

}
