<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class RequestUrlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return voidau
     */
    public function run()
    {

        //生成restful接口权限数据
        $except = 'auth,index';//这里填写非restful controller的名字
        $this->generateRestfulInterface($except);

        //生成自定义接口权限数据
        $this->generateInterface('POST','admin/users/activate','admin.users.edit');
        $this->generateInterface('DELETE','admin/users/batch_delete','admin.users.delete');
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
                    "permission" => $permission,"url" => $url,"name" => "","method"=>$requestMethod,
                    "created_at" => Carbon::now()->toDateTimeString(),"updated_at" => Carbon::now()->toDateTimeString()
                ];
            }


        }

        DB::table('request_urls')->insert($datas);

    }


    private function generateInterface($method,$url,$permission)
    {
        $data = [
            "permission" => $permission, "url" => $url,  "method" => $method,
            "created_at" => Carbon::now()->toDateTimeString(), "updated_at" => Carbon::now()->toDateTimeString()
        ];

        DB::table('request_urls')->insert($data);
    }



}
