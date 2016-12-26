<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //管理员管理
        $id = \app\Components\Util\StringHelper::uuid();
        $datas[] = [
            "id" =>$id,
            "title" => "管理员管理","icon" => "&#xe62d;","sort" => 1,"layer" => 1,"pid" => 0,"permission" => "admin.users","url" => "",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "id" =>\app\Components\Util\StringHelper::uuid(),
            "title" => "管理员列表","icon" => "","sort" => 1,"layer" => 2,"pid" => $id,"permission" => "admin.users.index","url" => "/admin/users/to_list",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "id" =>\app\Components\Util\StringHelper::uuid(),
            "title" => "角色管理","icon" => "","sort" => 2,"layer" => 2,"pid" => $id,"permission" => "admin.roles.index","url" => "/admin/roles/to_list",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "id" =>\app\Components\Util\StringHelper::uuid(),
            "title" => "权限管理","icon" => "","sort" => 3,"layer" => 2,"pid" => $id,"permission" => "admin.permissions.index","url" => "/admin/permissions/to_list",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        //系统管理
        $id = \app\Components\Util\StringHelper::uuid();
        $datas[] = [
            "id" =>$id,
            "title" => "系统管理","icon" => "&#xe62e;","sort" => 1,"layer" => 1,"pid" => 0,"permission" => "admin.system","url" => "",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "id" =>\app\Components\Util\StringHelper::uuid(),
            "title" => "系统设置","icon" => "","sort" => 1,"layer" => 2,"pid" => $id,"permission" => "admin.system.index","url" => "/admin/system/to_list",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "id" =>\app\Components\Util\StringHelper::uuid(),
            "title" => "系统日志","icon" => "","sort" => 2,"layer" => 2,"pid" => $id,"permission" => "admin.logs.index","url" => "/admin/logs/to_list",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        DB::table("menus")->insert($datas);
    }
}
