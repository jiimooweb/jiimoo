<?php

Route::group(['prefix' => 'api/displays'], function () {
    //基本信息
    Route::apiResource('/infos', '\App\Api\Controllers\Displays\BasicInfoController');
    //文章
    Route::apiResource('/articles', '\App\Api\Controllers\Displays\ArticleController');
    //文章分类
    Route::apiResource('/article_cates', '\App\Api\Controllers\Displays\ArticleCateController', ['except' => ['show']]);
    //评论
    Route::apiResource('/comments', '\App\Api\Controllers\Displays\CommentController');
    //产品分类
    Route::apiResource('/product_cates', '\App\Api\Controllers\Displays\ProductCateController', ['except' => ['show']]);
    //产品
    Route::apiResource('/products', '\App\Api\Controllers\Displays\ProductController');
    //活动
    Route::apiResource('/activitys', '\App\Api\Controllers\Displays\ActivityController');
    //其他::建议
    Route::apiResource('/suggests', '\App\Api\Controllers\Displays\SuggestController');    
    //其他::轮播图
    Route::apiResource('/swipers', '\App\Api\Controllers\Displays\SwiperController');   
    
});

