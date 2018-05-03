<?php

Route::group(['prefix' => 'api/coupons'], function () {
    //优惠券
    Route::apiResource('/coupons', '\App\Api\Controllers\Coupons\CouponController');
    //优惠券记录
    Route::apiResource('/records', '\App\Api\Controllers\Coupons\RecordController');
      
    
});

