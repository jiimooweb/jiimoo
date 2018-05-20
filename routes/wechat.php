<?php


Route::group(['prefix' => 'wechat'], function () {

    Route::post('user-authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@user_authorize');
    
    Route::post('authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');
    
});



