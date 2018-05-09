<?php

namespace App\Models\Commons;

use App\Models\Model;

class Fan extends Model
{
    public static function getByOpenID($openid) {
        return self::where('openid', $openid)->first();
    }

    public function couponRecords() 
    {
        return $this->hasMany(\App\Models\Coupons\CouponRecord::class);
    }

    public function scopeCouponRecords($query)
    {
        return $query->where('module', session('module'));
    }

    public function queue() {
        return $this->belongsTo(\App\Models\Queue\QueueUser::class, 'fan_id', 'id');
    }
}
