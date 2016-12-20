<?php
/**
 * A helper file for Laravel 5, to provide autocomplete information to your IDE
 * Generated for Laravel 5.2.45 on 2016-12-06.
 *
 * @author huzl
 */

namespace {
    exit("This file should not be included, only analyzed by your IDE");

    class Map extends \App\Foundation\Facades\Map{

        /**
         * 设置类
         * @param string $key
         * @param string $className
         * @param string $spaceSuffix
         * @return null
         */
        public static function setClass($key, $className, $spaceSuffix = null){
            parent::setClass($key, $className, $spaceSuffix);
        }

        /**
         * 获取类
         * @param string $className
         * @param string $spaceSuffix
         * @return string
         */
        public static function getClass($className, $spaceSuffix = null)
        {
            return parent::getClass($className, $spaceSuffix);
        }

        /**
         * 设置实例
         * @param string $key
         * @param object $instance
         * @param string $spaceSuffix
         */
        public static function setInstance($key, $instance, $spaceSuffix = null)
        {
            parent::setInstance($key, $instance, $spaceSuffix);
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
            parent::setInstanceByClassName($key, $className, $classParams, $spaceSuffix);
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
            return parent::getInstance($className, $params, $spaceSuffix);
        }
        
    }



}

