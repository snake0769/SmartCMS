<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class ActionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [];
        $this->genUsers($data);
        $this->genRoles($data);
        $this->genPermissions($data);

        $this->genSystemConfigs($data);

        DB::table('actions')->insert($data);
    }

    private function fillDefault(&$data){
        foreach($data as &$item){
            if(!array_key_exists('only_own',$item)){
                $item['only_own'] = 0;
            }

            $now = Carbon::now()->toDateTimeString();
            if(!array_key_exists('created_at',$item)){
                $item['created_at'] = $now;
            }
            if(!array_key_exists('updated_at',$item)){
                $item['updated_at'] = $now;
            }
        }
    }

    private function genUsers(&$data){
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toList',
            'permission' => 'admin.users.show',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@getList',
            'permission' => 'admin.users.show',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toCreate',
            'permission' => 'admin.users.create',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@create',
            'permission' => 'admin.users.create',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toEdit',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@edit',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@delete',
            'permission' => 'admin',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@activate',
            'permission' => 'admin',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toResetPassword',
            'permission' => 'admin',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@resetPassword',
            'permission' => 'admin',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@toChangePassword',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\UsersController::class.'@changePassword',
            'permission' => 'admin.users.edit',
            'only_own' => 1,
        ];

        $this->fillDefault($data);
    }

    private function genRoles(&$data){
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@toList',
            'permission' => 'admin.roles.show',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@getList',
            'permission' => 'admin.roles.show',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@toCreate',
            'permission' => 'admin.roles.create',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@create',
            'permission' => 'admin.roles.create',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@toEdit',
            'permission' => 'admin.roles.show',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@toEdit',
            'permission' => 'admin.roles.edit',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@edit',
            'permission' => 'admin.roles.edit',
            'only_own' => 0,
        ];
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\RolesController::class.'@delete',
            'permission' => 'admin.roles.delete',
            'only_own' => 0,
        ];

        $this->fillDefault($data);
    }

    private function genPermissions(&$data){
        $this->fillDefault($data);
    }

    private function genSystemConfigs(&$data){
        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\SystemConfigsController::class.'@index',
            'permission' => 'admin.system.show',
            'only_own' => 0,
        ];

        $data[] = [
            'id' => \app\Components\Util\StringHelper::uuid(),
            'action' => \App\Http\Controllers\Admin\SystemConfigsController::class.'@edit',
            'permission' => 'admin.system.edit',
            'only_own' => 0,
        ];

        $this->fillDefault($data);
    }


}
