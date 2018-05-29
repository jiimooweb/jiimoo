<?php

namespace App\Models\Wechat;

use Illuminate\Database\Eloquent\Model;

class Experiencer extends Model
{
    protected $guarded = [];
    
    public function xcx() 
    {
        return $this->belongsTo(\App\Models\Commons\Xcx::class, 'id', 'xcx_id');
    }
}
