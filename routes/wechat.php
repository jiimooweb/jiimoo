<?php


Route::group(['prefix' => 'wechat'], function () {
    Route::any('{appid}/callback', '\App\Api\Controllers\Wechat\OpenPlatformController@callback');    
    Route::any('authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');    
    Route::any('authorized', '\App\Api\Controllers\Wechat\OpenPlatformController@authorized');    
    Route::any('user-authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@user_authorize');
    Route::get('bind-tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@bind_tester');
    Route::get('unbind-tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@unbind_tester');
    Route::get('commit', '\App\Api\Controllers\Wechat\OpenPlatformController@commit');
    Route::get('get_qrcode', '\App\Api\Controllers\Wechat\OpenPlatformController@get_qrcode');
    Route::get('get_category', '\App\Api\Controllers\Wechat\OpenPlatformController@get_category');

    Route::get('code_tpl_get_drafts', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_get_drafts');
    Route::get('code_tpl_create_from_draft/{draft_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_create_from_draft');
    Route::get('code_tpl_list', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_list');
    Route::get('code_tpl_delete/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_delete');
});



