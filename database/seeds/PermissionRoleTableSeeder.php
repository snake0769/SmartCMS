<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=1;$i<=20;$i++){
            $datas[] = ["permission_id" => $i,"role_id" => 1];
        }

        DB::table("permission_role")->insert($datas);
    }
}
