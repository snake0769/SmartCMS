<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $permissions = Permission::getAll();
        foreach ($permissions as $permission) {
            /** @var Permission $permission */
            $gate->define($permission->name, function($user) use ($permission) {
                /** @var $user User */
                return $user->hasPermission([$permission]);
            });
        }
    }
}
