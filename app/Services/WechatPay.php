<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use EasyWeChat\Factory;

class WechatPay extends Model
{
    public static function getApp()
    {
        $config = [
            // 必要配置
            'app_id'    => config('wechat.payment.default.app_id'),
            'mch_id'    => config('wechat.payment.default.mch_id'),
            'key'   => config('wechat.payment.default.key'),   // API 密钥
            'notify_url' => 'https://www.rdoorweb.com/wechat/notify',
        ];

        $app = Factory::payment($config);

        return $app;
    }

    public static function unify($order)
    {
        $app = self::getApp();
        $result = $app->order->unify([
            'body' => $order['body'],
            'out_trade_no' => $order['order_no'],
            'total_fee' => $order['price'],
            'trade_type' => 'JSAPI',
            'openid' => 'owuH05Uoc0ItjqhGVjK62Acp5CkI',
        ]);

        return $result;
    }
}
