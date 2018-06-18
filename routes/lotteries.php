<?php

Route::group(['prefix' => 'lotteries'], function () {


    Route::post('/get_result','\App\Api\Controllers\Lotteries\ActivityController@get_prize_result');
    Route::post('/get_prizes','\App\Api\Controllers\Lotteries\ActivityController@get_prizes');
    Route::get('/activities/show_by_filter','\App\Api\Controllers\Lotteries\ActivityController@show_by_filter');

    Route::apiResource('/activities', '\App\Api\Controllers\Lotteries\ActivityController');

    Route::apiResource('/prizes', '\App\Api\Controllers\Lotteries\PrizeController');
      
    
});

