<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/30
 * Time: 13:39
 * Name:
 */

namespace App\Foundation\Contracts;


use App\Menu;
use App\Models\Permission;
use App\Models\Role;
use App\Models\SystemConfig;
use App\Models\User;

class DatabaseMapper implements Mapper
{

    private static $maps = [
        'User'=>User::class,
        'Role'=>Role::class,
        'Permission'=>Permission::class,
        'Menu'=>Menu::class,
        'SystemConfig'=>SystemConfig::class,
    ];

    public static function model($key)
    {
        if(isset(self::$maps[$key])){
            return self::$maps[$key];
        }else{
            return null;
        }
    }


}