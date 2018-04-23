<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Comment;
use App\Admin\Controllers\Controller;

class CommentController extends Controller
{
    
    public function index() 
    {
        $comments = Comment::orderBy('created_at','desc')->paginate(30);
        $comments->load('article');                
        return view('admin.displays.comment.index', compact('comments'));
    }


    public function delete(Article $article)
    {
        // TODO:判断删除权限
        $article->delete();
        return redirect('admin/displays/comments');
    }
}
