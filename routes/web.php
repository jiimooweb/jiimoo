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

Route::group(['prefix' => '{client_type}/{xcx_flag}/api','middleware'=>['token', 'client', 'cors']], function () {
    include_once('displays.php');
    include_once('coupons.php');
    include_once('answers.php');
    include_once('members.php');
    include_once('commons.php');
    include_once('queues.php');
    include_once('qiniu.php');
});

Route::get('login',function() {
    return '这是登录页';
});

Route::get('api/user','\App\Api\Controllers\LoginController@index')->middleware(['cors']);
Route::post('api/user/login','\App\Api\Controllers\LoginController@login')->middleware(['cors']);


include_once('admin.php');

Route::post('api/token', 'TokenController@getToken')->middleware(['cors']);

Route::group(['prefix' => '{client_type}/{xcx_flag}/api/wechat','middleware'=>['client', 'cors']], function() {
    Route::post('token/getToken', '\App\Api\Controllers\Wechat\MiniProgramController@getToken');
    Route::post('token/verifyToken', '\App\Api\Controllers\Wechat\MiniProgramController@verifyToken');
    Route::post('saveInfo', '\App\Api\Controllers\Wechat\MiniProgramController@saveInfo')->middleware('token');
});

Route::get('/getQrCode', '\App\Api\Controllers\MiniProgramController@getQrCode');
Route::get('/getMiniCode', '\App\Api\Controllers\MiniProgramController@getMiniCode');
Route::get('wechat/test', '\App\Api\Controllers\MiniProgramController@test');

