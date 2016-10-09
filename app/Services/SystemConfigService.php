<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 17:10
 * Name:
 */
namespace App\Service;

use App\Foundation\Facades\Map;
use App\Menu;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemConfigService extends Service
{

    /**
     * 获取系统配置
     */
    public static function getConfigs(){
        $CONFIG = Map::model('SystemConfig');

        $configs = $CONFIG::all();
        $configs = $configs->all();
        $result = [];
        foreach($configs as $config){
            $kv = $config->attributesToArray();
            $result[$kv['key']] = $kv['value'];
        }
        return $result;

    }

    /**
     * 根据用户id，获取拥有准入权限的菜单列表
     * @param int $id
     * @return Collection
     */
    public static function getMenus($id){
        $USER = Map::model('User');
        $user = $USER::with('roles')->find($id);

        $menus = self::getAllMenus();

        $accessMenus = [];
        //menus数据集本身已经按照layer顺序排列
        foreach($menus as $menu){
            if($user->hasPermission($menu->permission,false,'name')){
                switch($menu->layer){
                    case 1:
                        $lv1Pms = &$accessMenus[$menu->id];
                        $lv1Pms['menu'] = $menu->toArray();
                        break;
                    case 2:
                        $lv1Pms = &$accessMenus[$menu->pid];
                        $lv2ChildPms = &$lv1Pms['subMenus'];
                        $lv2ChildPms[$menu->id] = ['menu'=>$menu->toArray()];
                        break;

                }
            }
        }

        return $accessMenus;

    }

    public static function getAllMenus(){
        $MENU = Map::model('Menu');
        $menus = Cache::rememberForever('admin.menu', function() use($MENU) {
            return $menus = $MENU::where('active',1)->orderBy('layer','asc')->orderBy('pid','asc')->get();
        });

        return $menus;
    }

}