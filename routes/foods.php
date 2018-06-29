<?php

Route::group(['prefix' => 'foods'], function () {
    //分类
    Route::apiResource('/cates', '\App\Api\Controllers\Foods\CateController');
    //商品
    Route::get('/products/list', '\App\Api\Controllers\Foods\ProductController@list');
    Route::apiResource('/products', '\App\Api\Controllers\Foods\ProductController');

    Route::post('/orders/init', '\App\Api\Controllers\Foods\OrderController@init');
    Route::post('/orders/commit', '\App\Api\Controllers\Foods\OrderController@commit');
    Route::apiResource('/orders', '\App\Api\Controllers\Foods\OrderController');
    
    Route::apiResource('/settings', '\App\Api\Controllers\Foods\SettingController', ['only' => ['index', 'store', 'update']]);

});

