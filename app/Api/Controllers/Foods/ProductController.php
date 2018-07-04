<?php

namespace App\Api\Controllers\Foods;

use Illuminate\Http\Request;
use App\Models\Foods\Product;
use App\Api\Controllers\Controller;
use App\Models\Foods\Cate;
use App\Http\Requests\Foods\ProductRequest;

class ProductController extends Controller
{
    
    public function index(Request $request) 
    {
        $products = Product::when($request->keyword, function($query) use ($request) {
            return $query->where('name', 'like', '%'.$request->keyword.'%');
        })->when($request->cate_id, function($query) use ($request) {
            return $query->where('cate_id', $request->cate_id);
        })->get()->paginate(config('common.pagesize'));
        return response()->json(['status' => 'success', 'data' => $products]);
    }

    public function store(ProductRequest $request) 
    {   
        $data = request()->all();
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
        $data = request()->all();   
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

    public function list()
    {
        $cates = Cate::orderBy('id')->get();
        $products = Cate::with(['products' => function($query) {
            return $query->where('display', 1)->orderBy('c_price', 'asc');
        }])->orderBy('id')->get();
        return response()->json(['status' => 'success', 'cates' => $cates, 'products' => $products]);
    }



    
}
