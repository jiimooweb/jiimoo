<?php

namespace App\Models\Queues;

use Illuminate\Database\Eloquent\Model;

class QueueFan extends Model
{
    protected $guarded = [];
    
    public function fan() 
    {
        return $this->hasOne(\App\Models\Commons\Fan::class, 'id', 'fan_id');
    }
}
