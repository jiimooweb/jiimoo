<?php

namespace App\Models\Commons;

use App\Models\Model;

class ArticleCate extends Model
{
    public $timestamps = false; 
    
    public function articles() 
    {
        return $this->hasMany(Article::class, 'cate_id', 'id')->orderBy('created_at','desc');
    }

}
