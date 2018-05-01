<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\BasicInfo;
use App\Admin\Controllers\Controller;

class BasicInfoController extends Controller
{
    
    public function index() 
    {
        $info = BasicInfo::first();
        return response()->json(['status' => 'success', 'data' => $info]);
    }

    public function store() 
    {   
        $this->validate(request(),[
            'name' => 'required',
            'tel' => 'required',
            'address' => 'required',
            'logo' => 'required|file'
        ]);

        $data = request([
            'name', 'tel', 'address', 'intro', 'desc'
        ]);

        $data['logo'] = '/storage/'.request()->file('logo')->storePublicly(md5(time()));
        
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

    public function update() 
    {
        // TODO:判断更新权限
        
        $this->validate(request(),[
            'name' => 'required',
            'tel' => 'required',
            'address' => 'required',
            'logo' => 'required|file'
        ]);

        $data = request([
            'name', 'tel', 'address', 'intro', 'desc'
        ]);

        // $data['logo'] = '/storage/'.request()->file('logo')->storePublicly(md5(time()));
        
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
