<?php

namespace App\Models\Foods;

use App\Models\Model;

class Cate extends Model
{
    protected $table = 'food_cates';

    public $timestamps = false;

    public function products() 
    {
        return $this->hasMany(Product::class, 'cate_id', 'id');
    }
}
