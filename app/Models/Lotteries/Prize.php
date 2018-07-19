<?php

namespace App\Models\Lotteries;

use Illuminate\Database\Eloquent\Model;
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
        return $this->belongsTo(Activity::class,'prize_id','activity_id');
    }

    public function detachActivity($activity)
    {
        return $this->activities()->detach($activity);
    }
}
