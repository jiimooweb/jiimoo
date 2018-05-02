<?php

namespace App\Models\Displays;

use App\Models\Model;

class Article extends Model
{
    protected $table = 'displays_articles';

    public function comments() 
    {
        return $this->hasMany(Comment::class)->orderBy('created_at','desc');
    }

    public function category() 
    {
        return $this->belongsTo(ArticleCate::class, 'cate_id', 'id');
    }

}
