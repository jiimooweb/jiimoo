<?php


Route::group(['prefix'=>'admin'],function (){

    Route::group(['prefix'=>'user'],function (){
        //登陆
        Route::get('/login','\App\Admin\Controllers\LoginController@index');
        Route::post('/login','\App\Admin\Controllers\LoginController@login');
        Route::get('/logout','\App\Admin\Controllers\LoginController@logout');
        //添加用户
        Route::get('/register','\App\Admin\Controllers\UserController@create');
        Route::post('/register','\App\Admin\Controllers\UserController@store');

        Route::get('/index','\App\Admin\Controllers\UserController@index');
        Route::post('/update','\App\Admin\Controllers\UserController@edit');
        Route::post('/update','\App\Admin\Controllers\UserController@update');
    });
    Route::group(['prefix'=>'xcx'],function (){
        Route::get('/{adminUser}/xcxs','\App\Admin\Controllers\XcxController@index');
        Route::get('/create','\App\Admin\Controllers\XcxController@create');
        Route::post('/create','\App\Admin\Controllers\XcxController@store');
        Route::get('/{xcx}/check','\App\Admin\Controllers\XcxController@checkCombo');
        Route::post('/{xcx}/check','\App\Admin\Controllers\XcxController@storeCombo');
    });

    Route::group(['prefix'=>'module'],function (){
        Route::get('/list','\App\Admin\Controllers\ModuleController@index');
        Route::get('/create','\App\Admin\Controllers\ModuleController@create');
        Route::post('/create','\App\Admin\Controllers\ModuleController@store');
    });

    Route::group(['prefix'=>'combo'],function (){
        Route::get('/list','\App\Admin\Controllers\ComboControlle@index');
        Route::get('/create','\App\Admin\Controllers\ComboControlle@create');
        Route::post('/create','\App\Admin\Controllers\ComboControlle@store');
    });

    Route::group(['middleware'=>'auth:admins'],function (){
        //登陆后访问
        Route::group(['middleware'=>'can:Admin'],function (){
            //是管理员
        });
    });
  
    Route::resource('reservations', '\App\Admin\Controllers\Resertvations\ResertvationController');
});

