<?php

namespace App\Models\Reservations;

use App\Models\Model;

class Cate extends Model
{
    protected $table = 'reservation_cates';

    public $timestamps = false;

    public function products() 
    {
        return $this->hasMany(Product::class, 'cate_id', 'id');
    }

    public function thumb()
    {
        return $this->hasOne(Product::class,'cate_id','id');
    }
}
