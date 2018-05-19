<?php


Route::group(['prefix' => 'wechat'], function () {

    Route::get('user_authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@openPlatform');
    
    Route::get('authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@eventAuthorize');
    
});



