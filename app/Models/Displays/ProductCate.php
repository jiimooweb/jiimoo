<?php

namespace App\Models\Displays;

use App\Models\Model;

class ProductCate extends Model
{
    protected $table = 'displays_product_cates';

    public function products() 
    {
        return $this->hasMany(\App\Models\Displays\Product::class, 'cate_id', 'id')->orderBy('created_at','desc');
    }
    
}
