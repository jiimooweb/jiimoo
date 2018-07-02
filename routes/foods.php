<?php

Route::group(['prefix' => 'foods'], function () {
    //分类
    Route::apiResource('/cates', '\App\Api\Controllers\Foods\CateController');
    //商品
    Route::get('/products/list', '\App\Api\Controllers\Foods\ProductController@list');
    Route::apiResource('/products', '\App\Api\Controllers\Foods\ProductController');

    Route::get('/orders/app_index', '\App\Api\Controllers\Foods\OrderController@app_index');
    Route::post('/orders/init', '\App\Api\Controllers\Foods\OrderController@init');
    Route::post('/orders/commit', '\App\Api\Controllers\Foods\OrderController@commit');
    Route::get('/orders/status_count', '\App\Api\Controllers\Foods\OrderController@status_count');
    Route::post('/orders/change_status', '\App\Api\Controllers\Foods\OrderController@change_status');
    Route::apiResource('/orders', '\App\Api\Controllers\Foods\OrderController');
    
    Route::apiResource('/settings', '\App\Api\Controllers\Foods\SettingController', ['only' => ['index', 'store', 'update']]);

});

