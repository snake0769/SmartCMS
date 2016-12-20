<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/12
 * Time: 18:15
 */

Route::group(['middleware'=>'auth.permission'],function(){
    Route::resource('system', 'Admin\\SystemConfigsController');
});