<?php

namespace App\Api\Controllers\Reservations;

use App\Models\Reservations\Setting;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Http\Requests\Reservations\SettingRequest;

class SettingController extends Controller
{
    
    public function index() 
    {
        $setting = Setting::first();

        return response()->json(['status' => 'success', 'data' => $setting]);
    }

    public function store(SettingRequest $requset) 
    {   
        $data = request()->all();
        
        $setting = Setting::create($data);

        if($setting) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $setting]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    

    public function update(SettingRequest $requset) 
    {
        $data = request()->all();
        $data['businessHours'] =json_encode( $data['businessHours']);
        if(Setting::where('id', request()->setting)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

}
