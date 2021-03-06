<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\ArticleCate;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\ArticleCateRequest;

class ArticleCateController extends Controller
{
    
    public function index() 
    {
        $articleCates = ArticleCate::withCount('articles')->get();
        
        return response()->json(['status' => 'success', 'data' => $articleCates]);
    }

    public function store(ArticleCateRequest $requset) 
    {   
        $articleCate = ArticleCate::create(request(['name']));
        if($articleCate) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $articleCate]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        $cate = ArticleCate::withCount('articles')->find(request()->article_cate);             
        $status = $cate ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $cate]);
    }

    public function update(ArticleCateRequest $requset) 
    {
        if(ArticleCate::where('id', request()->article_cate)->update(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        if(ArticleCate::where('id', request()->article_cate)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        }
        
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }
}
