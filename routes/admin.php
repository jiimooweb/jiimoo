<?php


Route::group(['prefix'=>'admin'],function (){

    Route::group(['middleware'=>'auth:admins'],function (){
    });
    Route::group(['prefix'=>'xcx'],function (){
        Route::get('/show','\App\Admin\Controllers\XcxController@index');
        Route::get('/create','\App\Admin\Controllers\XcxController@create');
        Route::post('/create','\App\Admin\Controllers\XcxController@store');
        Route::get('/check','\App\Admin\Controllers\XcxController@checkCombo');
        Route::post('/check','\App\Admin\Controllers\XcxController@storeCombo');
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

    Route::resource('reservations', '\App\Admin\Controllers\Resertvations\ResertvationController');
        
});

