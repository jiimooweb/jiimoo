<?php
// const resertvationController = '\App\Api\Controllers\Resertvations\resertvationController';S
Route::group(['prefix' => 'booking'], function () {

    Route::apiResource('/settings', '\App\Api\Controllers\Booking\SettingController', ['only' => ['index', 'store', 'update']]);

    //分类
    Route::get('/{categoryId}/products', '\App\Api\Controllers\Booking\CategoriesController@currentCategoryList');  

    Route::apiResource('/categories', '\App\Api\Controllers\Booking\CategoriesController');

     //商品
     Route::get('/products/list', '\App\Api\Controllers\Booking\ProductController@list'); 
     Route::apiResource('/products', '\App\Api\Controllers\Booking\ProductController');
});
