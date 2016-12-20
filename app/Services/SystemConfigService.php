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
use App\Models\Menu;
use App\Models\SystemConfig;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Debug\Exception\FatalErrorException;

class SystemConfigService extends Service
{

    /** @var SystemConfig **/
    protected $baseModel = SystemConfig::class;
    /** @var User **/
    protected $userModel;
    /** @var Menu **/
    protected $menuModel;

    function __construct()
    {
        $this->userModel = \Map::getClass(User::class,self::class);
        $this->menuModel = \Map::getClass(Menu::class,self::class);
    }

    /**
     * 获取系统配置
     */
    public function getConfigs(){
        /** @var $CONFIG SystemConfig **/
        $CONFIG = $this->baseModel;

        /** @var $configs SystemConfig[] **/
        $configs = $CONFIG::all()->all();
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
    public function getMenus($id){
        /**@var $user User*/
        $USER = $this->userModel;

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

    /**
     * 获取所有菜单
     * @return Collection
     */
    public function getAllMenus(){
        //throw new FatalErrorException(null,null,null,null,null);
        /**@var $MENU Menu*/
        $MENU = $this->menuModel;
        $menus = \Cache::rememberForever('admin.menu', function() use($MENU) {
            return $menus = $MENU::where('active',1)->orderBy('layer','asc')->orderBy('pid','asc')->get();
        });

        return $menus;
    }

}