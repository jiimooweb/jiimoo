<?php

namespace App\Models\Displays;

use App\Models\Model;

class Comment extends Model
{
    protected $table = 'displays_comments';

    public function article() 
    {
        return $this->belongsTo(\App\Models\Displays\Article::class, 'article_id', 'id');
    }

}
