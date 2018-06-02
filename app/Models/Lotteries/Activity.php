<?php

namespace App\Models\Lotteries;

use App\Models\Model;
use App\Models\Lotteries\Prize;
class Activity extends Model
{
    //
    protected $guarded=[];
    protected $table = 'lottery_activities';

    public function prizes()
    {
        return $this->belongsToMany(Prize::class,'lottery_activity_prize',
            'activity_id','prize_id');
    }

    public function assignPrize($prize)
    {
        return $this->prizes()->save($prize);
    }

    public function detachPrize($prize)
    {
        return $this->prizes()->detach($prize);
    }
}
