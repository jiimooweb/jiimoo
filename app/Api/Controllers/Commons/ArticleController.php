<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\Article;
use App\Models\Commons\ArticleCate;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\ArticleRequest;

class ArticleController extends Controller
{
    
    public function index(Request $request) 
    {
        $articles = Article::when($request->keyword, function($query) use ($request) {
            return $query->where('title', 'like', '%'.$request->keyword.'%');
        })->when($request->cate_id, function($query) use ($request) {
            return $query->where('cate_id', $request->cate_id);
        })->orderBy('created_at','desc')->withCount(['comments'])->paginate(config('common.pagesize'));
        $articles->load('category');
        
        return response()->json(['status' => 'success', 'data' => $articles]);
    }


    public function store() 
    {   
        $data = request([
            'title', 'cate_id', 'author', 'click', 'content', 'thumb'
        ]);
        
        if(Article::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                            
        }
            
        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                              
    }

    public function show()
    {
        // TODO: 待开发      
        $article = Article::find(request()->article);
        $article->load('category');                
        $status = $article ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $article]);
    }

    public function update()
    {
        $data = request([
            'title', 'cate_id', 'author', 'click', 'content', 'thumb'
        ]);
        
        if(Article::where('id', request()->article)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                  
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Article::where('id', request()->article)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);                        
    }
}
