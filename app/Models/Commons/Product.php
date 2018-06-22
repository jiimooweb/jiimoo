<?php

namespace App\Models\Commons;

use App\Models\Model;
use App\Models\Coupons\CouponProduct;

class Product extends Model
{
    public function category() 
    {
        return $this->belongsTo(ProductCate::class, 'cate_id', 'id');
    }

    public function coupons()
    {
        return $this->belongsToMany(\App\Models\Coupons\Coupon::class, 'coupon_products', 'product_id', 'coupon_id')->withoutGlobalScopes();
    }

    public static function processCoupon(int $product_id, array $coupons) : bool
    {
        $couponProducts = CouponProduct::where('product_id', $product_id)->get()->pluck('coupon_id')->toArray();

        foreach($coupons as $coupon_id) {
            if(!in_array($coupon_id, $couponProducts)) {
                CouponProduct::create(['product_id' => $product_id, 'coupon_id' => $coupon_id]);
            }
        }

        foreach($couponProducts as $coupon_id) {
            if( !in_array($coupon_id, $coupons)) {
                CouponProduct::where(['product_id' => $product_id, 'coupon_id' => $coupon_id])->delete();                
            }
        }

        return true;
    }
}
