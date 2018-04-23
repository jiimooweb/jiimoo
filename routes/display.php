<?php

Route::group(['prefix' => 'admin/displays'], function () {
    //基本信息
    Route::resource('/infos', '\App\Admin\Controllers\Displays\BasicInfoController', ['names' => ['destroy' => 'infos.delete']]);
    Route::get('/infos/{info}/delete', '\App\Admin\Controllers\Displays\BasicInfoController@delete')->name('infos.delete');
    //文章
    Route::resource('/articles', '\App\Admin\Controllers\Displays\ArticleController', ['names' => ['destroy' => 'articles.delete']]);
    // Route::get('/articles/{article}/delete', '\App\Admin\Controllers\Displays\ArticleController@delete')->name('articles.delete');
    //文章分类
    Route::resource('/article_cates', '\App\Admin\Controllers\Displays\ArticleCateController', ['except' => ['show'], 'names' => ['destroy' => 'article_cates.delete']]);
    Route::get('/article_cates/{articleCate}/delete', '\App\Admin\Controllers\Displays\ArticleCateController@delete')->name('article_cates.delete');
    //评论
    Route::get('/comments', '\App\Admin\Controllers\Displays\CommentController@index');
    Route::get('/comments/{comment}/delete', '\App\Admin\Controllers\Displays\CommentController@delete');
    //产品分类
    Route::resource('/product_cates', '\App\Admin\Controllers\Displays\ProductCateController', ['except' => ['show'], 'names' => ['destroy' => 'product_cates.delete']]);
    Route::get('/product_cates/{productCate}/delete', '\App\Admin\Controllers\Displays\ProductCateController@delete')->name('product_cates.delete');
    //产品
    Route::resource('/products', '\App\Admin\Controllers\Displays\ProductController', ['names' => ['destroy' => 'products.delete']]);
    Route::get('/products/{product}/delete', '\App\Admin\Controllers\Displays\ProductController@delete')->name('products.delete');
    //活动
    Route::resource('/activitys', '\App\Admin\Controllers\Displays\ActivityController', ['names' => ['destroy' => 'activitys.delete']]);
    Route::get('/activitys/{activity}/delete', '\App\Admin\Controllers\Displays\ActivityController@delete')->name('activitys.delete');
    //其他::建议
    Route::get('/suggests', '\App\Admin\Controllers\Displays\SuggestController@index');    
    Route::get('/suggests/{suggest}/delete', '\App\Admin\Controllers\Displays\SuggestController@delete');
    //其他::轮播图
    Route::resource('/swipers', '\App\Admin\Controllers\Displays\SwiperController', ['names' => ['destroy' => 'swipers.delete']]);   
    Route::get('/swipers/{swiper}/delete', '\App\Admin\Controllers\Displays\SwiperController@delete')->name('swipers.delete'); 
    
});

