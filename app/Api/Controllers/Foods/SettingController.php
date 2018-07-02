<?php

namespace App\Api\Controllers\Foods;

use App\Models\Foods\Setting;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Http\Requests\Foods\SettingRequest;

class SettingController extends Controller
{
    
    public function index() 
    {
        $setting = Setting::first();

        if(empty($setting)) {
            $setting['offer'] = isset($setting['offer']) ? json_decode($setting['offer']) : null;
        }
        
        return response()->json(['status' => 'success', 'data' => $setting]);
    }

    public function store(SettingRequest $requset) 
    {   
        $data = request()->all();
        $data['offer'] = isset($data['offer']) ? json_encode($data['offer']) : null;
        
        $setting = Setting::create($data);

        if($setting) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $setting]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    

    public function update(SettingRequest $requset) 
    {
        $data = request()->all();
        $data['offer'] = $data['offer'] ? json_encode($data['offer']) : null;

        if(Setting::where('id', request()->setting)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

}
