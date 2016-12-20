<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class SystemConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $datas = [
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '后台标题',
                'key'              => 'admin.base.title',
                'value'             => 'Admin',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '关键字',
                'key'              => 'admin.base.keywords',
                'value'             => '站点管理,后台',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '后台描述',
                'key'              => 'admin.base.description',
                'value'             => '这是企业网站管理后台',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '公司名称',
                'key'              => 'admin.base.companyName',
                'value'             => 'Admin.LTD',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        DB::table('system_configs')->insert($datas);

        Model::reguard();
    }
}
