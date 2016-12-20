<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas[] = [
            "id" =>'39dc2c9e-d037-bb33-15e6-9e5a97c76d79',
            "username" => "admin",
            "nickName" => "admin",
            "email" => "admin@admin.com",
            "password" => bcrypt("123456"),
            "created_at" => Carbon::now()->toDateTimeString(),
            "updated_at" => Carbon::now()->toDateTimeString()
        ];

        DB::table("users")->insert($datas);
    }
}
