<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Service\RoleService;
use App\Service\UserService;
use Illuminate\Database\QueryException;
use Exception;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function index(Request $request){
        $paramters = $request->all();
        if(isset($paramters['page']))
            $page = array_pull($paramters,'page');
        else
            $page = 1;

        $query =  UserService::buildQuery("roles",$paramters,['*'],['id'=>'asc']);
        $users =  $this->paginate($query,$page);
        $data['users'] = $users;
        return view('admin.default.admin-list',$data);
    }


    public function create(){
        $roles = RoleService::get();
        $data['roles'] = $roles->toArray();
        return view('admin.default.admin-add',$data);

    }


    public function store(){

        try{
            DB::beginTransaction();

            isError(UserService::save(Input::all()),true);

            DB::commit();
            return responseSuccess();
        }catch(Exception $ex){
            DB::rollback();
            return responseError($ex);
        }

    }


    public function show($id){
        $user = UserService::get($id,"roles");
        $roles = RoleService::get();
        $data['user'] = $user->toArray();
        $data['roles'] = $roles->toArray();
        return view('admin.default.admin-edit',$data);
    }


    public function edit($id){
        $user = UserService::get($id,"roles");
        $roles = RoleService::get();
        $data['user'] = $user->toArray();
        $data['roles'] = $roles->toArray();
        return view('admin.default.admin-edit',$data);
    }


    public function update($id){
        try{
            DB::beginTransaction();

            $user = array_merge(['id'=>$id],Input::all());
            isError(UserService::save($user),true);

            DB::commit();
            return responseSuccess();
        }catch(Exception $ex){
            DB::rollback();
            return responseError($ex);
        }
    }


    public function destroy($id){
        try{
            DB::beginTransaction();

            isError(UserService::delete($id),true);

            DB::commit();
            return responseSuccess();
        }catch(Exception $ex){
            DB::rollback();
            return responseError($ex);
        }
    }


    public function activate(Request $request){
        try{
            DB::beginTransaction();

            $id = $request->get('id');
            $active = $request->get('active');

            $user = UserService::get($id);
            if(!empty($user)){
                if($active == "0")
                    $rs = $user->inactivate();
                else
                    $rs = $user->activate();
            }

            DB::commit();
            return responseSuccess();
        }catch(Exception $ex){
            DB::rollback();
            return responseError($ex);
        }

    }

    public function batchDestroy(Request $request){
        $ids = $request->get('ids');
        if(empty($ids))
            return responseFalied(-1,"请勾选想要删除的条目");

        try{
            DB::beginTransaction();

            isError(UserService::delete($ids),true);

            DB::commit();
            return responseSuccess();
        }catch(Exception $ex){
            DB::rollback();
            return responseError($ex);
        }
    }

}
