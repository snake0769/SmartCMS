<?php

namespace App\Http\Controllers\Admin;

use app\Components\Pagination\SimplePresenter;
use App\Models\Role;
use App\Models\User;
use App\Service\RoleService;
use App\Service\UserService;
use Exception;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * 用户控制器
 * Class UsersController
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller
{

    use ResetsPasswords;

    /**
     * 进入用户列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toList(){
        return view('admin.default.admin-list');
    }

    /**
     * 获取用户列表
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getList(Request $request){
        $params = $this->validateAndFilter($request,[
            'start_date'=>'date',
            'end_date'=>'date',
            'keyword'=>'',
            'order'=>'json',
            'draw'=>'',
            'start'=>'integer|min:0',
            'length'=>'integer|min:1'
        ]);
        $data = UserService::instance()->getList($params);
        $data = $this->toDataTables($params,$data);
        return responseSuccess($data);
    }


    /**
     * 进入添加用户
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toCreate(){
        $roles = RoleService::instance()->all(null,null,['*'],['created_at'=>'asc']);
        $data['roles'] = $roles->toArray();
        return view('admin.default.admin-add',$data);
    }


    /**
     * 添加用户
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function create(Request $request){
        $paramters = $this->validateAndFilter($request,[
            'username'=>'required',
            'nickname'=>'required',
            'password'=>'required',
            'roles'=>'required',
        ]);

        UserService::instance()->create($paramters);
        return responseSuccess();
    }


    /**
     * 进入编辑用户
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toEdit(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required'
        ]);

        $user = UserService::instance()->get($params['id']);
        return view('admin.default.admin-edit',$user);
    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function edit(Request $request){
        $user = $this->validateAndFilter($request,[
            'id'=>'required',
            'username'=>'',
            'nickname'=>'',
            'roles'=>''
        ]);

        UserService::instance()->update($user);
        return responseSuccess();
    }


    /**
     * 删除用户,支持批量删除
     * @param Request $request
     * @return string
     */
    public function delete(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required',
        ],[
            'id.required'=>'请选择要删除的项目'
        ]);

        UserService::instance()->delete($params['id']);
        return responseSuccess();
    }


    /**
     * 启用或停用用户
     * @param Request $request
     * @return string
     */
    public function activate(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required',
            'active'=>'required|integer|between:0,1'
        ]);

        UserService::instance()->activate($params['id'],$params['active']);
        return responseSuccess();
    }


    /**
     * 进入重设密码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toResetPassword(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required'
        ]);

        $user = UserService::instance()->get($params['id']);
        return view('admin.default.reset-password',$user);
    }


    /**
     * 重设密码
     * @param Request $request
     * @return string
     */
    public function resetPassword(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required',
            'new_password'=>'required|same:new_password2',
            'new_password2'=>'required'
        ]);

        UserService::instance()->resetPassword($params['id'],$params['new_password']);
        return responseSuccess();
    }


    /**
     * 进入修改密码
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toChangePassword(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required'
        ]);

        $user = UserService::instance()->get($params['id']);
        return view('admin.default.change-password',$user);
    }

    /**
     * 修改密码
     * @param Request $request
     * @return string
     */
    public function changePassword(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required',
            'old_password'=>'required',
            'new_password'=>'required'
        ]);

        UserService::instance()->resetPassword($params['id'],$params['new_password'],$params['old_password']);
        return responseSuccess();
    }

}
