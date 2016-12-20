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
            "role_id" => "39dc2c9e-d123-a147-8e9f-6fefc9bb23b1",
            "user_id" => "39dc2c9e-d037-bb33-15e6-9e5a97c76d79"
        ];

        DB::table("role_user")->insert($datas);
    }
}
