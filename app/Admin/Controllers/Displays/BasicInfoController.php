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
        return view('admin.displays.basic_info.index', compact('info'));
    }

    public function create() 
    {
        return view('admin.displays..basic_info.create');
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

        return back();
    }

    public function show(BasicInfo $info)
    {
        return view('admin.displays.basic_info.show',compact('info'));        
    }

    public function edit(BasicInfo $info) 
    {
        return view('admin.displays.basic_info.edit',compact('info'));        
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

        return back();
    }

    public function delete(BasicInfo $info)
    {
        // TODO:判断删除权限
        $info->delete();
        return redirect('admin/displays/infos');
    }
}
