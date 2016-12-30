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
    protected $baseModel;
    /** @var User **/
    protected $userModel;
    /** @var Menu **/
    protected $menuModel;

    function __construct()
    {
        $this->baseModel = \Map::getClass(SystemConfig::class,self::class);
        $this->userModel = \Map::getClass(User::class,self::class);
        $this->menuModel = \Map::getClass(Menu::class,self::class);
    }

    /**
     * 获取系统配置
     */
    public function getConfigs(){
        /** @var $configs SystemConfig[] **/
        $configs = parent::all(null,null,null,['sort'=>'asc'])->all();
        $result = [];
        foreach($configs as $config){
            $kv = $config->attributesToArray();
            $result[$kv['key']] = $kv;
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
            if($user->hasPermission($menu->permission,'name')){
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
        /**@var $MENU Menu*/
        $MENU = $this->menuModel;
        $menus = \Cache::rememberForever('admin.menu', function() use($MENU) {
            return $menus = $MENU::where('active',1)->orderBy('layer','asc')->orderBy('pid','asc')->get();
        });

        return $menus;
    }

    /**
     * @param array $attributes
     * @return bool|int
     * @throws \App\Exceptions\BusinessException
     */
    public function update(array $attributes)
    {
        //根据attributes是否包含id值，执行插入或更新操作，并称“保存”操作
        /**@var $CONFIG SystemConfig*/
        $CONFIG = $this->baseModel;
        return $CONFIG::setConfigs($attributes);
    }


}