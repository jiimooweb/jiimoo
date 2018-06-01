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

    public function store(NoticeTemplateRequest $request) 
    {   
        $data = request()->all();
        
        if(NoticeTemplate::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
        
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(NoticeTemplate::where('xcx_id', request()->notice_template)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
    }
}
