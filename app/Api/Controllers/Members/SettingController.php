<?php

namespace App\Api\Controllers\Members;

use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Members\Group;
use App\Models\Members\Setting;
use App\Api\Controllers\Controller;
use App\Http\Requests\Members\SettingRequest;

class SettingController extends Controller
{
    
    public function index() 
    {
        $setting = Setting::first()->toArray();
        $groups = Group::getGroup()->toArray();
        return response()->json(['status' => 'success', 'setting' => $setting, 'groups' => $groups]);   
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
        $array = [
            1 => ['full' => 100, 'reduction' => 5],
            2 => ['full' => 100, 'reduction' => 10],
            3 => ['full' => 100, 'reduction' => 15],
            4 => ['full' => 100, 'reduction' => 20],
        ];
        // dd(json_encode($array));
        $setting = Setting::find(request()->setting);
        $offer = json_decode($setting->offer,true);   
        dd($offer);
        $status = $setting ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $setting]);   
    }

    public function update(SettingRequest $request)
    {
        $data = request()->all();                      
        if(Setting::where('id', request()->setting)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Setting::where('id', request()->setting)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

    

}
