<?php

namespace App\Api\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Article;
use App\Models\Displays\ArticleCate;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\ArticleRequest;

class ArticleController extends Controller
{
    
    public function index() 
    {
        $articles = Article::orderBy('created_at','desc')->withCount(['comments'])->paginate(config('common.pagesize'));
        $articles->load('category');                
        foreach($articles as &$article) {
            $article['thumb'] = env('APP_URL').$article['thumb'];
        }

        return response()->json(['status' => 'success', 'data' => $articles]);
    }


    public function store(ArticleRequest $request) 
    {   
        $data = request([
            'title', 'cate_id', 'author', 'click', 'content'
        ]);

        $data['thumb'] = '/storage/'.request()->file('thumb')->storePublicly(md5(time()));
        
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

    public function update(ArticleRequest $request)
    {
        $data = request([
            'title', 'cate_id', 'author', 'click', 'content'
        ]);
        
        $data['thumb'] = '/storage/'.request()->file('thumb')->storePublicly(md5(time()));
        
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
