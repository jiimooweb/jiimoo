<?php

namespace App\Models\Foods;

use App\Models\Model;

class Member extends Model
{
    protected $table = 'food_members';

    public function products() 
    {
        return $this->hasMany(Product::class, 'cate_id', 'id');
    }
}
