<?php

namespace App\Models\Members;

use App\Models\Model;
use App\Models\Members\MemberTag;

class MiniMember extends Model
{
    protected $table = 'members';

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'member_tag_links', 'member_id', 'tag_id')->withoutGlobalScopes();
    }


    public static function changeIntegral($member_id, $value)
    {
        $self = self::find($member_id);
        $self->integral = $self->integral + $value;
        $self->integral_total = $self->integral_total + abs($value);
        $self->save();
    }

    public static function changeMoney($member_id, $value)
    {
        $self = self::find($member_id);
        $self->money = $self->money + $value;
        $self->save();
    }

    public static function getNotHasTags($member_id)
    {
        $memberTags = MemberTag::where('member_id', $member_id)->get()->pluck('tag_id')->toArray();
        return Tag::whereNotIn('id', $memberTags)->get();
    }
}
