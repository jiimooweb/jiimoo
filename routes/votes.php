<?php

Route::group(['prefix'=>'votes'],function (){

    Route::post('/{voteID}/vote', '\App\Api\Controllers\Votes\VoteInfoController@doVote');
    Route::get('/new','\App\Api\Controllers\Votes\VoteInfoController@getNweVote');
    Route::post('/other','\App\Api\Controllers\Votes\VoteInfoController@getOther');
    //总投票基本信息
    Route::apiResource('/infos','\App\Api\Controllers\Votes\VoteInfoController');
    //选手信息
    Route::get('/{voteID}/mine', '\App\Api\Controllers\Votes\ApplicantInfoController@getMine');
    Route::post('/search', '\App\Api\Controllers\Votes\ApplicantInfoController@doSearch');
    Route::post('/{voteID}/applicants', '\App\Api\Controllers\Votes\ApplicantInfoController@index');
    Route::post('/{voteID}/list', '\App\Api\Controllers\Votes\ApplicantInfoController@list');
    Route::post('/applicants/audited', '\App\Api\Controllers\Votes\ApplicantInfoController@doAudited');
    Route::apiResource('/applicants','\App\Api\Controllers\Votes\ApplicantInfoController');
    //选项信息
    Route::post('/{voteID}/options', '\App\Api\Controllers\Votes\OptionController@index');
    Route::post('/options', '\App\Api\Controllers\Votes\OptionController@store');
});