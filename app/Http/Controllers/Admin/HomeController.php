<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Service\RoleService;
use App\Service\SystemConfigService;
use App\Service\UserService;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{


    public function __construct()
    {
        $this->middleware("auth", ['except' => 'getTest']);
    }


    /**
     * 获取首页信息
     */
    function getIndex(){
        $user = \Auth::user();
        $usrSrv = UserService::instance();
        $rolesName = $usrSrv->getRolesLabel($user);
        $configSrv = SystemConfigService::instance();
        $accessMenus = $configSrv->getMenus($user->id);
        $user = ['roles'=>$rolesName,'username'=>$user->username,'nickname'=>$user->nickname];
        $data = ['user'=>$user,'menus'=>$accessMenus];
        return view('admin.default.index',$data);
    }


    function getWelcome(){
        $datas = [];
        return view('admin.default.welcome', $datas);
    }


    /**
     * 清除缓存
     */
    function postClear(){
        \Cache::forget('admin.menu');
        \Cache::forget('admin.permission');
        return responseSuccess();
    }

}
