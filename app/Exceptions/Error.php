<?php
/**
 * Created by PhpStorm.
 * User: Snake
 * Date: 2016/6/17
 * Time: 14:27
 * Name:
 */
namespace App\Exceptions;

class Error{

    //通用错误
    const RUNTIME_CLASS_NOT_FOUND = ['errcode'=>10000,'msg'=>'找不到指定类'];
    const BUSSINESS_PERMISSION_DENIED = ['errcode'=>11000,'msg'=>'权限不足'];

    //针对性业务错误
    const USER_DUMPLICATED_USERNAME = ['errcode'=>20000,'msg'=>'用户名已存在'];
    const USER_NOT_EXISTED = ['errcode'=>20001,'msg'=>'用户不存在'];
}

