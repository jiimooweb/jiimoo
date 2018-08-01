<?php
// const resertvationController = '\App\Api\Controllers\Resertvations\resertvationController';S
Route::group(['prefix' => 'booking'], function () {

    Route::apiResource('/settings', '\App\Api\Controllers\Booking\SettingController', ['only' => ['index', 'store', 'update']]);

    //分类
    Route::apiResource('/categories', '\App\Api\Controllers\Booking\CategoriesController');

    // https://www.sxl.cn/r/v1/sites/11040765/booking/categories
});
