<?php
//基本信息
Route::apiResource('/infos', '\App\Api\Controllers\Commons\BasicInfoController');
//文章
Route::post('/articles/search', '\App\Api\Controllers\Commons\ArticleController@search');    
Route::apiResource('/articles', '\App\Api\Controllers\Commons\ArticleController');
//文章分类
Route::apiResource('/article_cates', '\App\Api\Controllers\Commons\ArticleCateController');
//评论
Route::apiResource('/comments', '\App\Api\Controllers\Commons\CommentController');
//产品分类
Route::get('/product_cates/{pid}/pid', '\App\Api\Controllers\Commons\ProductCateController@getCateByPid');
Route::apiResource('/product_cates', '\App\Api\Controllers\Commons\ProductCateController');
//产品
Route::apiResource('/products', '\App\Api\Controllers\Commons\ProductController');
//活动
Route::get('/activitys/{activity}/signlist', '\App\Api\Controllers\Commons\ActivityController@signlist');
Route::apiResource('/activitys', '\App\Api\Controllers\Commons\ActivityController');
//其他::建议
Route::apiResource('/suggests', '\App\Api\Controllers\Commons\SuggestController');    
//其他::轮播图
Route::apiResource('/swipers', '\App\Api\Controllers\Commons\SwiperController');   
//其他::轮播图组
Route::apiResource('/swiper_groups', '\App\Api\Controllers\Commons\SwiperGroupController'); 
//专题  
Route::apiResource('/topics', '\App\Api\Controllers\Commons\TopicController');  
//相册
Route::apiResource('/photos', '\App\Api\Controllers\Commons\PhotoController');   
//粉丝
Route::apiResource('/fans', '\App\Api\Controllers\Commons\FanController', ['only' => [
    'index', 'show'
]]);  


//范文
Route::get('/templetsCombox','\App\Api\Controllers\Commons\TempletController@templetCombox');
Route::apiResource('/templets', '\App\Api\Controllers\Commons\TempletController');
