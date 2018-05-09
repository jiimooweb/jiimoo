<?php

namespace App\Models\Commons;

use App\Models\Model;

class Article extends Model
{

    public function comments() 
    {
        return $this->hasMany(Comment::class)->orderBy('created_at','desc');
    }

    public function category() 
    {
        return $this->belongsTo(ArticleCate::class, 'cate_id', 'id');
    }

}
