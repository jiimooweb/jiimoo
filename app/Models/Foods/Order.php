<?php

namespace App\Models\Foods;

use App\Models\Model;
use App\Models\Foods\Product;
use App\Models\Foods\Setting;
use App\Models\Foods\OrderProduct;
use App\Models\Coupons\CouponRecord;

class Order extends Model
{
    protected $table = 'food_orders';

    public function fan()
    {
        return $this->hasOne(\App\Models\Commons\Fan::class, 'id', 'fan_id');
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id');
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
        $mj_offer = 0;

        foreach($products as $row) {
            $product_id = $row['product_id'];
            $count = $row['count'];
            $product = Product::find($product_id);
            $price_total += $product['c_price'] * $count;

            if($product['o_price'] > 0) {
                $offer += ($product['o_price'] - $product['c_price']) * $count;
            }

            OrderProduct::create(['order_id' => $order_id, 'product_id' => $product_id, 'count' => $count, 'name' => $product['name'], 'thumb' => $product['thumb'], 'c_price' => $product['c_price'] * $count, 'o_price' => ($product['o_price'] ?? 0) * $count]);
        }
        //满减 
        $setting = Setting::firsr();
        if($setting->offer_status == 1) {
            foreach($setting->offer as $offer) {
                if($price_total > $offer['condition']) {
                    $mj_offer = $offer['reduce'];
                }
            }
        }
        //优惠券
        if($record_id) {
            CouponRecord::use($record_id);
            $coupon = CouponRecord::find($record_id)->coupon;
            $coupon_offer = intval($coupon['price']);
        } 

        $price_total = $price_total - $coupon_offer - $mj_offer;
        

        return ['price_total' => $price_total, 'offer' => $offer,'coupon_offer' => $coupon_offer, 'mj_offer' => $mj_offer];
    }

    public static function getStatusCount(int $fans_id) 
    {
        $unpay = self::where(['fan_id' => $fans_id, 'status' => 0])->get()->count();
        $receive = self::where(['fan_id' => $fans_id, 'status' => 1])->get()->count();
        $receipt = self::where(['fan_id' => $fans_id, 'status' => 2])->get()->count();
        return ['unpay' => $unpay, 'receive' => $receive, 'receipt' => $receipt];
    }
}
