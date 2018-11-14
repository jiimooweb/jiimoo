<?php

namespace App\Models\Reservations;

use App\Models\Model;

class Product extends Model
{
    protected $table = 'reservation_products';

    public function cate()
    {
        return $this->belongsTo(Cate::class, 'id', 'cate_id');
    }
}
