<?php

namespace App\Api\Controllers\Foods;

use App\Models\Foods\Cate;
use Illuminate\Http\Request;
use App\Models\Foods\Product;
use App\Models\Coupons\Coupon;
use App\Api\Controllers\Controller;
use App\Models\Coupons\CouponRecord;
use App\Http\Requests\Foods\CateRequest;

class OrderController extends Controller
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

    public function init() 
    {
        $carts = request('cart');
        $price_total = 0;
        foreach($carts as $cart) {
            $price = Product::find($cart['product_id']);
            $price_total += $price['c_price'] * $cart['count'];
        }

        $coupons = CouponRecord::getUserAccordCoupons(1 ,$price_total);

        return response()->json(['status' => 'success', 'price_total' => $price_total,'coupons' => $coupons]);     
    }
}
