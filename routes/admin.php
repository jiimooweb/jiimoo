<?php


Route::group(['prefix'=>'api'],function (){

    Route::group(['middleware'=>'auth:admins'],function (){
    });
    Route::group(['prefix'=>'xcx'],function (){
        Route::get('/show','\App\Api\Controllers\XcxController@index');
        Route::get('/create','\App\Api\Controllers\XcxController@create');
        Route::post('/create','\App\Api\Controllers\XcxController@store');
        Route::get('/check','\App\Api\Controllers\XcxController@checkCombo');
        Route::post('/check','\App\Api\Controllers\XcxController@storeCombo');
    });

    Route::group(['prefix'=>'module'],function (){
        Route::get('/list','\App\Api\Controllers\ModuleController@index');
        Route::get('/create','\App\Api\Controllers\ModuleController@create');
        Route::post('/create','\App\Api\Controllers\ModuleController@store');
    });

    Route::group(['prefix'=>'combo'],function (){
        Route::get('/list','\App\Api\Controllers\ComboControlle@index');
        Route::get('/create','\App\Api\Controllers\ComboControlle@create');
        Route::post('/create','\App\Api\Controllers\ComboControlle@store');
    });

    Route::resource('reservations', '\App\Api\Controllers\Resertvations\ResertvationController');
        
});

