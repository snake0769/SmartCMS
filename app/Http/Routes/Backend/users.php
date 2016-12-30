<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/12
 * Time: 18:15
 */
Route::group(['middleware'=>'auth.permission'],function(){

    Route::get('users/to_list',['uses'=>'Admin\\UsersController@toList']);

    Route::get('users/list',['uses'=>'Admin\\UsersController@getList']);

    Route::get('users/to_create',['uses'=>'Admin\\UsersController@toCreate']);

    Route::post('users/create',['uses'=>'Admin\\UsersController@create']);

    Route::get('users/to_edit',['uses'=>'Admin\\UsersController@toEdit']);

    Route::post('users/edit',['uses'=>'Admin\\UsersController@edit']);

    Route::get('users/delete',['uses'=>'Admin\\UsersController@delete']);

    Route::get('users/batch-delete',['uses'=>'Admin\\UsersController@batchDelete']);

    Route::get('users/activate',['uses'=>'Admin\\UsersController@activate']);

    Route::get('users/to_reset_password',['uses'=>'Admin\\UsersController@toResetPassword']);

    Route::post('users/reset_password',['uses'=>'Admin\\UsersController@resetPassword']);

    Route::get('users/to_change_password',['uses'=>'Admin\\UsersController@toChangePassword']);

    Route::post('users/change_password',['uses'=>'Admin\\UsersController@changePassword']);


});