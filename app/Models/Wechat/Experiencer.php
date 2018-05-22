<?php

namespace App\Models\Wechat;

use App\Models\Model;

class Experiencer extends Model
{
    public function xcx() 
    {
        return $this->belongsTo(\App\Models\Commons\Xcx::class, 'id', 'xcx_id');
    }
}
