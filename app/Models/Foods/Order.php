<?php

namespace App\Models\Foods;

use App\Models\Model;
use App\Models\Foods\Product;
use App\Models\Foods\OrderProduct;
use App\Models\Coupons\CouponRecord;

class Order extends Model
{
    protected $table = 'food_orders';

    public function fan()
    {
        return $this->hasOne(\App\Models\Commons\Fan::class, 'id', 'fan_id');
    }

    public function generateOrderNo() 
    {
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    public function calcOrderPrice(int $order_id, array $products, int $record_id)
    {
        $price_total = 0;
        $offer = 0;
        $coupon_offer = 0;

        foreach($products as $row) {
            $product_id = $row['product_id'];
            $product = Product::find($product_id);
            $price_total += $product['c_price'] * $row['count'];

            if($product['o_price'] > 0) {
                $offer += ($product['o_price'] - $product['c_price']) * $row['count'];
            }

            OrderProduct::create(['order_id' => $order_id, 'product_id' => $product_id, 'count' => $row['count']]);
        }

        if($record_id) {
            CouponRecord::use($record_id);
            $coupon = CouponRecord::find($record_id)->coupon;
            $coupon_offer = intval($coupon['price']);
            $price_total = $price_total - $coupon_offer;
        } 

        return ['price_total' => $price_total, 'offer' => $offer,'coupon_offer' => $coupon_offer];
    }

    public static function getStatusCount(int $fans_id) 
    {
        $unpay = self::where(['fan_id' => $fans_id, 'status' => 0])->get()->count();
        $confirm = self::where(['fan_id' => $fans_id, 'status' => 1])->get()->count();
        $distribution = self::where(['fan_id' => $fans_id, 'status' => 2])->get()->count();
        return ['unpay' => $unpay, 'confirm' => $confirm, 'distribution' => $distribution];
    }
}
