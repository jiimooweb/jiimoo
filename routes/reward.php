<?php

Route::group(['prefix' => 'rewards'], function () {

    Route::post('/orders/commit', '\App\Api\Controllers\Pay\RewardPayController@commit');


});

