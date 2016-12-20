<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 17:10
 * Name:
 */
namespace App\Service;

use app\Components\Database\DataTablesHelper;
use App\Exceptions\BusinessException;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserService extends Service
{

    /**@var User**/
    protected $baseModel;
    /**@var Role**/
    protected $roleModel;

    protected function __construct()
    {
        $this->baseModel = User::class;
        $this->roleModel = \Map::getClass(Role::class,self::class);

        parent::__construct();
    }

    /**
     * 添加或更新用户
     * @param array $attributes 如果属性中包含id，则为更新，否则添加
     * @return boolean
     * @throws BusinessException|\Exception
     */
    public function save(array $attributes)
    {
        try {
            \DB::beginTransaction();

            //如果属性未发生更改，则直接返回
            if (isset($attributes['password'])) {
                $attributes['password'] = bcrypt($attributes['password']);
            }

            $roles = array_pull($attributes, "roles");
            if (isset($attributes['username'])) {
                $username = $attributes['username'];
                if ($username !== "") {
                    if (!$this->isUserNameExists($username)) {
                        $user = parent::save($attributes);
                    } else {
                        throw new BusinessException('用户名已存在');
                    }
                }
            } else {
                $user = parent::save($attributes);
            }

            //保存基本User数据后，保存Role数据
            /**@var $user User* */
            if ($user !== false && !empty($roles)) {
                $roles = explode(',', $roles);
                $user->removeRoles();
                $user->assignRoles($roles);
            }

            \DB::commit();
        } catch (\Exception $ex) {
            \DB::rollback();
            throw $ex;
        }

        return true;
    }


    /**
     * 启用或停用用户
     * @param int $id
     * @param boolean $active
     */
    public function activeUser($id,$active){
    }

    /**
     * 用户名是否存在
     * @param string $username
     * @return boolean
     */
    protected function isUserNameExists($username){
        $USER = $this->baseModel;
        $rs = $USER::where('username','=',$username)->exists();
        return $rs;
    }


    /**
     * 获取用户的角色标签组。如果有多个角色标签，则组成一个以','作为分隔符的字符串返回
     * @param $user User
     * @return string
     */
    public function getRolesLabel($user){
        /*$roles = $user->roles;
        $labels = "";
        foreach($roles as $role){
            $labels .= trim($role->name.",");
        }
        if($labels !== ""){
            $labels = substr($labels,0,strlen($labels)-1);
        }
        return $labels;*/
    }

    /**
     * 粘合角色名称
     * @param $roles array
     * @return string
     */
    public function glueRolesName(array $roles){
        $result = '';
        if(count($roles) == 0 ){
            return $result;
        }else{
            foreach($roles as $role){
                $result .= $role['name'].',';
            }
        }
        $result = substr($result,0,-1);

        return $result;
    }

    /**
     * 查询用户列表
     * @param $params
     * @return array
     */
    public function getList($params){
        /**@var $USER User**/
        $USER = $this->baseModel;
        $page = self::convertPage($params);
        $data =  $USER::getList($params,$page['page'],$page['perPage']);

        //整理输出数据，将相关Model实例转为数组
        $items = (new Collection($data['items']))->toArray();
        foreach($items as &$item){
            $item['roles'] = $this->glueRolesName($item['roles']);
        }
        $data['items'] = $items;

        return $data;
    }

    /**
     * 获取用户详细信息，包含角色列表
     * @param $id
     * @return array
     */
    public function get($id){
        $user = parent::one($id,"roles");
        $roles = RoleService::instance()->all();
        $data['user'] = $user->toArray();
        $data['roles'] = $roles->toArray();
        return $data;
    }
}