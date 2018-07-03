<?php

namespace App\Services;

use EasyWeChat\Factory;
use App\Models\Wechat\NoticeTemplate;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    /**
     * 支付成功通知
     *
     * @param integer $xcx_id 小程序ID
     * @param string $openid 用户openid
     * @param string $page 小程序页面路径
     * @param string $prepay_id prepay_id
     * @param string $order_no 订单编号
     * @param string $product_name 商品名称
     * @param float $product_price 商品价格
     * @param string $order_status 支付状态
     * @param float $pay_price 支付金额
     * @param string $pay_time 支付时间
     * @return void
     */
    public static function pay_success_notice(int $xcx_id, string $openid, $page = '/pages/index/index', string $prepay_id, string $order_no, $product_name = '任意门奶茶', float $product_price, string $order_status,float $pay_price, string $pay_time)
    {
        $template_id = NoticeTemplate::getTemplateIdByMark('PAY_SUCCESS');
        $app = OpenPlatform::getMiniProgram($xcx_id);
        $result = $app->template_message->send([
            'touser' => $openid,
            'template_id' => $template_id,
            'page' => $page,
            'form_id' => $prepay_id,
            'data' => [
                'keyword1' => $order_no,
                'keyword2' => $product_name,
                'keyword3' => $product_price,
                'keyword4' => $order_status,
                'keyword5' => $pay_price,
                'keyword6' => $pay_time,
            ],
        ]);
        return $result;
    }
    
}
