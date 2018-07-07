<?php

namespace App\Api\Controllers\Pay;

use App\Utils\OrderStatus;
use App\Models\Foods\Order;
use App\Services\WebSocket;
use App\Services\WechatPay;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;

class FoodPayController extends Controller
{
    public function notify()
    {
        $xcx_id = request()->xcx_id;

        $notify_url = config('notify.wechat.foods') . '/' . $xcx_id;

        $wechatPay = new WechatPay($notify_url);
        
        $app = $wechatPay->getApp($xcx_id);

        $response = $app->handlePaidNotify(function($message, $fail) use ($xcx_id){

            $order = Order::where('order_no', $message['out_trade_no'])->withoutGlobalScopes()->first();
        
            if (!$order || $order->pay_time) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
        
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    $order->pay_time = date('Y-m-d H:i:s', time()); // 更新支付时间为当前时间
                    $order->trans_no = $message['transaction_id']; // 更新支付时间为当前时间
                    $order->status = OrderStatus::PAID;
                    $order->save();
                    WebSocket::sendOrderMsg('xcx_id_'.$xcx_id, $order);
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
        
            
        
            return true; // 返回处理完成
        });
        

        return $response;
    }
}