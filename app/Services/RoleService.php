<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 17:10
 * Name:
 */
namespace App\Service;

use app\Components\Database\QueryBuilder;
use app\Components\Util\StringHelper;
use App\Exceptions\BusinessException;
use App\Foundation\Facades\Map;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use PhpParser\Error;

class RoleService extends Service
{

    /**@var Role**/
    protected $baseModel;

    protected function __construct()
    {
        $this->baseModel = \Map::getClass(Role::class,self::class);
        parent::__construct();
    }

    /**
     * 创建角色
     * @param array $attributes
     * @return \App\Models\BaseModel|false
     * @throws \Exception
     */
    public function create(array $attributes)
    {
        try {
            \DB::beginTransaction();

            $permissions = array_pull($attributes, 'permissions');
            $role = parent::create($attributes);
            if ($role !== false && !empty($permissions)) {
                /** @var Role $role */
                $role->assignPermissions($permissions);
            }
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
        return true;
    }


    /**
     * 更新角色
     * @param array $attributes
     * @return \App\Models\BaseModel|false
     * @throws \Exception
     */
    public function update(array $attributes)
    {
        try {
            \DB::beginTransaction();

            $permissions = array_pull($attributes, 'permissions');

            $role = parent::update($attributes);
            if ($role !== false && !empty($permissions)) {
                /** @var Role $role */
                $role = new Role($attributes);
                $role->removePermissions();
                $role->assignPermissions($permissions);
            }
            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }
        return true;
    }


    /**
     * 粘合用户名称
     * @param $users array
     * @return string
     */
    public function glueUserName(array $users){
        $result = '';
        if(count($users) == 0 ){
            return $result;
        }else{
            foreach($users as $user){
                $result .= $user['nickname'].',';
            }
        }
        $result = substr($result,0,-1);

        return $result;
    }

    /**
     * 查询角色列表
     * @param $params
     * @return array
     */
    public function getList($params){
        /**@var $ROLE Role**/
        $ROLE = $this->baseModel;
        $page = self::convertPage($params);
        $data =  $ROLE::getList($params,$page['page'],$page['perPage']);

        //整理输出数据，将相关Model实例转为数组
        $items = (new Collection($data['items']))->toArray();
        foreach($items as &$item){
            $item['users'] = $this->glueUserName($item['users']);
        }
        $data['items'] = $items;

        return $data;
    }


    /**
     * 获取角色详细信息，包含权限列表
     * @param $id
     * @return array
     */
    public function get($id){
        $role = parent::one($id,"permissions");
        $permissions = PermissionService::instance()->getAllWithLayer();
        $data['role'] = $role->toArray();
        $data['permissions'] = $permissions;
        return $data;
    }

    /**
     * 软删除指定id记录，成功则返回操作记录id，否则返回false或错误码
     * @param array|string $id
     * @return int
     * @throws BusinessException
     */
    public function delete($id)
    {
        $ROLE = $this->baseModel;
        $ids = [];
        if (is_array($id)) {
            $ids = $id;
        } else {
            $ids = strpos($id, ',') !== false ? explode(',', $id) : [$id];
        }

        if($ROLE::belongToUser($ids)){
            throw new BusinessException('请先解除用户角色,再进行角色删除');
        }

        return parent::delete($ids);
    }


}