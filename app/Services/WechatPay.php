<?php

namespace App\Services;

use EasyWeChat\Factory;
use Illuminate\Database\Eloquent\Model;

class WechatPay extends Model
{
    private $notify_url;

    public function __construct($notify_url)
    {
        $this->notify_url = $notify_url;
    }


    public function getApp($xcx_id)
    {
        if(!$this->notify_url) {
            return '回调地址不能为空';
        }

        $pay = DB::table('wechat_pay_configs')->where('xcx_id', $xcx_id)->first();

        \Log::info($pay);

        $config = [
            // 必要配置
            'app_id'    => $pay->app_id,
            'mch_id'    => $pay->mch_id,
            'key'   => $pay->key,   // API 密钥
            'cert_path' => '/usr/local/wechat/ssl/' . $pay->xcx_id . '/apiclient_cert.pem',
            'key_path'  => '/usr/local/wechat/ssl/' . $pay->xcx_id . '/apiclient_key.pem',  
            'notify_url' => $this->notify_url,
        ];

        $app = Factory::payment($config);

        return $app;
    }

    public function unify($order)
    {
        $app = $this->getApp($order->xcx_id);

        $result = $app->order->unify([
            'body' => $order->body,
            'out_trade_no' => $order->order_no,
            'total_fee' => $order->price * 100,
            'trade_type' => 'JSAPI',
            'openid' => $order->openid,
        ]);

        $prepay_id = $result['prepay_id'];
        
        $payment =  $app->jssdk->bridgeConfig($prepay_id, false);

        return array_merge($payment,['prepay_id' => $prepay_id]);
    }

    public function refund($order) 
    {
        $app = $this->getApp($order->xcx_id);
        $result = $app->refund->byTransactionId($order->trans_no, 'TK'.$order->order_no, $order->price * 100, $order->price * 100, [
            // 可在此处传入其他参数，详细参数见微信支付文档
            'refund_desc' => '用户取消订单',
        ]);

        return $result;
    }
    

    
    
}
