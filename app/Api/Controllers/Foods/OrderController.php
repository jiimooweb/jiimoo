<?php

namespace App\Api\Controllers\Foods;

use App\Services\Token;
use App\Utils\OrderStatus;
use App\Models\Foods\Order;
use App\Services\WechatPay;
use Illuminate\Http\Request;
use App\Models\Foods\Product;
use Illuminate\Support\Facades\DB;
use App\Api\Controllers\Controller;
use App\Models\Coupons\CouponRecord;
use App\Http\Requests\Foods\CateRequest;

class OrderController extends Controller
{
    
    public function index() 
    {
        $cates = Order::withCount('products')->get();
        
        return response()->json(['status' => 'success', 'data' => $cates]);
    }

    public function store(CateRequest $requset) 
    {   
        $cate = Order::create(request(['name']));
        if($cate) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $cate]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        $cate = Order::withCount('products')->find(request()->cate);             
        $status = $cate ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $cate]);
    }

    public function update(CateRequest $requset) 
    {
        if(Order::where('id', request()->cate)->update(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        if(Order::where('id', request()->cate)->delete()) {
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

    public function commit() 
    {
        $result = DB::transaction(function (){

            $products = request('products');
            $record_id = request('record_id') ?? 0;

            $order = new Order;
            $order->order_no = $order->generateOrderNo();
            $order->pay_way = request('pay_way');
            //TODO UID
            $order->fan_id = Token::getUid();
            $order->status = OrderStatus::UNPAID;
            $order->remark = request('remark');
            $order->save();

            $data = $order->calcOrderPrice($order->id, $products, $record_id);
            
            $order->coupon_offer = $data['coupon_offer'];
            $order->price = $data['price_total'];
            $order->save();
            
            if($order->pay_way == 0) {
                $order = $order->toArray();
                $order['body'] = '任意门微信支付';
                $order['openid'] = Token::getCurrentTokenVar('openid');
                $wechatPay = new WechatPay(config('notify.wechat.foods'));
                return array_merge($wechatPay->unify($order),['order_id' => $order['id']]);
            }

            return $order;

        }, 5);

        

        if($result) {

            return response()->json(['status' => 'success', 'data' => $result]);    
        }

        return response()->json(['status' => 'error', 'msg' => '出现未知错误，请重试']); 
    }
}
