<?php

use Illuminate\Support\Facades\Redis;

include_once('admin.php');
include_once('qiniu.php');
include_once('wechat.php');

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '{client_type}/{xcx_flag}/api','middleware'=>['token', 'client', 'cors']], function () {
    include_once('displays.php');
    include_once('coupons.php');
    include_once('answers.php');
    include_once('members.php');
    include_once('commons.php');
    include_once('queues.php');
});

Route::get('login',function() {
    return '这是登录页';
});


Route::group(['prefix' => '{client_type}/{xcx_flag}/api/wechat','middleware'=>['client', 'cors']], function() {
    Route::post('token/getToken', '\App\Api\Controllers\Wechat\MiniProgramController@getToken');
    Route::post('token/verifyToken', '\App\Api\Controllers\Wechat\MiniProgramController@verifyToken');
    Route::post('saveInfo', '\App\Api\Controllers\Wechat\MiniProgramController@saveInfo')->middleware('token');
});


Route::group(['prefix' => 'api','middleware'=>['cors']], function () {
    Route::get('user','\App\Api\Controllers\LoginController@index');
    Route::post('user/login','\App\Api\Controllers\LoginController@login');
    Route::post('token', 'TokenController@getToken');
    //消息模板
    Route::apiResource('notice_templates', '\App\Api\Controllers\Commons\NoticeTemplateController', ['only' => [
        'index', 'store', 'destroy'
    ]])->middleware(['token']);
});



Route::get('flash_token', function() {
    $token = request()->header('token');
    if(\App\Services\Token::verifyToken($token)) {
        cache([$token => cache($token)], config('token.token_expire_in'));
        return 'success';
    }
    return 'error';
});

Route::get('ticket', function() {
    return cache('component_verify_ticket');
});


Route::get('token','\App\Api\Controllers\Wechat\OpenPlatformController@token');

Route::get('/', function() {
    return view('welcome');
});




