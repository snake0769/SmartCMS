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

Route::group(['prefix'=>'frontend'],function(){
    foreach(glob(app_path('Http\Routes\Frontend').'\*.php') as $file){
        require  $file;
    }

});
/*Route::group(['prefix'=>'admin'],function(){
    Route::controller('auth', "Admin\\AuthController");

    Route::get("/index",function(){

        View::addExtension('html', 'php');
        return view('admin.default.index');
    });

    Route::get("/welcome",function(){

        View::addExtension('html', 'php');
        return view('admin.default.index');
    });

});*/

