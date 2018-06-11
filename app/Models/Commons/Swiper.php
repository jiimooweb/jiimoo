<?php

namespace App\Models\Commons;

use App\Models\Model;

class Swiper extends Model
{
    public function group()
    {
        return $this->belongsTo(SwiperGroup::class, 'id', 'group');
    }
}
