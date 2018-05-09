<?php

use App\Models\Commons\Fan;
use Illuminate\Support\Facades\Cache;

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

Route::get('cache/{id}', function($id) {
    return Cache::get(Fan::find($id)->openid, '没有数据');
});

Route::get('test', function() {
   
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

Route::get('flash_token', function() {
    $token = request()->header('token');
    if(\App\Services\Token::verifyToken($token)) {
        cache([$token => cache($token)], config('token.token_expire_in'));
    }
});
