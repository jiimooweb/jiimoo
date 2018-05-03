<?php


Route::group(['prefix'=>'api','middleware'=>['token']],function (){
    Route::get('user/register','\App\Api\Controllers\UserController@create');
    Route::post('user/register','\App\Api\Controllers\UserController@store');
    Route::put('user/check_xcxs','\App\Api\Controllers\UserController@checkXcx');
    Route::post('user/update_xcxs','\App\Api\Controllers\UserController@addXcx');
    Route::apiResource('user','\App\Api\Controllers\UserController');
    Route::apiResource('module','\App\Api\Controllers\ModuleController');
    Route::apiResource('combo','\App\Api\Controllers\ComboControlle');
    Route::apiResource('xcx','\App\Api\Controllers\XcxController');
    Route::resource('reservations', '\App\Api\Controllers\Resertvations\ResertvationController');
        
});

