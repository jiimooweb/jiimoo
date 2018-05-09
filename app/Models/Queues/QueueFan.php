<?php

namespace App\Models\Queues;

use App\Models\Model;

class QueueFan extends Model
{
    public function fan() 
    {
        return $this->hasOne(\App\Models\Commons\Fan::class, 'id', 'fan_id');
    }
}
