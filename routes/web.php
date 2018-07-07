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
    include_once('votes.php');
    include_once('displays.php');
    include_once('coupons.php');
    include_once('answers.php');
    include_once('members.php');
    include_once('commons.php');
    include_once('queues.php');
    include_once('lotteries.php');
    include_once('foods.php');
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
    Route::apiResource('notice_templates', '\App\Api\Controllers\Commons\NoticeTemplateController')->middleware(['token']);
    //小程序模板
    Route::apiResource('templates', '\App\Api\Controllers\Commons\TemplateController')->middleware(['token']);

    //www.rdoorweb.com/api/templates/{id}  GET/POST/DELETE
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
    return view('index');
});

Route::get('socket', function() {
    // 建立socket连接到内部推送端口
    $client = stream_socket_client('tcp://127.0.0.1:8150', $errno, $errmsg, 1);
    // 推送的数据，包含uid字段，表示是给这个uid推送
    $data = array('uid'=>'uid1', 'percent'=>'88%');
    // 发送数据，注意8150端口是Text协议的端口，Text协议需要在数据末尾加上换行符
    fwrite($client, json_encode($data)."\n");
    // 读取推送结果
    echo fread($client, 8192);

    fclose($client);
});

Route::get('/backend', function () {
    return view('backend');
});



