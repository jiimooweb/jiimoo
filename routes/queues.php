<?php
// Route::get('/queues/test', '\App\Api\Controllers\Queues\QueueController@test');   
Route::get('/queues/{queue}/add', '\App\Api\Controllers\Queues\QueueController@add');   
Route::get('/queues/{fan}/call', '\App\Api\Controllers\Queues\QueueController@call');   
Route::get('/queues/{fan}/confirm', '\App\Api\Controllers\Queues\QueueController@confirm');   
Route::get('/queues/{fan}/cancel', '\App\Api\Controllers\Queues\QueueController@cancel');   
Route::post('/queues/{queue}/join', '\App\Api\Controllers\Queues\QueueController@join');  //小程序
Route::apiResource('/queues', '\App\Api\Controllers\Queues\QueueController');   
