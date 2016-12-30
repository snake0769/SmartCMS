<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/12
 * Time: 18:15
 */

Route::group(['middleware'=>'auth.permission'],function(){
    Route::get('roles/to_list',['uses'=>'Admin\\RolesController@toList']);

    Route::get('roles/list',['uses'=>'Admin\\RolesController@getList']);

    Route::get('roles/to_create',['uses'=>'Admin\\RolesController@toCreate']);

    Route::post('roles/create',['uses'=>'Admin\\RolesController@create']);

    Route::get('roles/to_edit',['uses'=>'Admin\\RolesController@toEdit']);

    Route::post('roles/edit',['uses'=>'Admin\\RolesController@edit']);

    Route::get('roles/delete',['uses'=>'Admin\\RolesController@delete']);

    Route::get('roles/batch-delete',['uses'=>'Admin\\RolesController@batchDelete']);

});