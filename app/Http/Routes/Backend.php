<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\View;

Route::group(['prefix'=>'admin'],function(){
    Route::controller('auth', 'Admin\\AuthController');
    Route::controller('index','Admin\\IndexController');

    Route::group(['middleware'=>'auth.permission'],function(){
        //管理员管理
        Route::post('users/activate',['as'=>'admin.users.activate','uses'=>'Admin\\UsersController@activate']);
        Route::delete('users/batch_delete',['as'=>'admin.users.batch_delete','uses'=>'Admin\\UsersController@batchDestroy']);
        Route::resource('users', 'Admin\\UsersController');

        Route::resource('roles', 'Admin\\RolesController');

        //系统管理
        Route::resource('system', 'Admin\\SystemConfigsController');
        //Route::resource('columns', 'Admin\\ColumnsController');
    });

});

