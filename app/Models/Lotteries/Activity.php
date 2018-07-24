<?php

namespace App\Models\Lotteries;

use App\Models\Model;
use App\Models\Lotteries\Prize;
use App\Models\Commons\Fan;
class Activity extends Model
{
    //
    protected $guarded=[];
    protected $table = 'lottery_activities';

    public function prizes()
    {
        return $this->hasMany(Prize::class,
            'activity_id','id');
    }

    public function assignPrize($prize)
    {
        return $this->prizes()->save($prize);
    }

    public function detachPrize($prize)
    {
        return $this->prizes()->detach($prize);
    }

    public function fans(){
        return $this->belongsToMany(Fan::class,'lottery_activity_fan',
            'activity_id','fan_id');
    }
}
