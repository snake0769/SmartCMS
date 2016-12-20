<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas[] = [
            "id" =>'39dc2c9e-d123-a147-8e9f-6fefc9bb23b1',
            "name" => "超级管理员",
            "label" => "",
            "description" => "拥有所有权限",
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        DB::table("roles")->insert($datas);
    }
}
