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
        $info['images'] = json_decode($info['images'], true);
        return response()->json(['status' => 'success', 'data' => $info]);
    }

    public function store(BasicInfoRequest $request) 
    {   
        $data = request()->all();

        $data['images'] = isset($data['images']) ? json_encode($data['images'], JSON_UNESCAPED_SLASHES) : null; 

        if(BasicInfo::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
        
    }

    public function show()
    {
        $info = BasicInfo::find(request()->info);
        $info['images'] = json_decode($info['images'], true);
        $status = $info ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $info]);     
    }

    public function update(BasicInfoRequest $request) 
    {
        // TODO:判断更新权限
        $data = request()->all();

        $data['images'] = isset($data['images']) ? json_encode($data['images'], JSON_UNESCAPED_SLASHES) : null;       
        if(BasicInfo::where('xcx_id', request()->info)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);    
        }
        
        return response()->json(['status' => 'error', 'msg' => '更新失败！']);    
        
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(BasicInfo::where('xcx_id', request()->info)->delete()) {
            return response()->json(['status' => 'success','msg' => '删除成功！']); 
        }

        return response()->json(['status' => 'error','msg' => '删除失败！']); 
        
    }
}
