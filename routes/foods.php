<?php

Route::group(['prefix' => 'foods'], function () {
    //分类
    Route::apiResource('/cates', '\App\Api\Controllers\Foods\CateController');
    //商品
    Route::get('/products/list', '\App\Api\Controllers\Foods\ProductController@list');
    Route::apiResource('/products', '\App\Api\Controllers\Foods\ProductController');

    Route::post('/orders/confirm', '\App\Api\Controllers\Foods\OrderController@confirm');
    Route::post('/orders/confirm_refund', '\App\Api\Controllers\Foods\OrderController@confirm_refund');

    Route::get('/orders/send', '\App\Api\Controllers\Foods\OrderController@send');
    Route::get('/orders/app_index', '\App\Api\Controllers\Foods\OrderController@app_index');
    Route::get('/orders/status_count', '\App\Api\Controllers\Foods\OrderController@status_count');
    Route::post('/orders/delete', '\App\Api\Controllers\Foods\OrderController@delete');
    Route::post('/orders/refund_order', '\App\Api\Controllers\Foods\OrderController@refund_order');
    Route::post('/orders/success', '\App\Api\Controllers\Foods\OrderController@success');
    Route::post('/orders/init', '\App\Api\Controllers\Foods\OrderController@init');
    Route::post('/orders/commit', '\App\Api\Controllers\Foods\OrderController@commit');
    Route::post('/orders/cancel_order', '\App\Api\Controllers\Foods\OrderController@cancel_order');
    Route::post('/orders/pay_order', '\App\Api\Controllers\Foods\OrderController@pay_order');
    Route::apiResource('/orders', '\App\Api\Controllers\Foods\OrderController');
    
    Route::apiResource('/settings', '\App\Api\Controllers\Foods\SettingController', ['only' => ['index', 'store', 'update']]);

    Route::get('/members/getuser', '\App\Api\Controllers\Foods\MemberController@getuser');
    Route::apiResource('/members', '\App\Api\Controllers\Foods\MemberController');
    
    Route::apiResource('/address', '\App\Api\Controllers\Foods\AddressController');

});

