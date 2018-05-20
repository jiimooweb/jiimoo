<?php


Route::group(['prefix' => 'wechat'], function () {

    Route::post('authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');    

    Route::get('user-authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@user_authorize');
    
    
});



