<?php

namespace App\Models\Foods;

use App\Models\Model;

class Product extends Model
{
    protected $table = 'food_products';

    public function cate()
    {
        return $this->belongsTo(Cate::class, 'id', 'cate_id');
    }
}
