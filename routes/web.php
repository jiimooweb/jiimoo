<?php

// include_once('admin.php');
// include_once('answer.php');
// include_once('display.php');
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

Route::group(['prefix' => '{client_type}/{xcx_flag}','middleware'=>['token', 'client']], function () {
    include_once('displays.php');
    include_once('coupons.php');
    include_once('answers.php');
    include_once('members.php');
    Route::get('api/xcx/check','\App\Api\Controllers\XcxController@checkCombo');
    Route::post('api/xcx/check','\App\Api\Controllers\XcxController@storeCombo');
    
});

Route::get('login',function() {
    return '这是登录页';
});
Route::get('api/user','\App\Api\Controllers\LoginController@index')->middleware(['cors']);
Route::post('api/user/login','\App\Api\Controllers\LoginController@login')->middleware(['cors']);


include_once('admin.php');

Route::post('api/token', 'TokenController@getToken')->middleware(['cors']);

Route::group(['prefix' => 'wechat'], function() {
    Route::post('token/getToken', 'MiniProgramController@getToken');
    Route::post('token/verifyToken', 'MiniProgramController@verifyToken');
    Route::post('saveInfo', 'MiniProgramController@saveInfo')->middleware('token');
});

Route::get('/getQrCode', '\App\Api\Controllers\MiniProgramController@getQrCode');
Route::get('/getMiniCode', '\App\Api\Controllers\MiniProgramController@getMiniCode');
Route::get('/test', '\App\Api\Controllers\MiniProgramController@test');
