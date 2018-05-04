<?php

Route::group(['prefix' => 'api/members'], function () {
    //会员
    Route::get('/members/{member}/group/{group_id}', '\App\Api\Controllers\Members\MemberController@group');
    Route::post('/members/join', '\App\Api\Controllers\Members\MemberController@join');
    Route::apiResource('/members', '\App\Api\Controllers\Members\MemberController');
    //会员组
    Route::get('/groups/{group_id}/default', '\App\Api\Controllers\Members\GroupController@default');
    Route::apiResource('/groups', '\App\Api\Controllers\Members\GroupController');


    Route::apiResource('/settings', '\App\Api\Controllers\Members\SettingController');
    
});

