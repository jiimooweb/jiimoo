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

Route::group(['prefix' => '{client_type}/{xcx_flag}'],function () {
    include_once('displays.php');
    include_once('coupons.php');
    include_once('admin.php');
    include_once('answer.php');
    
});

Route::group(['prefix'=>'user','middleware'=>['token']],function (){
    //登陆
    Route::get('/index','\App\Admin\Controllers\UserController@index');
    Route::post('/delete','\App\Admin\Controllers\UserController@delete');
    Route::post('/update','\App\Admin\Controllers\UserController@update');
    Route::get('/check_xcxs','\App\Admin\Controllers\UserController@checkXcx');
    Route::post('/update_xcxs','\App\Admin\Controllers\UserController@addXcx');
});

Route::get('admin/user','\App\Admin\Controllers\LoginController@index')->middleware(['cors']);
Route::post('admin/user/login','\App\Admin\Controllers\LoginController@login')->middleware(['cors']);

Route::get('admin/user/register','\App\Admin\Controllers\UserController@create');
Route::post('admin/user/register','\App\Admin\Controllers\UserController@store');

Route::post('admin/token', 'TokenController@getToken')->middleware(['cors']);

Route::group(['prefix' => 'wechat'], function() {
    Route::post('token/getToken', 'MiniProgramController@getToken');
    Route::post('token/verifyToken', 'MiniProgramController@verifyToken');
    Route::post('saveInfo', 'MiniProgramController@saveInfo')->middleware('token');
});

Route::get('/getQrCode', '\App\Admin\Controllers\MiniProgramController@getQrCode');
Route::get('/getMiniCode', '\App\Admin\Controllers\MiniProgramController@getMiniCode');
Route::get('/test', '\App\Admin\Controllers\MiniProgramController@test');
