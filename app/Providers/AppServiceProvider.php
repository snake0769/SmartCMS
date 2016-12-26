<?php

namespace App\Providers;

use App\Models\SystemConfig;
use App\Service\SystemConfigService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->shareViews();
        //自定义校验器规则:
        //like:对比字符串是否相同,大小写不敏感
        \Validator::extend('like',function($attribute, $value, $parameters, $validator){
            $isLike = strtolower($value) == strtolower($parameters[0]);
            return $isLike;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * 设置其全局视图变量
     */
    public function shareViews(){
        try{
            $configSrv = SystemConfigService::instance();
            $configs = $configSrv->getConfigs();
            //g开头表示global，全局视图变量都以g开头
            view()->share('gTitle',$configs['admin.base.title']);
            view()->share('gKeywords',$configs['admin.base.keywords']);
            view()->share('gDescription',$configs['admin.base.description']);
            view()->share('gCompanyName',$configs['admin.base.companyName']);
        }catch(\Exception $ex){
        }

    }

}
