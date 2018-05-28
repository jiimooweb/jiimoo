<?php

Route::any('wechat/{appid}/callback', '\App\Api\Controllers\Wechat\OpenPlatformController@callback');    
Route::any('wechat/miniprogram/{xcx_id?}', '\App\Api\Controllers\Wechat\OpenPlatformController@miniprogram');
Route::any('wechat/authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');    

Route::group(['prefix' => 'wechat', 'middleware' => ['token']], function () {
    //代码管理
    Route::get('bind-tester/{xcx_id}/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@bind_tester');
    Route::get('unbind-tester/{xcx_id}/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@unbind_tester');
    Route::get('commit/{xcx_id}/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@commit');
    Route::get('get_qrcode/{xcx_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@get_qrcode');
    Route::get('get_category/{xcx_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@get_category');
    Route::get('get_page/{xcx_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@get_page');
    Route::get('submit_audit/{xcx_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@submit_audit');
    //模板管理
    Route::get('code_tpl_get_drafts', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_get_drafts');
    Route::get('code_tpl_create_from_draft/{draft_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_create_from_draft');
    Route::get('code_tpl_list', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_list');
    Route::get('code_tpl_delete/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_delete');
});


Route::any('wechat/user_author', '\App\Api\Controllers\Wechat\OpenPlatformController@user_author');   

Route::any('wechat/user', function() {
    return  'success';
});



