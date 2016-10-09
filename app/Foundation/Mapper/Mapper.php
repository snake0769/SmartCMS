<?php

/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/30
 * Time: 11:53
 * Name:
 */
namespace App\Foundation\Contracts;

interface Mapper
{

    /**
     * 获取指定key的Model类，返回类名
     * @param $key
     * @return string
     */
    static function model($key);

}