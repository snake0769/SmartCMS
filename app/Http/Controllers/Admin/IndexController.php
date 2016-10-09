<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Service\SystemConfigService;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class IndexController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth", ['except' => 'getTest']);
    }


    function getIndex(){
        $user = Auth::user();
        $rolesName = Role::drawLabel($user->roles);
        $userData = ['roles'=>$rolesName,'username'=>$user->username,'nickname'=>$user->nickname,];
        $accessMenus = SystemConfigService::getMenus($user->id);
        $datas = ['user'=>$userData,'menus'=>$accessMenus];
        return view('admin.default.index',$datas);
    }


    function getWelcome(){
        $datas = [];
        return view('admin.default.welcome', $datas);
    }


    function getTest(Request $request){
        $user = User::find(1);
        /*$r1 = Role::find(1);
        $r2 = new Role();
        $r2->id = 100;
        $r2->name = "ttt.index";
        $collection =  collect([$r1,$r2]);
        dump($user->hasRole([1,211],true,"id"));*/
        $pms1 = Permission::find(1);
        $pms2 = new Permission();
        $pms2->id = 100;
        $pms2->name = "ttt.index";
        $collection =  collect([$pms1,null]);
        dump($user->hasPermission($collection,true,"id"));

    }



    /**
     * 清除缓存
     */
    function postClear(){
        Cache::forget('admin.menu');
        Cache::forget('admin.permission');
        return responseSuccess();
    }

}
