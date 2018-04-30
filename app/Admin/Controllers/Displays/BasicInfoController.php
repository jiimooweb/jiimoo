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
        // return view('admin.displays.basic_info.index', compact('info'));
        return response()->json(['status' => 'success', 'data' => $info]);
    }

    public function create() 
    {
        return;
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
        
        BasicInfo::create($data);

        return response()->json(['status' => 'success', 'msg' => '新增成功！']);
    }

    public function show(BasicInfo $info)
    {
        return response()->json(['status' => 'success', 'data' => $info]);     
    }

    public function edit(BasicInfo $info) 
    {
        return response()->json(['status' => 'success', 'data' => $info]);   
    }

    public function update(BasicInfo $info) 
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

        $data['logo'] = '/storage/'.request()->file('logo')->storePublicly(md5(time()));
        
        BasicInfo::where('id', $info->id)->update($data);

        return response()->json(['status' => 'success', 'msg' => '更新成功！']);
    }

    public function destroy(BasicInfo $info)
    {
        // TODO:判断删除权限
        $info->delete();
        return response()->json(['status' => 'success','msg' => '删除成功']);
    }
}
