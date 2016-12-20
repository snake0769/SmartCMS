<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/30
 * Time: 13:39
 * Name:
 */

namespace App\Foundation\Mapper;

class Mapper
{

    /** @var array */
    protected static $container = [];
    /** @var string */
    protected static $space = 'global';
    /** @var string */
    protected static $delimiter = ':';

    /**
     * 设置类
     * @param string $key
     * @param string $className
     * @param string $spaceSuffix
     * @return null
     */
    public static function setClass($key, $className, $spaceSuffix = null)
    {
        if (!isset(static::$container[static::parseSpace($spaceSuffix)])) {
            static::$container[static::parseSpace($spaceSuffix)] = [];
        }
        $keyType = 'class';
        static::$container[static::parseSpace($spaceSuffix)][static::parseName($keyType, $key)] = $className;
    }

    /**
     * 获取类
     * @param string $className
     * @param string $spaceSuffix
     * @return string
     */
    public static function getClass($className, $spaceSuffix = null)
    {
        if (!isset(static::$container[static::parseSpace($spaceSuffix)])) {
            static::$container[static::parseSpace($spaceSuffix)] = [];
        }
        $keyType = 'class';
        if (array_key_exists(static::parseName($keyType, $className), static::$container[static::parseSpace($spaceSuffix)])) {
            return static::$container[static::parseSpace($spaceSuffix)][static::parseName($keyType, $className)];
        } else {
            return $className;
        }
    }

    /**
     * 设置实例
     * @param string $key
     * @param object $instance
     * @param string $spaceSuffix
     */
    public static function setInstance($key, $instance, $spaceSuffix = null)
    {
        if (!isset(static::$container[static::parseSpace($spaceSuffix)])) {
            static::$container[static::parseSpace($spaceSuffix)] = [];
        }
        $keyType = 'instance';
        static::$container[static::parseSpace($spaceSuffix)][static::parseName($keyType, $key)] = $instance;
        return;
    }

    /**
     * 设置实例通过类名
     * @param string $key
     * @param string $className
     * @param array $classParams
     * @param string $spaceSuffix
     */
    public static function setInstanceByClassName($key, $className, $classParams = [], $spaceSuffix = null)
    {
        if (!isset(static::$container[static::parseSpace($spaceSuffix)])) {
            static::$container[static::parseSpace($spaceSuffix)] = [];
        }
        $type = 'instance';
        static::$container[static::parseSpace($spaceSuffix)][static::parseName($type, $key)] = static::buildInstance($className, $classParams);
        return;
    }

    /**
     * 获取实例
     * @param string $className
     * @param array $params
     * @param string $spaceSuffix
     * @return object
     */
    public static function getInstance($className, $params = [], $spaceSuffix = null)
    {
        if (!isset(static::$container[static::parseSpace($spaceSuffix)])) {
            static::$container[static::parseSpace($spaceSuffix)] = [];
        }
        $keyType = 'instance';
        if (array_key_exists(static::parseName($keyType, $className), static::$container[static::parseSpace($spaceSuffix)])) {
            return static::$container[static::parseSpace($spaceSuffix)][static::parseName($keyType, $className)];
        } else {
            $instance = static::buildInstance($className, $params);
            static::setInstance($className, $instance, $spaceSuffix);
            return $instance;
        }
    }

    /**
     * 拼装命名空间
     * @param string $spaceSuffix
     * @return string
     */
    protected static function parseSpace($spaceSuffix)
    {
        if (!empty($spaceSuffix)) {
            return sprintf('%s-%s', static::$space, $spaceSuffix);
        } else {
            return static::$space;
        }
    }

    /**
     * 解析名称
     * @param string $keyType
     * @param string $key
     * @return string
     */
    protected static function parseName($keyType, $key)
    {
        return sprintf('%s-%s', $keyType, $key);
    }

    /**
     * 创建实例
     * @param string $className
     * @param array $params
     * @return object
     */
    protected static function buildInstance($className, $params = [])
    {
        $class = new \ReflectionClass($className);
        return $class->newInstanceArgs($params);
    }

}