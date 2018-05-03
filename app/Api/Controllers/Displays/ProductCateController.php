<?php

namespace App\Api\Controllers\Displays;

use App\Utils\Common;
use Illuminate\Http\Request;
use App\Models\Displays\ProductCate;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\ProductCateRequest;

class ProductCateController extends Controller
{
    
    public function index() 
    {
        $productCates = ProductCate::get();
        $productCates = Common::getTree($productCates);
        return response()->json(['status' => 'success', 'data' => $productCates]);
        
    }

    public function store(ProductCateRequest $request) 
    {   
        if(ProductCate::create(request(['name','pid']))) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                           
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
    }

    public function show() 
    {
        $cate = ProductCate::find(request()->productCates);             
        $cate = $cate ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $cate]);
    }

    public function update(ProductCateRequest $request) 
    {
        if(ProductCate::where('id', request()->productCate)->update(request(['name', 'pid']))) {
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

    public function getCateByPid() 
    {
        $cates = ProductCate::where('pid', request()->pid)->get();
        return response()->json(['status' => 'success', 'data' => $cates]);
    }
}
