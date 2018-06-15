<?php

namespace App\Models\Members;

use App\Models\Model;

class MiniMember extends Model
{
    protected $table = 'members';

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'member_tag_links', 'member_id', 'tag_id')->withoutGlobalScopes();
    }


    public static function changeIntegral($member_id, $value, $mode)
    {
        if($mode === 'increment') {
            MiniMember::increment('integral', $vlaue, ['id' => $member_id]);
        }elseif($mode === 'decrement') {
            MiniMember::decrement('integral', $vlaue, ['id' => $member_id]);
        }

        MiniMember::increment('integral_total', $vlaue, ['id' => $member_id]);
    }

    public static function changeMoney($member_id, $value, $mode)
    {
        if($mode === 'increment') {
            MiniMember::increment('money', $vlaue, ['id' => $member_id]);
        }elseif($mode === 'decrement') {
            MiniMember::decrement('money', $vlaue, ['id' => $member_id]);
        }
    }
}
