<?php

namespace App\Models\Coupons;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;

class CouponRecord extends Model
{

    public static function boot() 
    {
        parent::boot();
        
        static::addGlobalScope('data', function(Builder $builder) {
            $builder->where('end_time', '>=', date('Y-m-d',time()));
        });

    }

    public function coupon() 
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    public function fan() 
    {
        return $this->belongsTo(\App\Models\Commons\Fan::class);
    }

    //获取用户可用的优惠券
    public static function getUserHasCoupons(int $uid)
    {
        if(!isset($uid)) {
            return '用户ID不能为空';
        }

        return self::where(['fan_id' => $uid, 'status' => 0])->with(['coupon'])->get()->toArray();
    }

    //获取用户已使用和已过期的优惠券
    public static function getUserCouponsByUsed(int $uid)
    {
        if(!isset($uid)) {
            return '用户ID不能为空';
        }

        return self::where(['fan_id' => $uid])->whereIn('status', [-1, 1])->with(['coupon'])->get()->toArray();
    }

    //获取用户符合条件的优惠券    
    public static function getUserAccordCoupons($uid, $total_price) {
        $result = [];
        $records = self::getUserHasCoupons($uid);
        foreach($records as $record) {
            if($record['coupon']['use_price'] <= $total_price) {
                $result[] = $record;
            }
        }
        return $result;
    }
}
