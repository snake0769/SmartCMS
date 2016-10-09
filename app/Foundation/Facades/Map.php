<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/30
 * Time: 14:40
 * Name:
 */
namespace App\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

class Map extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'map';
    }


}