<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/12
 * Time: 18:15
 */

Route::group(['middleware'=>'auth.permission'],function(){

    Route::get('system/index',['uses'=>'Admin\\SystemConfigsController@index']);

    Route::post('system/edit',['uses'=>'Admin\\SystemConfigsController@edit']);

});