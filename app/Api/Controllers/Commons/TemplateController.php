<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Wechat\Template;
use App\Http\Requests\Commons\TemplateRequest;

class TemplateController extends Controller
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

    public function update(TemplateRequest $request) 
    {   
        $data = request()->all();
        
        if(Template::where('id', $request->template)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);   
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Template::where('id', request()->template)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
    }
}
