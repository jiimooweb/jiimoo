<?php

namespace App\Models\Commons;

use App\Models\Model;

class ProductCate extends Model
{
    public $timestamps = false;

    public function products() 
    {
        return $this->hasMany(Product::class, 'cate_id', 'id')->orderBy('created_at','desc');
    }

    public function getChildrens(int $pid)
    {
        return $this->where('pid', $pid)->get()->pluck('id')->toArray();
    }
}
