<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Wechat\Template;
use App\Http\Requests\Commons\TemplateRequest;

class NoticeTemplateController extends Controller
{
    
    public function index() 
    {
        $templates = Template::get();
        return response()->json(['status' => 'success', 'data' => $templates]);
    }

    public function store(TemplateRequest $request) 
    {   
        $data = request()->all();
        
        if(Template::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
        
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Template::where('id', request()->notice_template)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
    }
}
