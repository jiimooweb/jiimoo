<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Commons\Topic;
use App\Http\Requests\Commons\TopicRequest;

class TopicController extends Controller
{
    
    public function index() 
    {
        $topics = Topic::get();
        return response()->json(['status' => 'success', 'data' => $topics]);
    }

    public function show()
    {
        $topic = Topic::formatTopic(Topic::find(request()->topic));
        return response()->json(['status' => 'success', 'data' => $topic]);
    }

    public function store(TopicRequest $request) 
    {   
        $data = request()->all();
        
        if(Topic::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
        
    }

    public function update(TopicRequest $request) 
    {   
        $data = request()->all();
        
        if(Topic::where('id', $request->topic)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);   
        
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Topic::where('id', request()->topic)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
    }
}
