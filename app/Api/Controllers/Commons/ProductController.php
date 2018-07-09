<?php

namespace App\Api\Controllers\Commons;

use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Commons\Product;
use App\Api\Controllers\Controller;
use App\Models\Commons\ProductCate;
use App\Models\Coupons\CouponRecord;
use App\Http\Requests\Commons\ProductRequest;

class ProductController extends Controller
{
    
    public function index(Request $request) 
    {
        $products = Product::when($request->keyword, function($query) use ($request) {
            return $query->where('name', 'like', '%'.$request->keyword.'%');
        })->when($request->cate_id, function($query) use ($request) {
            $cate_ids = (new ProductCate)->getChildrens($request->cate_id);
            $cate_ids[] = (int)$request->cate_id;
            return $query->whereIn('cate_id', $cate_ids);
        })->orderBy('created_at','desc')->with(['coupons' => function ($query) {
            $query->select('coupons.id', 'coupons.name')->get();
        }])->paginate(config('common.pagesize'));
        $products->load('category');                
        foreach($products as &$product) {
            $product->banner = json_decode($product->banner);
        }
        return response()->json(['status' => 'success', 'data' => $products]);
    }

    public function store(ProductRequest $request) 
    {   
        $data = request()->all();
        $data['banner'] = isset($data['banner']) ? json_encode($data['banner'], JSON_UNESCAPED_SLASHES) : null; 
        $coupons = $data['coupons'];
        unset($data['coupons']);
        $product = Product::create($data);
        if($product && Product::processCoupon($product->id, $coupons)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);   
    }

    public function show()
    {
        $coupons = null;

        if(request()->client_type == 'app') {
            $uid = Token::getUid();
            $coupons = CouponRecord::getUserHasCoupons($uid);
        }

        $product = Product::where('id', request()->product)->with(['coupons' => function ($query) use ($coupons){
            $query->when($coupons, function($query) use ($coupons) {
                return $query->whereIn('coupons.id', $coupons);
            })->select('coupons.id', 'coupons.name')->get();
        }])->first();
        $product->load('category');  
        $product['banner'] = json_decode($product['banner']);   
        $status = $product ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $product]);
    }

    public function update(ProductRequest $request) 
    {
        $data = request()->all();   

        $data['banner'] = isset($data['banner']) ? json_encode($data['banner'], JSON_UNESCAPED_SLASHES) : null; 
        $coupons = $data['coupons'];       
        unset($data['coupons']);
        
        $product = Product::where('id', request()->product)->update($data);
        if($product && Product::processCoupon(request()->product, $coupons)) {
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
