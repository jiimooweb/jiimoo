<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/25
 * Time: 11:28
 */
Route::group(['prefix'=>'api/answer'],function (){
    Route::get('activity/choiceDepot','\App\Api\Controllers\Answers\ActivityController@choiceDepot');
    Route::apiResource('cash','\App\Api\Controllers\Answers\CashController');
    Route::apiResource('activity','\App\Api\Controllers\Answers\ActivityController');
    Route::apiResource('depot','\App\Api\Controllers\Answers\DepotController');
    Route::apiResource('question','\App\Api\Controllers\Answers\QuestionController');
    Route::apiResource('fan','\App\Api\Controllers\Answers\FanController');
});
