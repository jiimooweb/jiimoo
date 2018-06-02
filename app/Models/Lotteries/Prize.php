<?php

namespace App\Models\Lotteries;

use App\Models\Model;
use App\Models\Coupons\Coupon;
use App\Models\Lotteries\Activity;
class Prize extends Model
{
    //
    protected $guarded=[];
    protected $table = 'lottery_prizes';

    public function coupon()
    {
        return $this->hasOne(Coupon::class,'id','coupon_id');
    }

    public function activities()
    {
        return $this->belongsToMany(Activity::class,'lottery_activity_prize',
            'prize_id','activity_id');
    }

    public function detachActivity($activity)
    {
        return $this->activities()->detach($activity);
    }
}
