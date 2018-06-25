<?php

Route::group(['prefix'=>'votes'],function (){
    //总投票基本信息
    Route::apiResource('/infos','\App\Api\Controllers\Votes\VoteInfoController');
    Route::post('/infos/voteOpt','\App\Api\Controllers\Votes\VoteInfoController@voteAndOptStore');

    //选手信息
    Route::post('/{voteID}/applicants', '\App\Api\Controllers\Votes\ApplicantInfoController@index');
    Route::post('/applicants/audited', '\App\Api\Controllers\Votes\ApplicantInfoController@doAudited');
    Route::apiResource('/applicants','\App\Api\Controllers\Votes\ApplicantInfoController');

    //选项信息
    Route::post('/{voteID}/options', '\App\Api\Controllers\Votes\OptionController@index');
    Route::post('/options', '\App\Api\Controllers\Votes\OptionController@store');
    Route::put('/options', '\App\Api\Controllers\Votes\OptionController@update');
    Route::post('/options/destroy','\App\Api\Controllers\Votes\OptionController@destroy');



});