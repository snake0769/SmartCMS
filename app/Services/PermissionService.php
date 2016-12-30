<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 17:10
 * Name:
 */
namespace App\Service;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PermissionService extends Service
{

    /**@var Permission**/
    protected $baseModel;

    protected function __construct()
    {
        $this->baseModel = \Map::getClass(Permission::class,self::class);
        parent::__construct();
    }


    /**
     * 获取所有权限,权限经过格式整理,以层级方式呈现
     * @return \App\Models\Permission[]
     */
    public function getAllWithLayer(){
        $PERMISSION = $this->baseModel;
        return  $PERMISSION::getAllWithLayer();
    }

}