<?php

namespace App\Http\Controllers\Admin;

use app\Components\Pagination\SimplePresenter;
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
use \DB;

/**
 * 用户控制器
 * Class UsersController
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller
{

    public function test(Request $request){
        $params = $request->all();
        $data = [
            'draw'=>1,
            'recordsTotal'=>5,
            'recordsFiltered'=>5,
            'data'=>[
                'data'=> [
                ['id'=>1,'username'=>'snake','nickname'=>'snake','roles'=>'admin','created_at'=>'2016-12-14','active'=>1],
                ['id'=>2,'username'=>'snake1','nickname'=>'snake1','roles'=>'admin1','created_at'=>'2016-12-14','active'=>1],
                ['id'=>3,'username'=>'snake2','nickname'=>'snake2','roles'=>'admin2','created_at'=>'2016-12-14','active'=>1]
                ]
            ]
        ];

        return response()->json($data);
    }

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
        //return response()->json($data);
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

        UserService::instance()->save($paramters);
        return responseSuccess();
    }


    public function toEdit(Request $request){
        $params = $this->validateAndFilter($request,[
            'id'=>'required'
        ]);

        $data = UserService::instance()->get($params['id']);
        return view('admin.default.admin-edit',$data);
    }


    public function edit(Request $request){
        $user = $this->validateAndFilter($request,[
            'id'=>'required',
            'username'=>'',
            'nickname'=>'',
            'roles'=>''
        ]);

        UserService::instance()->save($user);
        return responseSuccess();
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
            return responseFailed(-1,"请勾选想要删除的条目");

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
