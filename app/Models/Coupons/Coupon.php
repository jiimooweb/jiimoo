<?php

namespace App\Models\Coupons;

use App\Models\Commons\AdminUser;
use App\Models\Model;

class Coupon extends Model
{
    protected $hidden = ['updated_at', 'created_at'];
    
    public function records() 
    {
        return $this->hasMany(CouponRecord::class);
    }


    public static function getTime(int $id) {
        $time = [];
        $coupon = self::find($id);
        if($coupon->all()) {
            if($coupon->time_type) {
                $time['start'] = date('Y-m-d',strtotime('+'.$coupon->startat.' day'));
                $time['end'] = date('Y-m-d', strtotime('+'.($coupon->startat+$coupon->time_limit).' day'));
            }else {
                $time['start'] = $coupon->start_time;
                $time['end'] = $coupon->end_time;
            }
        }
        return $time;
    }

    public function products()
    {
        return $this->belongsToMany(\App\Models\Commons\Product::class, 'coupon_products', 'coupon_id', 'product_id')->withoutGlobalScopes();
    }

    

}
