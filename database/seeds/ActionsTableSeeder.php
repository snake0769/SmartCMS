<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class ActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*//生成restful接口权限数据
        $except = 'auth,index';//这里填写非restful controller的名字
        $this->generateRestfulInterface($except);

        //生成自定义接口权限数据
        $this->generateInterface('POST','admin/users/activate','admin.users.edit');
        $this->generateInterface('DELETE','admin/users/batch_delete','admin.users.delete');*/
        $data = [];
        $this->genUsers($data);
        $this->genRoles($data);
        $this->genPermissions($data);

        DB::table('actions')->insert($data);
    }

    private function fillDefault(&$data){
        foreach($data as &$item){
            if(!array_key_exists('only_own',$item)){
                $item['only_own'] = 0;
            }

            $now = Carbon::now()->toDateTimeString();
            if(!array_key_exists('created_at',$item)){
                $item['created_at'] = $now;
            }
            if(!array_key_exists('updated_at',$item)){
                $item['updated_at'] = $now;
            }
        }
    }

    private function genUsers(&$data){
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toList',
            'permission' => 'admin.users.show',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@getList',
            'permission' => 'admin.users.show',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toCreate',
            'permission' => 'admin.users.create',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@create',
            'permission' => 'admin.users.create',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toEdit',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@edit',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@delete',
            'permission' => 'admin',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@activate',
            'permission' => 'admin',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toResetPassword',
            'permission' => 'admin',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@resetPassword',
            'permission' => 'admin',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toChangePassword',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@changePassword',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $this->fillDefault($data);
    }

    private function genRoles(&$data){
        $this->fillDefault($data);
    }

    private function genPermissions(&$data){
        $this->fillDefault($data);
    }

    private function generateRestfulInterface($except=null){
        $datas = [];

        $routes = Route::getRoutes();
        foreach($routes as $route){
            $actionName = $route->getActionName();
            if(strtolower($actionName) == "closure"){
                continue;
            }

            $action = explode("\\",$actionName);
            $module = strtolower($action[count($action)-2]);
            $action = $action[count($action)-1];
            $action = explode("@",$action);

            $controller = strtolower(str_replace("Controller",'',$action[0]));

            //跳过过滤掉的控制器url
            if( empty($except) || !str_contains($except,$controller)){
                $method = $action[1];
                $permission = "$module.$controller";
                $requestMethod = implode(",",$route->getMethods());
                $url = $route->getPath();

                if($method == "create"){
                    $permission .= ".create";
                }elseif($method == "edit"){
                    $permission .= ".edit";
                }elseif($method == "show"){
                    $permission .= ".show";
                }elseif($method == "store"){
                    $permission .= ".create";
                }elseif($method == "update"){
                    $permission .= ".edit";
                }elseif($method == "destroy"){
                    $permission .= ".delete";
                }else{
                    continue;
                }

                $datas[] = [
                    "id" =>\app\Components\Util\StringHelper::uuid(),
                    "permission" => $permission,"url" => $url,"name" => "","method"=>$requestMethod,
                    "created_at" => Carbon::now()->toDateTimeString(),"updated_at" => Carbon::now()->toDateTimeString()
                ];
            }


        }

        DB::table('actions')->insert($datas);

    }


    private function generateInterface($method,$url,$permission)
    {
        $data = [
            "id" =>\app\Components\Util\StringHelper::uuid(),
            "permission" => $permission, "url" => $url,  "method" => $method,
            "created_at" => Carbon::now()->toDateTimeString(), "updated_at" => Carbon::now()->toDateTimeString()
        ];

        DB::table('actions')->insert($data);
    }



}
