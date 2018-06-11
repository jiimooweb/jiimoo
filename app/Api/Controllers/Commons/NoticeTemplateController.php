<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Commons\NoticeTemplate;
use App\Http\Requests\Commons\NoticeTemplateRequest;

class NoticeTemplateController extends Controller
{
    
    public function index() 
    {
        $templates = NoticeTemplate::get();
        return response()->json(['status' => 'success', 'data' => $templates]);
    }

    public function show()
    {
        $template = NoticeTemplate::find(request()->notice_template);
        return response()->json(['status' => 'success', 'data' => $template]);
    }

    public function store(NoticeTemplateRequest $request) 
    {   
        $data = request()->all();
        
        if(NoticeTemplate::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
        
    }

    public function update(NoticeTemplateRequest $request) 
    {   
        $data = request()->all();
        
        if(NoticeTemplate::where('id', $request->notice_template)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);   
        
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(NoticeTemplate::where('id', request()->notice_template)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
    }
}
