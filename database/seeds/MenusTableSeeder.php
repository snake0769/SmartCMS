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
        $datas[] = [
            "title" => "管理员管理","icon" => "&#xe62d;","sort" => 1,"layer" => 1,"pid" => 0,"permission" => "admin.users","url" => "",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "title" => "管理员列表","icon" => "","sort" => 1,"layer" => 2,"pid" => 1,"permission" => "admin.users.index","url" => "/admin/users",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "title" => "角色管理","icon" => "","sort" => 2,"layer" => 2,"pid" => 1,"permission" => "admin.roles.index","url" => "/admin/roles",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "title" => "权限管理","icon" => "","sort" => 3,"layer" => 2,"pid" => 1,"permission" => "admin.permissions.index","url" => "/admin/permissions",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        //系统管理
        $datas[] = [
            "title" => "系统管理","icon" => "&#xe62e;","sort" => 1,"layer" => 1,"pid" => 0,"permission" => "admin.system","url" => "",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "title" => "系统设置","icon" => "","sort" => 1,"layer" => 2,"pid" => 5,"permission" => "admin.system.index","url" => "/admin/system",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];
        $datas[] = [
            "title" => "系统日志","icon" => "","sort" => 2,"layer" => 2,"pid" => 5,"permission" => "admin.logs.index","url" => "/admin/logs",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        DB::table("menus")->insert($datas);
    }
}
