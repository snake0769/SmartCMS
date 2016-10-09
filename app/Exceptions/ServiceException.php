<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 14:39
 * Name:
 */

namespace App\Exceptions;


use Exception;

class ServiceException extends \Exception
{

    protected $called = "";

    public function __construct($array)
    {
        $called = $this->getCalledService();
        $message = $array['msg'];
        $code = $array['errcode'];

        if(!empty($called)){
            $headers = require_once __DIR__ . "/Error.php";
            $called = $this->getClassName($called);
            $code = strval($headers[$called]).$array['errcode'];
        }

        parent::__construct($message, $code, null);
    }


    /**
     * 获取实例最近的Service实例
     * @return string
     */
    public function getCalledService(){
        $trace = debug_backtrace();
        foreach($trace as $element){
            if($element !== null && strpos($element['class'],"Service") > 0){
                return $element['class'];
            }
        }
        return  "";
    }

    /**
     * 获取实例最近的Controller实例
     * @return string
     */
    public function getCalledController(){
        $trace = debug_backtrace();
        foreach($trace as $element){
            if($element !== null && strpos($element['class'],"Controller") > 0){
                return $element['class'];
            }
        }
        return  "";
    }

    /**
     * 获取类名
     * @param $class
     */
    private function getClassName($class){
        $classArr = explode("\\",$class);
        return end($classArr);
    }

}