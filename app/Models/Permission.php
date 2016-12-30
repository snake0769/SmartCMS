<?php

namespace App\Models;


use Illuminate\Support\Facades\Cache;

class Permission extends BaseModel
{

    public $incrementing = true;

    public function roles()
    {
        return $this->belongsToMany(Role::class,"permission_role",'permission_id','role_id');
    }


    /**
     * 查找root权限id
     * @param $id
     * @param $permission
     */
    private static function isSetPermission($id,$permissions){

        foreach($permissions as $pms){
            if(isset($permissions[$id])){
                return true;
            }elseif(isset($pms['childPermissions'])){
                return self::isSetPermission($id,$pms['childPermissions']);
            }

        }
    }

    /**
     * 获取所有权限,权限经过格式整理,以层级方式呈现
     * @return Permission[]
     */
    public static function getAllWithLayer(){
        return \Cache::rememberForever('admin.permissionForMenu', function(){
            $PERMISSION = \Map::getClass(Permission::class);

            /** @var Permission $PERMISSION $permissions */
            $permissions = $PERMISSION::orderBy('layer','asc')->orderBy('pid','asc')->get();

            $sortPermissions = [];

            //permissions数据集本身已经按照layer顺序排列
            foreach($permissions as $permission){

                switch($permission->layer){
                    case 1:
                        $lv1Pms = &$sortPermissions[$permission->id];
                        $lv1Pms['permission'] = $permission->toArray();
                        break;
                    case 2:
                        $lv1Pms = &$sortPermissions[$permission->pid];
                        $lv2ChildPms = &$lv1Pms['childPermissions'];
                        $lv2ChildPms[$permission->id] = ['permission'=>$permission->toArray()];
                        break;
                    case 3:
                        //查找rootId
                        $rootId = -1;
                        foreach($sortPermissions as $k=>$pms){
                            if(isset($pms['childPermissions'])){
                                $rs = self::isSetPermission($permission->pid,$pms['childPermissions']);
                                if($rs){
                                    $rootId =  $k;
                                    break;
                                }

                            }
                        }

                        $lv1Pms = &$sortPermissions[$rootId];
                        $lv2ChildPms = &$lv1Pms['childPermissions'];
                        $lv3ChildPms = &$lv2ChildPms[$permission->pid]['childPermissions'];
                        $lv3ChildPms[$permission->id] = ['permission'=>$permission->toArray()];
                        break;
                }
            }

            return $sortPermissions;
        });

    }


    /**
     * 获取所有权限
     * @return Permission[]
     */
    public static function getAll()
    {
        return \Cache::rememberForever('admin.permission', function(){
            /** @var Permission $PERMISSION */
            $PERMISSION = \Map::getClass(Permission::class);
            return $PERMISSION::all();
        });
    }

}
