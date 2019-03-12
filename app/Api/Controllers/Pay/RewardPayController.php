<?php

namespace App\Api\Controllers\Pay;

use App\Models\Pay\PayOrder;
use App\Services\Token;
use App\Services\WebSocket;
use App\Services\WechatPay;
use App\Utils\OrderStatus;
use App\Utils\Wechat;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RewardPayController extends Controller
{
    public function getFanOrders()
    {
        $id = Token::getUid();
        $data = PayOrder::where('fan_id',$id)->where('status',1)
            ->orderBy('created_at','desc')->get();
        return response()->json(['status' => 'error', 'data' => $data]);
    }

    public function commit()
    {
        $fan_id = Token::getUid();
        $order = new PayOrder();
        $price = request('price');
        DB::beginTransaction();
        try {
            $payOrder = PayOrder::create([
                'order_no' => $order->generateOrderNo(),
                'fan_id'=>$fan_id,
                'price' => $price,
            ]);
            $payOrder->body = '任意门微信支付';
            $payOrder->openid = Token::getCurrentTokenVar('openid');
            $notify_url = config('notify.wechat.reward') . '/' . session('xcx_id');
            $wechatPay = new WechatPay($notify_url);
            $pay = $wechatPay->unify($payOrder);
            PayOrder::where('id', $payOrder->id)->update(['prepay_id' => $pay['prepay_id']]);
            $arrPay = array_merge($pay,['order_id' => $payOrder->id]);
            DB::commit();
            return response()->json(['status' => 'success', 'msg' => '新增成功!','data'=>$arrPay]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => ['新增失败！'.$e]]);
        }
    }

    public function notify()
    {
        $xcx_id = request()->xcx_id;
        
        $notify_url = config('notify.wechat.reward') . '/' . $xcx_id;

        $wechatPay = new WechatPay($notify_url);

        $app = $wechatPay->getApp($xcx_id);

        $response = $app->handlePaidNotify(function($message, $fail) use ($xcx_id){

            $order = PayOrder::where('order_no', $message['out_trade_no'])->withoutGlobalScopes()->first();

            if (!$order || $order->pay_time) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {

//                    PayOrder::where('id',$order->id)->update([
//                        'pay_time'=>date('Y-m-d H:i:s', time()),
//                        'trans_no'=> $message['transaction_id'],
//                        'status'=>OrderStatus::PAID
//                    ]);
                    $order->pay_time = date('Y-m-d H:i:s', time()); // 更新支付时间为当前时间
                    $order->trans_no = $message['transaction_id']; // 更新支付时间为当前时间
                    $order->status = OrderStatus::PAID;
                    $order->save();
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            return true; // 返回处理完成
        });


        return $response;
    }
}
