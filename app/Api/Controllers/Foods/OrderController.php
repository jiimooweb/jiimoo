<?php

namespace App\Api\Controllers\Foods;

use App\Services\Token;
use App\Services\Notice;
use App\Utils\OrderStatus;
use App\Models\Foods\Order;
use App\Services\WechatPay;
use Illuminate\Http\Request;
use App\Models\Foods\Product;
use App\Models\Foods\Setting;
use App\Services\OpenPlatform;
use App\Models\Foods\OrderProduct;
use Illuminate\Support\Facades\DB;
use App\Api\Controllers\Controller;
use App\Models\Coupons\CouponRecord;
use App\Models\Wechat\NoticeTemplate;
use App\Http\Requests\Foods\CateRequest;

class OrderController extends Controller
{
    
    public function index() 
    {
        $order_no = request()->order_no;
        $fan_id = request()->fan_id;
        $start_time = request()->start_time;
        $end_time = request()->end_time . ' 23:59:59';

        $orders = Order::when($fan_id, function($query) use ($fan_id){
            return $query->where('fan_id', $fan_id)->whereNotIn('status',[-1]);
        })->when($order_no, function($query) use ($order_no){
            return $query->where('order_no', 'like', '%'.$order_no.'%');
        })->with(['products'])->with(['record' => function($query) {
            $query->with(['coupon' => function($query) {
                $query->select('id','name');
            }])->select('id','coupon_id','title');
        }])->with(['fan' => function($query) {
            $query->select('id','nickname');
        }])->with(['member' => function($query) {
            $query->select('fan_id','name', 'mobile');
        }])->where([['created_at','>=', $start_time],['created_at','<=', $end_time]])->orderBy('id', 'desc')->get();

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
        $order = Order::with('fan')->find(request()->id);
        if($order->update(['status' => OrderStatus::CONFIRM, 'confirm_time' => date('Y-m-d H:i:s', time())])){
            //发送模板消息
            Notice::pay_success_notice($order->xcx_id, $order->fan->openid, '/pages/me/me', $order->prepay_id, $order->order_no, '任意门奶茶',$order->price, '已确认订单,待收货', $order->price, $order->pay_time);
            return response()->json(['status' => 'success', 'msg' => '确认成功！']);         
        }

        return response()->json(['status' => 'error', 'msg' => '确认失败']);         
    }

    public function confirm_refund()
    {
        $order = Order::find(request()->id);
        
        if(request()->review  == 'agree'){
            if($order->status == OrderStatus::REFUND) {
                $notify_url = config('notify.wechat.foods') . '/' . session('xcx_id');
                $wechatPay = new WechatPay($notify_url);
                $result = $wechatPay->refund($order);
                if($result['result_code'] == 'SUCCESS' && $result['return_msg'] == 'OK') {
                    if($order->update(['status' => OrderStatus::REFUND_SUCCESS])){
                        return response()->json(['status' => 'success', 'msg' => '确认退款成功！']);         
                    }
                }    
            }
        }else{
            if($order->update(['status' => OrderStatus::REFUND_FAIL])){
                return response()->json(['status' => 'success', 'msg' => '拒绝退款成功！']);         
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
        })->with(['member'])->where('del_status', 0)->orderBy('id', 'desc')->with(['products'])->get();

        return response()->json(['status' => 'success', 'data' => $orders]);
    }

    public function init() 
    {
        $carts = request('cart');
        $price_total = 0;
        $mj_offer = 0;
        foreach($carts as $cart) {
            $price = Product::find($cart['product_id']);
            $price_total += $price['c_price'] * $cart['count'];
        }

        //满减 
        $setting = Setting::first();
        if($setting->offer_status == 1) {
            $offers = json_decode($setting->offer, true);
            foreach($offers as $offer) {
                if($price_total >= $offer['condition']) {
                    $mj_offer = intval($offer['reduce']);
                }
            }
        }

        $coupons = CouponRecord::getUserAccordCoupons(Token::getUid() ,$price_total);

        return response()->json(['status' => 'success', 'price_total' => $price_total - $mj_offer, 'mj_offer' => $mj_offer,'coupons' => $coupons]);     
    }

    public function commit() 
    {
        $result = DB::transaction(function (){

            $products = request('products');
            $record_id = request('record_id') ?? 0;

            $order = new Order;
            $order->order_no = $order->generateOrderNo();
            $order->fan_id = Token::getUid();
            $order->status = OrderStatus::UNPAID;
            $order->sign = request('sign');
            $order->name = request('name');
            $order->mobile = request('mobile');
            $order->address = request('address');
            $order->remark = request('remark');
            $order->record_id = $record_id;     
            $order->save();

            $data = $order->calcOrderPrice($order->id, $products, $record_id);
            
            $order->coupon_offer = $data['coupon_offer'];
            $order->mj_offer = $data['mj_offer'];
            $order->price = $data['price_total'];
            $order->save();
            

            $order->body = '任意门微信支付';
            $order->openid = Token::getCurrentTokenVar('openid');
            $notify_url = config('notify.wechat.foods') . '/' . session('xcx_id');
            $wechatPay = new WechatPay($notify_url);

            //保存prepayid
            $payOrder = $wechatPay->unify($order);
            Order::where('id', $order->id)->update(['prepay_id' => $payOrder['prepay_id']]);
            //返回结果
            return array_merge($payOrder,['order_id' => $order->id]);  

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
        if($order->status == OrderStatus::PAID) {
            $notify_url = config('notify.wechat.foods') . '/' . session('xcx_id');
            $wechatPay = new WechatPay($notify_url);
            $result = $wechatPay->refund($order);
            if($result['result_code'] == 'SUCCESS' && $result['return_msg'] == 'OK') {
                if($order->update(['status' => OrderStatus::CANCEL])){
                    if($order->record_id) {
                        CouponRecord::where('id', $order->record_id)->update(['status' => 0]);
                    }
                    //TODO 发送通知
                    if(request()->client_type == 'web') {

                    }
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
        $notify_url = config('notify.wechat.foods') . '/' . session('xcx_id');
        $wechatPay = new WechatPay($notify_url);
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
            $result = DB::transaction(function () use ($order){
                //退回优惠券
                if($order->record_id) {
                    CouponRecord::where('id', $order->record_id)->update(['status' => 0]);
                }
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

    public function send() {
        //165
        $order = Order::with(['fan' => function($query) {
            $query->select('id','openid');
        }])->find(165);
        $template_id = \App\Models\Wechat\NoticeTemplate::getTemplateIdByMark('PAY_SUCCESS');
        // dd(NoticeTemplate::getTemplate(session('xcx_id')));
        $app = OpenPlatform::getMiniProgram($order->xcx_id);
        $reslut = $app->template_message->send([
            'touser' => $order->fan->openid,
            'template_id' => $template_id,
            'page' => '/pages/index/index',
            'form_id' => 'wx03180648228789c82bac17463678742005',
            'data' => [
                'keyword1' => $order->order_no,
                'keyword2' => '任意门奶茶',
                'keyword3' => $order->price,
                'keyword4' => '已接单',
                'keyword5' => $order->price,
                'keyword6' => $order->pay_time,
            ],
        ]);

        return $reslut;
        
    }

}
