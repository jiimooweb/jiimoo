<?php

namespace App\Api\Controllers\Members;

use Illuminate\Http\Request;
use App\Models\Members\Tag;
use App\Api\Controllers\Controller;
use App\Http\Requests\Members\TagRequest;

class TagController extends Controller
{
    
    public function index() 
    {
        $tags = Tag::get();
        return response()->json(['status' => 'success', 'data' => $tags]);   
    }

    public function store(TagRequest $request) 
    {   
        $data = request()->all();  
        if(Tag::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
    }

    public function show()
    {
        $tag = Tag::find(request()->tag);
        $status = $tag ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $tag]);   
    }

    public function update(TagRequest $request)
    {
        $data = request()->all();                      
        if(Tag::where('id', request()->tag)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Tag::where('id', request()->tag)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }
    

}
