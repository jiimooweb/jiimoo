<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\BasicInfo;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\BasicInfoRequest;

class BasicInfoController extends Controller
{
    
    public function index() 
    {
        $info = BasicInfo::first();
        return response()->json(['status' => 'success', 'data' => $info]);
    }

    public function store(BasicInfoRequest $request) 
    {   
        $data = request([
            'name', 'tel', 'address', 'intro', 'desc', 'logo'
        ]);
        
        if(BasicInfo::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
        
    }

    public function show()
    {
        $info = BasicInfo::find(request()->info);
        $status = $info ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $info]);     
    }

    public function update(BasicInfoRequest $request) 
    {
        // TODO:判断更新权限
        $data = request([
            'name', 'tel', 'address', 'intro', 'desc', 'logo'
        ]);
        
        if(BasicInfo::where('id', request()->info)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);    
        }
        
        return response()->json(['status' => 'error', 'msg' => '更新失败！']);    
        
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(BasicInfo::where('id', request()->info)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
        
    }
}
