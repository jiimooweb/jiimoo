<?php

Route::group(['prefix' => 'members'], function () {
    //会员
    Route::get('/members/{member}/group/{group_id}', '\App\Api\Controllers\Members\MemberController@group');
    Route::post('/members/changeIntegral', '\App\Api\Controllers\Members\MemberController@changeIntegral');
    Route::post('/members/changeMoney', '\App\Api\Controllers\Members\MemberController@changeMoney');
    Route::post('/members/join', '\App\Api\Controllers\Members\MemberController@join');
    Route::post('/members/addTag', '\App\Api\Controllers\Members\MemberController@addTag');
    Route::delete('/members/deleteTag', '\App\Api\Controllers\Members\MemberController@deleteTag');
    Route::apiResource('/members', '\App\Api\Controllers\Members\MemberController');
    //会员组
    Route::get('/groups/{group_id}/default', '\App\Api\Controllers\Members\GroupController@default');
    Route::apiResource('/groups', '\App\Api\Controllers\Members\GroupController');
    //会员卡设置
    Route::apiResource('/settings', '\App\Api\Controllers\Members\SettingController');
    Route::apiResource('/tags', '\App\Api\Controllers\Members\TagController');
});

