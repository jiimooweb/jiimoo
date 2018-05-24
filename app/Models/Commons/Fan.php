<?php

namespace App\Models\Commons;

use App\Models\Model;
use App\Models\Displays\Applicant;
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




    //展示类：使用者收藏求职者
    public function displayApplicant(){
        return $this->belongsToMany(Applicant::class,'displays_fans_applicant',
                'fan_id','applicant_id	');
    }
    public function detchApplicant($applicant){
        return $this->displayApplicant()->detach($applicant);
    }
}
