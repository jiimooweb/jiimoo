<?php

namespace App\Api\Controllers\Foods;

use App\Models\Foods\Cate;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Http\Requests\Foods\CateRequest;

class CateController extends Controller
{
    
    public function index() 
    {
        $cates = Cate::withCount('products')->get();
        
        return response()->json(['status' => 'success', 'data' => $cates]);
    }

    public function store(CateRequest $requset) 
    {   
        $cate = Cate::create(request(['name']));
        if($cate) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $cate]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        $cate = Cate::withCount('products')->find(request()->cate);             
        $status = $cate ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $cate]);
    }

    public function update(CateRequest $requset) 
    {
        if(Cate::where('id', request()->cate)->update(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        if(Cate::where('id', request()->cate)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        }
        
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }
}
