<?php


Route::group(['prefix' => 'wechat'], function () {

    Route::get('user-authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@user_authorize');
    
    Route::get('authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');
    
});



