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
                'name'            => '描述',
                'key'              => 'admin.base.description',
                'value'             => '这是企业网站管理后台',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => 'css、js、images根路径配置',
                'key'              => 'admin.base.assetsRoot',
                'value'             => 'public',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '上传文件目录',
                'key'              => 'admin.base.uploadRoot',
                'value'             => 'public/upload',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '底部版权信息',
                'key'              => 'admin.base.footer',
                'value'             => 'Admin.LTD',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
            [
                "id" =>\app\Components\Util\StringHelper::uuid(),
                'name'            => '备案号',
                'key'              => 'admin.base.icp',
                'value'             => '粤ICP备88888888',
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ];

        //按顺序填充排序字段sort
        foreach($datas as $sort=>&$value){
            $value['sort'] = $sort;
        }

        DB::table('system_configs')->insert($datas);

        Model::reguard();
    }
}
