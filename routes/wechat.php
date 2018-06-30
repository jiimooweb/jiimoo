<?php

Route::any('wechat/{appid}/callback', '\App\Api\Controllers\Wechat\OpenPlatformController@callback');    
Route::any('wechat/{xcx_id?}/miniprogram', '\App\Api\Controllers\Wechat\OpenPlatformController@miniprogram');
Route::any('wechat/{xcx_id?}/save_miniprogram', '\App\Api\Controllers\Wechat\OpenPlatformController@save_miniprogram');
Route::any('wechat/authorize', '\App\Api\Controllers\Wechat\OpenPlatformController@event_authorize');    

Route::get('wechat/{xcx_id}/get_qrcode', '\App\Api\Controllers\Wechat\OpenPlatformController@get_qrcode')->middleware(['cors']);

Route::group(['prefix' => 'wechat/{xcx_id}', 'middleware' => ['token']], function () {
    //体验者
    Route::get('bind_tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@bind_tester');
    Route::get('unbind_tester/{wechatid}', '\App\Api\Controllers\Wechat\OpenPlatformController@unbind_tester');
    Route::get('get_testers', '\App\Api\Controllers\Wechat\OpenPlatformController@get_testers');
    //上传相关
    Route::get('commit/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@commit');
    Route::get('get_category', '\App\Api\Controllers\Wechat\OpenPlatformController@get_category');
    Route::get('get_page', '\App\Api\Controllers\Wechat\OpenPlatformController@get_page');
    //审核
    Route::get('get_audits', '\App\Api\Controllers\Wechat\OpenPlatformController@get_audits');
    Route::post('submit_audit', '\App\Api\Controllers\Wechat\OpenPlatformController@submit_audit');
    Route::get('get_auditstatus/{audit_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@get_auditstatus');
    Route::get('get_latest_auditstatus', '\App\Api\Controllers\Wechat\OpenPlatformController@get_latest_auditstatus');
    Route::get('undocodeaudit', '\App\Api\Controllers\Wechat\OpenPlatformController@undocodeaudit');
    
    //发布
    Route::get('release', '\App\Api\Controllers\Wechat\OpenPlatformController@release');
    Route::get('rollback_release', '\App\Api\Controllers\Wechat\OpenPlatformController@rollback_release');
    Route::get('change_visitstatus/{action}', '\App\Api\Controllers\Wechat\OpenPlatformController@change_visitstatus');
    //消息模板
    Route::get('get_notice_templates', '\App\Api\Controllers\Wechat\OpenPlatformController@get_notice_templates');
    Route::get('add_template/{id}', '\App\Api\Controllers\Wechat\OpenPlatformController@add_template');
 
    Route::get('template_list', '\App\Api\Controllers\Wechat\OpenPlatformController@template_list');
    Route::get('get_template/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@get_template');
    Route::get('get_templates', '\App\Api\Controllers\Wechat\OpenPlatformController@get_templates');
    Route::get('del_template/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@del_template');
    
    Route::get('get_webview', '\App\Api\Controllers\Wechat\OpenPlatformController@get_webview');
});

Route::group(['prefix' => 'wechat', 'middleware' => ['token']], function () {
    //代码模板管理
    Route::get('code_tpl_get_drafts', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_get_drafts');
    Route::get('code_tpl_create_from_draft/{draft_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_create_from_draft');
    Route::get('code_tpl_list', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_list');
    Route::get('code_tpl_delete/{template_id}', '\App\Api\Controllers\Wechat\OpenPlatformController@code_tpl_delete');
});


Route::any('wechat/{xcx_id}/user_author', '\App\Api\Controllers\Wechat\OpenPlatformController@user_author');   

Route::any('wechat/user', function() {
    return  'success';
});

Route::group(['prefix' => 'wechat/pay_notify', ], function () {
    Route::post('/foods', '\App\Api\Controllers\Pay\FoodPayController@notify');
});



