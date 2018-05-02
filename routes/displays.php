<?php

Route::group(['prefix' => 'admin/displays'], function () {
    //基本信息
    Route::apiResource('/infos', '\App\Admin\Controllers\Displays\BasicInfoController');
    //文章
    Route::apiResource('/articles', '\App\Admin\Controllers\Displays\ArticleController');
    //文章分类
    Route::apiResource('/article_cates', '\App\Admin\Controllers\Displays\ArticleCateController', ['except' => ['show']]);
    //评论
    Route::apiResource('/comments', '\App\Admin\Controllers\Displays\CommentController');
    //产品分类
    Route::apiResource('/product_cates', '\App\Admin\Controllers\Displays\ProductCateController', ['except' => ['show']]);
    //产品
    Route::apiResource('/products', '\App\Admin\Controllers\Displays\ProductController');
    //活动
    Route::apiResource('/activitys', '\App\Admin\Controllers\Displays\ActivityController');
    //其他::建议
    Route::apiResource('/suggests', '\App\Admin\Controllers\Displays\SuggestController');    
    //其他::轮播图
    Route::apiResource('/swipers', '\App\Admin\Controllers\Displays\SwiperController');   
    
});

