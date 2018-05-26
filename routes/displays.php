<?php

Route::group(['prefix' => 'displays'], function () {
    //基本信息
    Route::apiResource('/infos', '\App\Api\Controllers\Displays\BasicInfoController');
    //文章
    Route::post('/articles/search', '\App\Api\Controllers\Displays\ArticleController@search');    
    Route::apiResource('/articles', '\App\Api\Controllers\Displays\ArticleController');
    //文章分类
    Route::apiResource('/article_cates', '\App\Api\Controllers\Displays\ArticleCateController');
    //评论
    Route::apiResource('/comments', '\App\Api\Controllers\Displays\CommentController');
    //产品分类
    Route::get('/product_cates/{pid}/pid', '\App\Api\Controllers\Displays\ProductCateController@getCateByPid');
    Route::apiResource('/product_cates', '\App\Api\Controllers\Displays\ProductCateController');
    //产品
    Route::apiResource('/products', '\App\Api\Controllers\Displays\ProductController');
    //活动
    Route::apiResource('/activitys', '\App\Api\Controllers\Displays\ActivityController');
    //其他::建议
    Route::apiResource('/suggests', '\App\Api\Controllers\Displays\SuggestController');    
    //其他::轮播图
    Route::apiResource('/swipers', '\App\Api\Controllers\Displays\SwiperController');   

    //工种分类
    Route::post('/careers/show', '\App\Api\Controllers\Displays\CareerController@xcxShow');
    Route::apiResource('/careers', '\App\Api\Controllers\Displays\CareerController');
    //求职人员
    Route::post('/applicants/show_by_filter', '\App\Api\Controllers\Displays\ApplicantController@show_by_filter');
    //收藏
    Route::post('/applicants/collection', '\App\Api\Controllers\Displays\ApplicantController@collection');
    Route::post('/applicants/dis_collection', '\App\Api\Controllers\Displays\ApplicantController@disCollection');
    Route::post('/applicants/show_collection', '\App\Api\Controllers\Displays\ApplicantController@showCollection');

    Route::post('/applicants/add_click', '\App\Api\Controllers\Displays\ApplicantController@addClick');

    Route::apiResource('/applicants', '\App\Api\Controllers\Displays\ApplicantController');

});

