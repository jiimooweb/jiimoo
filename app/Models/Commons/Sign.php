<?php

namespace App\Models\Commons;

use App\Models\Model;

class Sign extends Model
{
    protected $table = 'sign_lists';

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }


}
