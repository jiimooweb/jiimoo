<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\SwiperGroup;
use App\Models\Commons\ProductCate;
use App\Api\Controllers\Controller;

class SwiperGroupController extends Controller
{
    
    public function index() 
    {
        $groups = SwiperGroup::orderBy('display','asc')->get();

        return response()->json(['status' => 'success', 'data' => $groups]);
    }

    public function store(SwiperGroupRequest $request) 
    {   
        $data = request()->all();

        if(SwiperGroup::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
    }

    public function show()
    {
        $group = SwiperGroup::find('id', request()->swiper_group);
        $status = $swiper ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $group]);
    }

    public function update(SwiperGroupRequest $request) 
    {
        // TODO:判断更新权限
        
        $data = request()->all();
        
        if(SwiperGroup::where('id', request()->swiper_group)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);   
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(SwiperGroup::where('id', request()->swiper_group)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);   
    }
}