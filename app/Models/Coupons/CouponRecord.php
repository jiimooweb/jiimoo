<?php

namespace App\Models\Coupons;

use App\Models\Model;

class CouponRecord extends Model
{

    public static function boot() 
    {
        $date = date('Y-m-d',time());

        parent::boot();
    
        static::addGlobalScope('data', function(Builder $builder) {
            $builder->where('start_time', '<=', $date)->where('end_time', '>=', $date);
        });

    }
    public function coupon() 
    {
        return $this->belongsTo(Coupon::class);
    }

    public function fan() 
    {
        return $this->belongsTo(\App\Models\Commons\Fan::class);
    }

    public static function getUserHasCoupons(int $uid) : CouponRecord
    {
        if(iseet($uid)) {
            return '用户ID不能为空';
        }

        return self::where(['fan_id' => $uid, 'status' => 0])->get();
    }
}
