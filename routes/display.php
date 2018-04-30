<?php

Route::group(['prefix' => 'admin/displays', 'middleware' => ['cors', 'token']], function () {
    //基本信息
    Route::resource('/infos', '\App\Admin\Controllers\Displays\BasicInfoController');
    //文章
    Route::resource('/articles', '\App\Admin\Controllers\Displays\ArticleController');
    //文章分类
    Route::resource('/article_cates', '\App\Admin\Controllers\Displays\ArticleCateController', ['except' => ['show']]);
    //评论
    Route::get('/comments', '\App\Admin\Controllers\Displays\CommentController@index');
    Route::get('/comments/{comment}/destroy', '\App\Admin\Controllers\Displays\CommentController@destroy');
    //产品分类
    Route::resource('/product_cates', '\App\Admin\Controllers\Displays\ProductCateController', ['except' => ['show']]);
    //产品
    Route::resource('/products', '\App\Admin\Controllers\Displays\ProductController');
    //活动
    Route::resource('/activitys', '\App\Admin\Controllers\Displays\ActivityController');
    //其他::建议
    Route::get('/suggests', '\App\Admin\Controllers\Displays\SuggestController@index');    
    Route::get('/suggests/{suggest}/destroy', '\App\Admin\Controllers\Displays\SuggestController@destroy');
    //其他::轮播图
    Route::resource('/swipers', '\App\Admin\Controllers\Displays\SwiperController');   
    
});

