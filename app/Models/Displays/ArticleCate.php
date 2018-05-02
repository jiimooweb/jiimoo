<?php

namespace App\Models\Displays;

use App\Models\Model;

class ArticleCate extends Model
{
    protected $table = 'displays_article_cates';

    protected $hidden = ['created_at', 'updated_at'];

    public function articles() 
    {
        return $this->hasMany(Article::class, 'cate_id', 'id')->orderBy('created_at','desc');
    }

}
