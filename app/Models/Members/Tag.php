<?php

namespace App\Models\Members;

use App\Models\Model;

class Tag extends Model
{
    protected $table = 'member_tags';

    public $timestamps = false;

    public function members()
    {
        return $this->belongsToMany(MiniMember::class, 'member_tag_links', 'tag_id', 'member_id')->withoutGlobalScopes();
    }
}
