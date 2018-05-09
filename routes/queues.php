<?php
Route::get('/queues/test', '\App\Api\Controllers\Queues\QueueController@test');   
Route::post('/queues/{queue}/join', '\App\Api\Controllers\Queues\QueueController@join');   
Route::get('/queues/{fan}/call', '\App\Api\Controllers\Queues\QueueController@call');   
Route::get('/queues/{fan}/confirm', '\App\Api\Controllers\Queues\QueueController@confirm');   
Route::get('/queues/{fan}/cancel', '\App\Api\Controllers\Queues\QueueController@cancel');   
Route::apiResource('/queues', '\App\Api\Controllers\Queues\QueueController');   
