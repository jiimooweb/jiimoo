<?php

namespace App\Models\Coupons;

use App\Models\Commons\AdminUser;
use App\Models\Model;

class Coupon extends Model
{
    public function records() 
    {
        return $this->hasMany(CouponRecord::class);
    }


    public static function getTime($id) {
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

}
