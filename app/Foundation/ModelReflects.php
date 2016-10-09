<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/30
 * Time: 14:05
 * Name:
 */

namespace App\Foundation;


trait ModelReflects
{

    private static $models = [];

    /** @var  ModelMapper* */
    protected static $mapper = DatabaseMapper::class;

    /**
     * 设置模型映射器
     * @param ModelMapper $mapper
     */
    public static function setModelMapper($mapper){
        self::$mapper = $mapper;
    }
}