<?php

namespace App\Models\Displays;

use App\Models\Model;

class Product extends Model
{
    protected $table = 'displays_products';

    public function category() 
    {
        return $this->belongsTo(ProductCate::class, 'cate_id', 'id');
    }
}
