<?php

Route::group(['prefix' => 'lotteries'], function () {
    //优惠券
    Route::apiResource('/activities', '\App\Api\Controllers\Lotteries\ActivityController');
    //优惠券记录
    Route::apiResource('/prizes', '\App\Api\Controllers\Lotteries\PrizeController');
      
    
});

