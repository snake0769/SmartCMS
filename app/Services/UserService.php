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
     * 更新用户
     * @param array $attributes 必须包含id属性
     * @return boolean
     * @throws BusinessException|\Exception
     */
    public function update(array $attributes)
    {
        try {
            \DB::beginTransaction();

            //如果属性未发生更改，则直接返回
            if (isset($attributes['password'])) {
                $attributes['password'] = bcrypt($attributes['password']);
            }

            $roles = array_pull($attributes, "roles");
            $rs = parent::update($attributes);

            //保存基本User数据后，保存Role数据
            /**@var $user User* */
            if ($rs !== false && !empty($roles)) {
                $roles = explode(',', $roles);
                $user = new User($attributes);
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
     * 添加用户
     * @param array $attributes
     * @return boolean
     * @throws BusinessException|\Exception
     */
    public function create(array $attributes)
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
                        $user = parent::create($attributes);
                    } else {
                        throw new BusinessException('用户名已存在');
                    }
                }
            } else {
                $user = parent::create($attributes);
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
    public function activate($id,$active){
        $user = parent::one($id);
        if($active == '1'){
            return $user->activate();
        }else{
            return $user->inactivate();
        }
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


    /**
     * 重设用户密码
     * @param $userId string 用户id
     * @param $newPwd string 原生不被加密的新密码
     * @param null|string $oldPwd 旧密码,如果传入不为空,则对比旧密码是否正确,正确才能执行重设密码的动作;如果为空,则直接重设密码
     * @return bool|int
     * @throws BusinessException
     */
    public function resetPassword($userId,$newPwd,$oldPwd=null)
    {
        $user = $this->one($userId);
        if($oldPwd !== null && $user->password !== bcrypt($oldPwd)){
            throw new BusinessException('旧密码错误');
        }
        return $user->resetPassword($newPwd);
    }

}