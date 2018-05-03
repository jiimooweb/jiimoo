<?php

Route::group(['prefix' => 'api/members'], function () {
    //会员
    Route::post('/members/join', '\App\Api\Controllers\Members\MemberController@join');
    Route::apiResource('/members', '\App\Api\Controllers\Members\MemberController');
    
});

