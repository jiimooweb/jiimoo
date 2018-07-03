<?php

namespace App\Api\Controllers\Foods;

use App\Services\Token;
use App\Utils\OrderStatus;
use App\Models\Foods\Order;
use App\Services\WechatPay;
use Illuminate\Http\Request;
use App\Models\Foods\Product;
use App\Models\Foods\OrderProduct;
use Illuminate\Support\Facades\DB;
use App\Api\Controllers\Controller;
use App\Models\Coupons\CouponRecord;
use App\Http\Requests\Foods\CateRequest;

class OrderController extends Controller
{
    
    public function index() 
    {
        $status = request()->status;
        $order_no = request()->order_no;
        $fan_id = request()->fan_id;
        
        $orders = Order::when($status >= -1, function($query) use ($status){
            return $query->where('status', $status);
        })->when($fan_id, function($query) use ($fan_id){
            return $query->where('fan_id', $fan_id)->whereNotIn('status',[-1]);
        })->when($order_no, function($query) use ($order_no){
            return $query->where('order_no', 'like', '%'.$order_no.'%');
        })->with(['products'])->orderBy('id', 'desc')->get();

        return response()->json(['status' => 'success', 'data' => $orders]);
    }

    
    public function show() 
    {
        $order = Order::find(request()->order);             
        $status = $order ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $order]);
    }

    public function update(CateRequest $requset) 
    {
        if(Order::where('id', request()->order)->update(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        $result = DB::transaction(function (){
            if(Order::where('id', request()->order)->delete()) {
                OrderProduct::where('order_id', request()->order)->delete();
            }
            return ['status' => 'success', 'msg' => '删除成功！'];               
        }, 5);

        return response()->json($result);               
       
        
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }

    //接单，确认订单
    public function confirm() 
    {
        if(Order::where('id', request()->id)->update(['status' => OrderStatus::CONFIRM])){
            return response()->json(['status' => 'success', 'msg' => '确认成功！']);         
        }

        return response()->json(['status' => 'error', 'msg' => '确认失败']);         
    }

    public function confirm_refund()
    {
        $order = Order::find(request()->id);
        if($order->pay == 0 && $order->status == OrderStatus::REFUND) {
            $wechatPay = new WechatPay(config('notify.wechat.foods'));
            $result = $wechatPay->refund($order);
            if($result['result_code'] == 'SUCCESS' && $result['return_msg'] == 'OK') {
                if($order->update(['status' => OrderStatus::REFUND_SUCCESS])){
                    return response()->json(['status' => 'success', 'msg' => '确认退款成功！']);         
                }
            }    
        }

        return response()->json(['status' => 'error', 'msg' => '确认退款失败！']);         
        
    }

    //——————小程序————————

    public function app_index() 
    {
        $status = request()->status;
        $fan_id = Token::getUid();
        
        $orders = Order::when($status >= 0, function($query) use ($status){
            return $query->where('status', $status);
        })->when($fan_id, function($query) use ($fan_id){
            return $query->where('fan_id', $fan_id)->whereNotIn('status',[-1]);
        })->where('del_status', 0)->orderBy('id', 'desc')->with(['products'])->get();

        return response()->json(['status' => 'success', 'data' => $orders]);
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
            $order->fan_id = Token::getUid();
            $order->status = OrderStatus::UNPAID;
            $order->remark = request('remark');
            $order->save();

            $data = $order->calcOrderPrice($order->id, $products, $record_id);
            
            $order->coupon_offer = $data['coupon_offer'];
            $order->price = $data['price_total'];
            $order->save();
            
            if($order->pay_way == 0) {

                $order->body = '任意门微信支付';
                $order->openid = Token::getCurrentTokenVar('openid');
                $wechatPay = new WechatPay(config('notify.wechat.foods'));

                //保存prepayid
                $payOrder = $wechatPay->unify($order);
                Order::where('id', $order->id)->update(['prepay_id' => $payOrder['prepay_id']]);
                //返回结果
                return array_merge($payOrder,['order_id' => $order->id]);  
            }

            return $order;
        }, 5);

        if($result) {
            return response()->json(['status' => 'success', 'data' => $result]);    
        }

        return response()->json(['status' => 'error', 'msg' => '出现未知错误，请重试']); 
    }

    public function status_count()
    {
        $result = Order::getStatusCount(Token::getUid());
        return response()->json(['status' => 'success', 'data' => $result]);    
    }

    public function cancel_order()
    {
        $order = Order::find(request()->id);
        if($order->pay_way == 0 && $order->status == OrderStatus::PAID) {
            $wechatPay = new WechatPay(config('notify.wechat.foods'));
            $result = $wechatPay->refund($order);
            if($result['result_code'] == 'SUCCESS' && $result['return_msg'] == 'OK') {
                if($order->update(['status' => OrderStatus::CANCEL])){
                    return response()->json(['status' => 'success', 'result' => $result]);         
                }
            }
        }
        return response()->json(['status' => 'error', 'msg' => '取消订单失败']);            
    }

    public function pay_order()
    {
        $order = Order::find(request()->id);
        $order->body = '任意门微信支付';
        $order->openid = Token::getCurrentTokenVar('openid');
        $wechatPay = new WechatPay(config('notify.wechat.foods'));
        //保存prepayid
        $payOrder = $wechatPay->unify($order);
        Order::where('id', $order->id)->update(['prepay_id' => $payOrder['prepay_id']]);
        //返回结果
        return array_merge($payOrder,['order_id' => $order->id]); 
    }

    public function refund_order()
    {
        if(Order::where('id', request()->id)->update(['status' => OrderStatus::REFUND, 'refund_reason' => request()->reason])){
            return response()->json(['status' => 'success', 'msg' => '申请退款成功！']);         
        }

        return response()->json(['status' => 'error', 'msg' => '申请退款失败！']);  
        
    }

    public function success() 
    {
        if(Order::where('id', request()->id)->update(['status' => OrderStatus::SUCCESS])){
            return response()->json(['status' => 'success', 'msg' => '确认成功！']);         
        }

        return response()->json(['status' => 'error', 'msg' => '确认失败！']); 
    }

    public function delete()
    {
        $order = Order::find(request()->id);
        if($order->status == 0) {
            $result = DB::transaction(function (){
                if($order->delete()) {
                    OrderProduct::where('order_id', request()->id)->delete();
                }
                return ['status' => 'success', 'msg' => '删除成功！'];               
            }, 5);
        }else {
            $order->update(['del_status' => 1]);
        }
        return response()->json(['status' => 'success']);            
    }

}
