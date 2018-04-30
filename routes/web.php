<?php

include_once('admin.php');
include_once('answer.php');
include_once('display.php');
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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/token', 'TokenController@getToken')->middleware(['cors']);


Route::post('/wechat/token/getToken', 'MiniProgramController@getToken');
Route::post('/wechat/token/verifyToken', 'MiniProgramController@verifyToken');
Route::post('/wechat/saveInfo', 'MiniProgramController@saveInfo')->middleware('token');


Route::get('/hello', function () {
    return view('hello');
});