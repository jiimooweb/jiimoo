<?php

namespace App\Api\Controllers\Pay;

use App\Utils\OrderStatus;
use App\Models\Foods\Order;
use App\Services\WechatPay;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;

class FoodPayController extends Controller
{
    public function notify()
    {
        

        $wechatPay = new WechatPay(config('notify.wechat.foods'));
        
        $app = $wechatPay->getApp();

        // \Log::info($app);

        // $response = $app->handlePaidNotify(function($message, $fail){

        //     $order = Order::where('order_no', $message['out_trade_no'])->first();
        
        //     if (!$order || $order->pay_time) { // 如果订单不存在 或者 订单已经支付过了
        //         return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
        //     }
        
        //     if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
        //         // 用户是否支付成功
        //         if (array_get($message, 'result_code') === 'SUCCESS') {
        //             $order->pay_time = data('Y-m-d H:i:s', time()); // 更新支付时间为当前时间
        //             $order->trans_no = $message['transaction_id']; // 更新支付时间为当前时间
        //             $order->status = OrderStatus::PAID;
        
        //         // 用户支付失败
        //         } elseif (array_get($message, 'result_code') === 'FAIL') {
        //             $order->status = OrderStatus::UNPAID;
        //         }
        //     } else {
        //         return $fail('通信失败，请稍后再通知我');
        //     }
        
        //     $order->save(); // 保存订单
        
        //     return true; // 返回处理完成
        // });
        
        return $app;
    }
}