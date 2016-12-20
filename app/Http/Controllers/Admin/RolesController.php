<?php

namespace App\Http\Controllers\Admin;

use App\Foundation\Facades\Map;
use App\Models\Permission;
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

    public function index(Request $request)
    {
        $paramters = $request->all();
        if (isset($paramters['page']))
            $page = array_pull($paramters, 'page');
        else
            $page = 1;

        $query = RoleService::buildQuery("users", $paramters, ['*'], ['id' => 'asc']);
        $roles = $this->paginate($query, $page);
        $data['roles'] = $roles;
        return view('admin.default.admin-role', $data);
    }


    public function create()
    {
        $PERMISSION = Map::model('Permission');
        $permissions = $PERMISSION::getPermissions();
        $datas['permissions'] = $permissions;
        return view('admin.default.admin-role-add',$datas);

    }


    public function store()
    {

        try {
            DB::beginTransaction();

            DB::enableQueryLog();
            $params = Input::except(['admin-role-save','_token']);
            isError(RoleService::save($params), true);

            DB::commit();
            return responseSuccess();
        } catch (Exception $ex) {
            DB::rollback();
            return responseError($ex);
        }

    }


    public function show($id)
    {
        $user = UserService::get($id, "roles");
        $roles = RoleService::get();
        $data['user'] = $user->toArray();
        $data['roles'] = $roles->toArray();
        return view('admin.default.admin-edit', $data);
    }


    public function edit($id)
    {
        /*$user = UserService::get($id,"roles");
        $roles = RoleService::get();
        $data['user'] = $user->toArray();
        $data['roles'] = $roles->toArray();*/
        return view('admin.default.admin-role-add');
    }


    public function update($id)
    {
        try {
            DB::beginTransaction();

            $user = array_merge(['id' => $id], Input::all());
            isError(UserService::save($user), true);

            DB::commit();
            return responseSuccess();
        } catch (Exception $ex) {
            DB::rollback();
            return responseError($ex);
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            isError(UserService::delete($id), true);

            DB::commit();
            return responseSuccess();
        } catch (Exception $ex) {
            DB::rollback();
            return responseError($ex);
        }
    }


    public function activate(Request $request)
    {
        try {
            DB::beginTransaction();

            $id = $request->get('id');
            $active = $request->get('active');

            $user = UserService::get($id);
            if (!empty($user)) {
                if ($active == "0")
                    $rs = $user->inactivate();
                else
                    $rs = $user->activate();
            }

            DB::commit();
            return responseSuccess();
        } catch (Exception $ex) {
            DB::rollback();
            return responseError($ex);
        }

    }

    public function batchDestroy(Request $request)
    {
        $ids = $request->get('ids');
        if (empty($ids))
            return responseFailed(-1, "请勾选想要删除的条目");

        try {
            DB::beginTransaction();

            isError(UserService::delete($ids), true);

            DB::commit();
            return responseSuccess();
        } catch (Exception $ex) {
            DB::rollback();
            return responseError($ex);
        }
    }
}
