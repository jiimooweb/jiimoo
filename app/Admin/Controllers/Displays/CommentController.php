<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Comment;
use App\Admin\Controllers\Controller;

class CommentController extends Controller
{
    
    public function index() 
    {
        $comments = Comment::orderBy('created_at','desc')->paginate(config('common.pagesize'));
        $comments->load('article');                
        return response()->json(['status' => 'success', 'data' => $comments]);
        
    }


    public function destroy()
    {
        // TODO:判断删除权限
        if(Comment::where('id', request()->comment)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);               
        
    }
}
