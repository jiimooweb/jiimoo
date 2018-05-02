<?php

Route::group(['prefix' => 'admin/coupons'], function () {
    //优惠券
    Route::apiResource('/coupons', '\App\Admin\Controllers\Coupons\CouponController');
      
    
});

