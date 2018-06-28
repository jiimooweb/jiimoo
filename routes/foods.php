<?php

Route::group(['prefix' => 'foods'], function () {
    //分类
    Route::apiResource('/cates', '\App\Api\Controllers\Foods\CateController');
    //商品
    Route::get('/products/list', '\App\Api\Controllers\Foods\ProductController@list');
    Route::apiResource('/products', '\App\Api\Controllers\Foods\ProductController');

});

