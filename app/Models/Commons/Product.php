<?php

namespace App\Models\Commons;

use App\Models\Model;

class Product extends Model
{
    public function category() 
    {
        return $this->belongsTo(ProductCate::class, 'cate_id', 'id');
    }
}
