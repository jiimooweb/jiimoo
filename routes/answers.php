<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/25
 * Time: 11:28
 */
Route::group(['prefix'=>'api/answer'],function (){
    Route::group(['prefix'=>'cash'],function (){
        Route::get('/cash/create','\App\Api\Controllers\Answers\CashController@create');
        Route::post('/cash/store','\App\Api\Controllers\Answers\CashController@store');
        Route::get('/cash/edit','\App\Api\Controllers\Answers\CashController@edit');
        Route::post('/cash/update','\App\Api\Controllers\Answers\CashController@update');
    });
    Route::group(['prefix'=>'activity'],function (){
        Route::get('/index','\App\Api\Controllers\Answers\ActivityController@index');
        Route::get('/create','\App\Api\Controllers\Answers\ActivityController@create');
        Route::post('/store','\App\Api\Controllers\Answers\ActivityController@store');
        Route::get('/{activity}/edit','\App\Api\Controllers\Answers\ActivityController@edit');
        Route::post('/{activity}/update','\App\Api\Controllers\Answers\ActivityController@update');
    });
    Route::group(['prefix'=>'depot'],function(){
        Route::get('/index','\App\Api\Controllers\Answers\DepotController@index');
        Route::get('/create','\App\Api\Controllers\Answers\DepotController@create');
        Route::post('/store','\App\Api\Controllers\Answers\DepotController@store');
        Route::get('/edit','\App\Api\Controllers\Answers\DepotController@edit');
        Route::post('/update','\App\Api\Controllers\Answers\DepotController@update');
    });
    Route::group(['prefix'=>'question'],function(){
        Route::get('/index','\App\Api\Controllers\Answers\QuestionController@index');
        Route::get('/create','\App\Api\Controllers\Answers\QuestionController@create');
        Route::post('/store','\App\Api\Controllers\Answers\QuestionController@store');
        Route::get('/edit','\App\Api\Controllers\Answers\QuestionController@edit');
        Route::post('/update','\App\Api\Controllers\Answers\QuestionController@update');
    });
    Route::get('/user/show','\App\Api\Controllers\Answers\UserController@index');
});
