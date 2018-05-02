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

}
