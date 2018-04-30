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

Route::group(['prefix' => '{client_type}/{xcx_flag}','middleware' => ['client','cors','token']],function () {
    include_once('admin.php');
    include_once('answer.php');
    include_once('display.php');
});

Route::get('admin/user','\App\Admin\Controllers\LoginController@index');
Route::post('admin/user/login','\App\Admin\Controllers\LoginController@login');

Route::post('admin/token', 'TokenController@getToken')->middleware(['cors']);

Route::group(['prefix' => 'wechat'], function() {
    Route::post('token/getToken', 'MiniProgramController@getToken');
    Route::post('token/verifyToken', 'MiniProgramController@verifyToken');
    Route::post('saveInfo', 'MiniProgramController@saveInfo')->middleware('token');
});
