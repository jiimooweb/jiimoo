<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\ArticleCate;
use App\Admin\Controllers\Controller;

class ArticleCateController extends Controller
{
    
    public function index() 
    {
        $articleCates = ArticleCate::get();
        return view('admin.displays.article_cate.index', compact('articleCates'));
    }

    public function create() 
    {
        return view('admin.displays.article_cate.create');
    }

    public function store() 
    {   
        $this->validate(request(),[
            'name' => 'required|min:2|max:6',
        ]);
        
        ArticleCate::create(request(['name']));

        return back();
    }

    public function edit(ArticleCate $articleCate) 
    {
        return view('admin.displays.article_cate.edit', compact('articleCate'));
    }

    public function update(ArticleCate $articleCate) 
    {
        $this->validate(request(),[
            'name' => 'required|min:2|max:6',
        ]);
        
        ArticleCate::where('id', $articleCate->id)->update(request(['name']));

        return back();
    }

    public function delete(ArticleCate $articleCate)
    {   
        // TODO:判断删除权限
        $articleCate->delete();
        return redirect('admin/displays/article_cates');
    }
}
