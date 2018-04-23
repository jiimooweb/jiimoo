<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Article;
use App\Models\Displays\ArticleCate;
use App\Admin\Controllers\Controller;

class ArticleController extends Controller
{
    
    public function index() 
    {
        $articles = Article::orderBy('created_at','desc')->withCount(['comments'])->paginate(6);
        $articles->load('category');                
        // return view('admin.displays.article.index', compact('articles'));
        return response()->json($articles);
    }

    public function create() 
    {
        $cates = ArticleCate::all();
        return view('admin.displays.article.create', compact('cates'));
    }

    public function store() 
    {   
        $this->validate(request(),[
            'title' => 'required',
            'cate_id' => 'required',
            'author' => 'required',
            'content' => 'required',
        ]);

        $data = request([
            'title', 'cate_id', 'author', 'click', 'content'
        ]);
        
        Article::create($data);

        // return back();
    }

    public function show(Article $article)
    {
        // TODO: 待开发      
        $article->load('category');                
        
        return response()->json($article);
    }

    public function edit(Article $article) 
    {
        $cates = ArticleCate::all();        
        return view('admin.displays.article.edit',compact('article', 'cates'));
    }

    public function update(Article $article)
    {
        $this->validate(request(),[
            'title' => 'required',
            'cate_id' => 'required',
            'author' => 'required',
            'content' => 'required',
        ]);

        $data = request([
            'title', 'cate_id', 'author', 'click', 'content'
        ]);
        
        Article::where('id', $article->id)->update($data);

        return back();

    }

    public function destroy(Article $article)
    {
        // TODO:判断删除权限
        $article->delete();
        // return redirect('admin/displays/articles');
        return response()->json($article);
        
    }
}
