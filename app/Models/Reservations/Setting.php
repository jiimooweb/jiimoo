<?php

namespace App\Models\Reservations;

use App\Models\Model;

class Setting extends Model
{
    protected $table = 'reservation_settings';


    protected $casts = [
        'businessHours' => 'array',
    ];
}
