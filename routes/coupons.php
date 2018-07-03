<?php

Route::group(['prefix' => 'coupons'], function () {
    //优惠券
    Route::apiResource('/coupons', '\App\Api\Controllers\Coupons\CouponController');
    //优惠券记录
    Route::get('/records/get_user_coupons', '\App\Api\Controllers\Coupons\RecordController@get_user_coupons');
    Route::apiResource('/records', '\App\Api\Controllers\Coupons\RecordController');
});

