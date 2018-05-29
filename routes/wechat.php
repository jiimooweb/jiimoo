<?php

Route::any('wechat/{appid}/callback', '\App\Api\Controllers\Wechat\OpenPlatformController@callback');    
Route::any('wechat/miniprogram/{xcx_id?}', '\App\Api\Controllers\Wechat\OpenPlatformController@miniprogram');
Route::any('wechat/authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');    

Route::group(['prefix' => 'wechat', 'middleware' => ['token']], function () {
    //代码管理
    Route::get('{xcx_id}/bind-tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@bind_tester');
    Route::get('{xcx_id}/unbind-tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@unbind_tester');
    Route::get('{xcx_id}/commit/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@commit');
    Route::get('{xcx_id}/get_qrcode', '\App\Api\Controllers\Wechat\OpenPlatformController@get_qrcode');
    Route::get('{xcx_id}/get_category', '\App\Api\Controllers\Wechat\OpenPlatformController@get_category');
    Route::get('{xcx_id}/get_page', '\App\Api\Controllers\Wechat\OpenPlatformController@get_page');
    Route::get('{xcx_id}/submit_audit', '\App\Api\Controllers\Wechat\OpenPlatformController@submit_audit');
    Route::get('{xcx_id}/get_auditstatus/{audit_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@get_auditstatus');
    Route::get('{xcx_id}/get_latest_auditstatus', '\App\Api\Controllers\Wechat\OpenPlatformController@get_latest_auditstatus');
    Route::get('{xcx_id}/release', '\App\Api\Controllers\Wechat\OpenPlatformController@release');
    Route::get('{xcx_id}/undocodeaudit', '\App\Api\Controllers\Wechat\OpenPlatformController@undocodeaudit');
    Route::get('{xcx_id}/rollback_release', '\App\Api\Controllers\Wechat\OpenPlatformController@rollback_release');
    Route::get('{xcx_id}/change_visitstatus/{action}', '\App\Api\Controllers\Wechat\OpenPlatformController@change_visitstatus');

    //代码模板管理
    Route::get('code_tpl_get_drafts', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_get_drafts');
    Route::get('code_tpl_create_from_draft/{draft_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_create_from_draft');
    Route::get('code_tpl_list', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_list');
    Route::get('code_tpl_delete/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_delete');

    //消息模板
    Route::get('{xcx_id}/template_list', '\App\Api\Controllers\Wechat\OpenPlatformController@template_list');
    
});


Route::any('wechat/user_author', '\App\Api\Controllers\Wechat\OpenPlatformController@user_author');   

Route::any('wechat/user', function() {
    return  'success';
});



