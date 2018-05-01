<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\ProductCate;
use App\Admin\Controllers\Controller;

class ProductCateController extends Controller
{
    
    public function index() 
    {
        $productCates = ProductCate::get();
        return response()->json(['status' => 'success', 'data' => $productCates]);
        
    }

    public function store() 
    {   
        $this->validate(request(),[
            'name' => 'required|min:2|max:6',
        ]);
        
        if(ProductCate::create(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                           
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
    }

    public function update() 
    {
        $this->validate(request(),[
            'name' => 'required|min:2|max:6',
        ]);
        
        if(ProductCate::where('id', request()->productCate)->update(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                           
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        if(ProductCate::where('id', request()->productCate)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                           
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);
    }
}
