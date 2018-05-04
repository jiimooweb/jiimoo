<?php

namespace App\Api\Controllers\Members;

use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Members\Setting;
use App\Api\Controllers\Controller;
use App\Http\Requests\Members\SettingRequest;

class SettingController extends Controller
{
    
    public function index() 
    {
        $settings = Setting::get();
        return response()->json(['status' => 'success', 'data' => $settings]);   
    }

    public function store(SettingRequest $request) 
    {   
        $data = request()->all();  
        if(Setting::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $group = Setting::find(request()->group);
        $status = $group ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $group]);   
    }

    public function update(SettingRequest $request)
    {
        $data = request()->all();                      
        if(Setting::where('id', request()->group)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Setting::where('id', request()->group)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

    

}
