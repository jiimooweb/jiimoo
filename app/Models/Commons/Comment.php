<?php

namespace App\Models\Commons;

use App\Models\Model;

class Comment extends Model
{
    public function article() 
    {
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

}
