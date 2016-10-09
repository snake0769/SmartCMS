<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/7/7
 * Time: 11:49
 * Name:
 */
namespace App\Listeners;

use Illuminate\Routing\Events\RouteMatched;

class RouteMatchedEventListener
{

    /**
     * 创建事件侦听器。
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 处理事件。
     *
     * @param  RouteMatched  $event
     * @return void
     */
    public function handle(RouteMatched $event)
    {
    }
}