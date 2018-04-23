<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 9:10
 */
Route::group(['prefix'=>'admins'],function (){

    Route::group(['prefix'=>'user'],function (){
        //登陆
        Route::get('//login','\App\Admin\Controllers\UserController@loginIndex');
        Route::post('/login','\App\Admin\Controllers\UserController@login');
        Route::get('/logout','\App\Admin\Controllers\UserController@logout');
        //添加用户
        Route::get('/register','\App\Admin\Controllers\UserController@registerIndex');
        Route::post('/register','\App\Admin\Controllers\UserController@register');

        Route::get('/userlist','\App\Admin\Controllers\UserController@userLits');

        Route::post('/update','\App\Admin\Controllers\UserController@userUpdate');
    });
    Route::group(['prefix'=>'xcx'],function (){
        Route::get('/{communalUser}/list','\App\Admin\Controllers\XcxController@xcxList');
        Route::get('/create','\App\Admin\Controllers\XcxController@createIndex');
        Route::post('/create','\App\Admin\Controllers\XcxController@create');
        Route::get('/{xcx}/check','\App\Admin\Controllers\XcxController@checkCombo');
        Route::post('/{xcx}/check','\App\Admin\Controllers\XcxController@storeCombo');
    });

    Route::group(['prefix'=>'module'],function (){
        Route::get('/list','\App\Admin\Controllers\ModuleController@query');
        Route::get('/create','\App\Admin\Controllers\ModuleController@createIndex');
        Route::post('/create','\App\Admin\Controllers\ModuleController@create');
    });

    Route::group(['prefix'=>'combo'],function (){
        Route::get('/list','\App\Admin\Controllers\ComboControlle@query');
        Route::get('/create','\App\Admin\Controllers\ComboControlle@createIndex');
        Route::post('/create','\App\Admin\Controllers\ComboControlle@create');
    });

    Route::group(['middleware'=>'auth:admins'],function (){
        //登陆后访问
        Route::group(['middleware'=>'can:Admin'],function (){
            //是管理员
        });
    });
});