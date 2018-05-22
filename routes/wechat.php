<?php


Route::group(['prefix' => 'wechat'], function () {
    Route::any('authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');    
    Route::any('authorized', '\App\Api\Controllers\Wechat\OpenPlatformController@authorized');    
    Route::any('user-authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@user_authorize');
    Route::get('bind-tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@bind_tester');
    Route::get('unbind-tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@unbind_tester');
});



