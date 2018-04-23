<?php

namespace App\Models\Displays;

use App\Models\Model;

class Activity extends Model
{
    protected $table = 'displays_activitys';

    public function signlists()
    {
        return $this->hasMany(\App\Models\Displays\Sign::class)->orderBy('created_at','desc');    
    }

}
