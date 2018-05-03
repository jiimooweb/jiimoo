<?php

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
    include_once('admin.php');
    include_once('answers.php');
});

Route::group(['prefix'=>'api/user','middleware'=>['token']], function (){
    //登陆
    Route::get('/index','\App\Api\Controllers\UserController@index');
    Route::post('/delete','\App\Api\Controllers\UserController@delete');
    Route::post('/update','\App\Api\Controllers\UserController@update');
    Route::get('/check_xcxs','\App\Api\Controllers\UserController@checkXcx');
    Route::post('/update_xcxs','\App\Api\Controllers\UserController@addXcx');

    Route::group(['prefix'=>'module'], function (){
        Route::get('/index','\App\Api\Controllers\ModuleController@index');
        Route::post('/create','\App\Api\Controllers\ModuleController@store');
        Route::post('/delete','\App\Api\Controllers\ModuleController@delete');
    });

    Route::group(['prefix'=>'combo'], function (){
        Route::get('/index','\App\Api\Controllers\ComboControlle@index');
        Route::post('/create','\App\Api\Controllers\ComboControlle@store');
        Route::post('/update','\App\Api\Controllers\ComboControlle@update');
        Route::post('/delete','\App\Api\Controllers\ComboControlle@delete');
    });
});

Route::get('api/user','\App\Api\Controllers\LoginController@index')->middleware(['cors']);
Route::post('api/user/login','\App\Api\Controllers\LoginController@login')->middleware(['cors']);

Route::get('api/user/register','\App\Api\Controllers\UserController@create');
Route::post('api/user/register','\App\Api\Controllers\UserController@store');

Route::post('api/token', 'TokenController@getToken')->middleware(['cors']);

Route::group(['prefix' => 'wechat'], function() {
    Route::post('token/getToken', 'MiniProgramController@getToken');
    Route::post('token/verifyToken', 'MiniProgramController@verifyToken');
    Route::post('saveInfo', 'MiniProgramController@saveInfo')->middleware('token');
});

Route::get('/getQrCode', '\App\Api\Controllers\MiniProgramController@getQrCode');
Route::get('/getMiniCode', '\App\Api\Controllers\MiniProgramController@getMiniCode');
Route::get('/test', '\App\Api\Controllers\MiniProgramController@test');
