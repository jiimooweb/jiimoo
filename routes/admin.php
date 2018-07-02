<?php


Route::group(['prefix'=>'api','middleware'=>['token']],function (){
    Route::get('user/userInfo','\App\Api\Controllers\UserController@userInfo');
    Route::get('user/register','\App\Api\Controllers\UserController@create');
    Route::post('user/register','\App\Api\Controllers\UserController@store');
    Route::get('user/choice_xcxs','\App\Api\Controllers\UserController@choiceXcx');
    Route::post('user/update_xcxs','\App\Api\Controllers\UserController@addXcx');
    Route::put('user/update_sort','\App\Api\Controllers\UserController@updateSort');
    Route::get('xcx/has/{xcx_flag}','\App\Api\Controllers\XcxController@hasCombo');
    Route::get('xcx/choice_user/{xcx_flag}','\App\Api\Controllers\XcxController@choiceUser');
    Route::post('xcx/choice_user/{xcx_flag}','\App\Api\Controllers\XcxController@updateUser');
    Route::get('xcx/choice/{xcx_flag}','\App\Api\Controllers\XcxController@choiceCombo');
    Route::post('xcx/choice/{xcx_flag}','\App\Api\Controllers\XcxController@storeCombo');
    Route::apiResource('user','\App\Api\Controllers\UserController');
    Route::apiResource('module','\App\Api\Controllers\ModuleController');
    Route::apiResource('combo','\App\Api\Controllers\ComboControlle');
    Route::apiResource('xcx','\App\Api\Controllers\XcxController');

    Route::apiResource('/templets','\App\Api\Controllers\Commons\AdminTempletController');
});

