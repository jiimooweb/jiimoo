<?php

namespace App\Models\Displays;

use App\Models\Model;

class Sign extends Model
{
    protected $table = 'displays_sign_lists';

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }


}
