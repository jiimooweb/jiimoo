<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/25
 * Time: 11:28
 */
Route::group(['prefix'=>'answer'],function (){
    Route::post('activity/choice_depot','\App\Api\Controllers\Answers\ActivityController@choiceDepot');
    Route::post('question/randomQus','\App\Api\Controllers\Answers\QuestionController@randomQus');
    Route::apiResource('cash','\App\Api\Controllers\Answers\CashController');
    Route::apiResource('activitys','\App\Api\Controllers\Answers\ActivityController');
    Route::apiResource('depots','\App\Api\Controllers\Answers\DepotController');
    Route::apiResource('questions','\App\Api\Controllers\Answers\QuestionController');
    Route::apiResource('fans','\App\Api\Controllers\Answers\FanController');
});
