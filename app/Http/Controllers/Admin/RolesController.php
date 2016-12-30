<?php

namespace App\Http\Controllers\Admin;

use App\Foundation\Facades\Map;
use App\Models\Permission;
use App\Service\PermissionService;
use App\Service\RoleService;
use App\Service\UserService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Exception;

/**
 * 角色管理控制器
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class RolesController extends Controller
{

    /**
     * 进入角色列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toList(){
        return view('admin.default.admin-role');
    }

    /**
     * 获取角色列表
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
        $data = RoleService::instance()->getList($params);
        $data = $this->toDataTables($params,$data);
        return responseSuccess($data);
    }

    /**
     * 进入添加角色
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toCreate()
    {
        $permissions = PermissionService::instance()->getAllWithLayer();
        $datas['permissions'] = $permissions;
        return view('admin.default.admin-role-add',$datas);

    }


    /**
     * 添加用户
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function create(Request $request){
        $paramters = $this->validateAndFilter($request,[
            'name'=>'required',
            'description'=>'',
            'permissions'=>''
        ]);

        RoleService::instance()->create($paramters);
        return responseSuccess();
    }


    /**
     * 进入编辑角色
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toEdit(Request $request)
    {
        $params = $this->validateAndFilter($request,[
            'id'=>'required'
        ]);
        $role = RoleService::instance()->get($params['id']);
        return view('admin.default.admin-role-edit', $role);
    }

    /**
     * 编辑角色
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function edit(Request $request)
    {
        $role = $this->validateAndFilter($request,[
            'id'=>'required',
            'name'=>'required',
            'description'=>'',
            'permissions'=>''
        ]);

        RoleService::instance()->update($role);
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

        RoleService::instance()->delete($params['id']);
        return responseSuccess();
    }
}
