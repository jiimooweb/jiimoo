<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Comment;
use App\Admin\Controllers\Controller;
use App\Http\Requests\Displays\CommentRequest;

class CommentController extends Controller
{
    
    public function index() 
    {
        $comments = Comment::orderBy('created_at','desc')->paginate(config('common.pagesize'));
        $comments->load('article');                
        return response()->json(['status' => 'success', 'data' => $comments]);
        
    }

    public function store(CommentRequest $request) 
    {
        if(Comment::create(request()->all())){
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                  
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);               
    }


    public function update(CommentRequest $request) 
    {
        if(Comment::where('id', request()->comment)->update(request()->all())){
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                  
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);               
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
