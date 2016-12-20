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
    Route::controller('home','Admin\\HomeController');

    foreach(glob(app_path('Http\Routes\Backend').'\*.php') as $file){
        require  $file;
    }

});

