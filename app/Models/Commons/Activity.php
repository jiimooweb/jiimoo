<?php

namespace App\Models\Commons;

use App\Models\Model;

class Activity extends Model
{
    protected $table = 'activitys';

    public function signlists()
    {
        return $this->hasMany(Sign::class)->orderBy('created_at','desc');    
    }

}
