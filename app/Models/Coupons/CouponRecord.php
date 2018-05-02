<?php

namespace App\Models\Coupons;

use App\Models\Commons\AdminUser;
use App\Models\Model;

class CouponRecord extends Model
{
    public function coupon() 
    {
        return $this->belongsTo(Coupon::class);
    }

    public function fan() 
    {
        return $this->belongsTo(\App\Models\Commons\Fan::class);
    }
}
