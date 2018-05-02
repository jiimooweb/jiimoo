<?php

Route::group(['prefix' => 'admin/coupons'], function () {
    //优惠券
    Route::apiResource('/coupons', '\App\Admin\Controllers\Coupons\CouponController');
    //优惠券记录
    Route::apiResource('/records', '\App\Admin\Controllers\Coupons\RecordController');
      
    
});

