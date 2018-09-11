<?php
const resertvationController = '\App\Api\Controllers\Reservations\resertvationController';
Route::group(['prefix'=>'reservation'],function (){

    // Route::apiResource('/list','\App\Api\Controllers\Reservations\ReservationController');
    // Route::resource('/list','\App\Api\Controllers\Reservations\ReservationController');
    Route::apiResource('/list','\App\Api\Controllers\Reservations\reservationController');

    //分类
    Route::apiResource('/cates', '\App\Api\Controllers\Reservations\CateController');
    
    Route::apiResource('/products', '\App\Api\Controllers\Reservations\ProductController');

    Route::apiResource('/settings', '\App\Api\Controllers\Reservations\SettingController', ['only' => ['index', 'store', 'update']]);

});