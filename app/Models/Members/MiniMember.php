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


    public static function changeIntegral($member_id, $value)
    {
        $self = new self;
        if($value > 0) {
            $self->increment('integral', $vlaue, ['id' => $member_id]);
        }elseif($value < 0) {
            $self->decrement('integral', abs($vlaue), ['id' => $member_id]);
        }

        $self->increment('integral_total', abs($vlaue), ['id' => $member_id]);
    }

    public static function changeMoney($member_id, $value)
    {
        $self = new self;
        if($value > 0) {
            $self->increment('money', $vlaue, ['id' => $member_id]);
        }elseif($value < 0) {
            $self->decrement('money', abs($vlaue), ['id' => $member_id]);
        }
    }
}
