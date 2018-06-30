<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;

class WechatPay extends Model
{
    private $notify_url;

    public function __construct($notify_url)
    {
        $this->notify_url = $notify_url;
    }


    public function getApp()
    {
        if(!$this->notify_url) {
            return '回调地址不能为空';
        }

        $config = [
            // 必要配置
            'app_id'    => config('wechat.payment.default.app_id'),
            'mch_id'    => config('wechat.payment.default.mch_id'),
            'key'   => config('wechat.payment.default.key'),   // API 密钥
            'notify_url' => $this->notify_url,
        ];

        $app = Factory::payment($config);

        return $app;
    }

    public function unify($order)
    {
        $app = $this->getApp();
        $result = $app->order->unify([
            'body' => $order['body'],
            'out_trade_no' => $order['order_no'],
            'total_fee' => $order['price'],
            'trade_type' => 'JSAPI',
            'openid' => 'owuH05Uoc0ItjqhGVjK62Acp5CkI',
        ]);
        
        $result =  $app->jssdk->bridgeConfig($result['prepay_id'], false);

        return $result;
    }

    public static function notify($callback, $array) {
        return call_user_func_array($callback, $array);
    }
    
    
}
