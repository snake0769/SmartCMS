<?php
/**
 * Created by PhpStorm.
 * User: huzl
 * Date: 2016/12/12
 * Time: 18:15
 */

Route::group(['middleware'=>'auth.permission'],function(){
    /*Route::post('users/activate',['as'=>'admin.users.activate','uses'=>'Admin\\UsersController@activate']);
    Route::delete('users/batch_delete',['as'=>'admin.users.batch_delete','uses'=>'Admin\\UsersController@batchDestroy']);
    Route::resource('users', 'Admin\\UsersController');*/
    Route::get('users/to_list',['as'=>'admin.users.search','uses'=>'Admin\\UsersController@toList']);
    Route::get('users/list',['as'=>'admin.users.search','uses'=>'Admin\\UsersController@getList']);
    Route::get('users/to_create',['as'=>'admin.users.create','uses'=>'Admin\\UsersController@toCreate']);
    Route::get('users/create',['as'=>'admin.users.store','uses'=>'Admin\\UsersController@create']);
    Route::get('users/to_edit',['as'=>'admin.users.search','uses'=>'Admin\\UsersController@toEdit']);
    Route::get('users/edit',['as'=>'admin.users.search','uses'=>'Admin\\UsersController@edit']);
    Route::get('users/delete',['as'=>'admin.users.search','uses'=>'Admin\\UsersController@delete']);
    Route::get('users/batch-delete',['as'=>'admin.users.search','uses'=>'Admin\\UsersController@batchDelete']);

    Route::get('users/test',['as'=>'admin.users.test','uses'=>'Admin\\UsersController@test']);
});