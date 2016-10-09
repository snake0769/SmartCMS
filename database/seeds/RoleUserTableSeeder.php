<?php

use Illuminate\Database\Seeder;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $datas[] = [
            "role_id" => "1",
            "user_id" => "1"
        ];

        DB::table("role_user")->insert($datas);
    }
}
