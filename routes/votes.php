<?php

Route::group(['prefix'=>'votes'],function (){

    Route::get('/new','\App\Api\Controllers\Votes\VoteInfoController@getNweVote');
    //总投票基本信息
    Route::apiResource('/infos','\App\Api\Controllers\Votes\VoteInfoController');
    //选手信息
    Route::post('/{voteID}/applicants', '\App\Api\Controllers\Votes\ApplicantInfoController@index');
    Route::post('/{voteID}/list', '\App\Api\Controllers\Votes\ApplicantInfoController@list');
    Route::post('/applicants/audited', '\App\Api\Controllers\Votes\ApplicantInfoController@doAudited');
    Route::apiResource('/applicants','\App\Api\Controllers\Votes\ApplicantInfoController');
    //选项信息
    Route::post('/{voteID}/options', '\App\Api\Controllers\Votes\OptionController@index');
    Route::post('/options', '\App\Api\Controllers\Votes\OptionController@store');
});