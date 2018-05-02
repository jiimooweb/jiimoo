<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/25
 * Time: 11:28
 */
Route::group(['prefix'=>'admin/answer'],function (){
    Route::group(['prefix'=>'cash'],function (){
        Route::get('/cash/create','\App\Admin\Controllers\Answers\CashController@create');
        Route::post('/cash/store','\App\Admin\Controllers\Answers\CashController@store');
        Route::get('/cash/edit','\App\Admin\Controllers\Answers\CashController@edit');
        Route::post('/cash/update','\App\Admin\Controllers\Answers\CashController@update');
    });
    Route::group(['prefix'=>'activity'],function (){
        Route::get('/index','\App\Admin\Controllers\Answers\ActivityController@index');
        Route::get('/create','\App\Admin\Controllers\Answers\ActivityController@create');
        Route::post('/store','\App\Admin\Controllers\Answers\ActivityController@store');
        Route::get('/{activity}/edit','\App\Admin\Controllers\Answers\ActivityController@edit');
        Route::post('/{activity}/update','\App\Admin\Controllers\Answers\ActivityController@update');
    });
    Route::group(['prefix'=>'depot'],function(){
        Route::get('/index','\App\Admin\Controllers\Answers\DepotController@index');
        Route::get('/create','\App\Admin\Controllers\Answers\DepotController@create');
        Route::post('/store','\App\Admin\Controllers\Answers\DepotController@store');
        Route::get('/edit','\App\Admin\Controllers\Answers\DepotController@edit');
        Route::post('/update','\App\Admin\Controllers\Answers\DepotController@update');
    });
    Route::group(['prefix'=>'question'],function(){
        Route::get('/index','\App\Admin\Controllers\Answers\QuestionController@index');
        Route::get('/create','\App\Admin\Controllers\Answers\QuestionController@create');
        Route::post('/store','\App\Admin\Controllers\Answers\QuestionController@store');
        Route::get('/edit','\App\Admin\Controllers\Answers\QuestionController@edit');
        Route::post('/update','\App\Admin\Controllers\Answers\QuestionController@update');
    });
    Route::get('/user/show','\App\Admin\Controllers\Answers\UserController@index');
});
