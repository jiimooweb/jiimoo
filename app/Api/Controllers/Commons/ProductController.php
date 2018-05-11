<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\Product;
use App\Models\Commons\ProductCate;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\ProductRequest;

class ProductController extends Controller
{
    
    public function index() 
    {
        $products = Product::orderBy('created_at','desc')->paginate(config('common.pagesize'));
        $products->load('category');                
        return response()->json(['status' => 'success', 'data' => $products]);
    }

    public function store(ProductRequest $request) 
    {   
        $data = request()->all();

        // TODO:储存图片
        
        if(Product::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
    }

    public function show()
    {
        $product = Product::where('id', request()->product)->first();
        $status = $product ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $product]);
    }


    public function update(ProductRequest $request) 
    {
        // TODO:判断更新权限
        $data = request()->all();

        // TODO:储存图片
        
        if(Product::where('id', request()->product)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']); 
    }

    public function destroy()
    {
        // TODO:判断删除权限
        if(Product::where('id', request()->product)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);
    }
}