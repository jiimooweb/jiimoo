<?php

namespace App\Models\Members;

use App\Models\Model;
use App\Models\Members\MiniMember;

class Group extends Model
{
    protected $table = 'member_groups';
    
    public $timestamps = false;

    public static function getGroup() 
    {
        return Group::orderBy('value', 'asc')->get();
    }

    public static function changeGroupLevel(int $uid) 
    {
        $member = MiniMember::find($uid);
        $groups = self::getGroup();
        foreach($groups as $group) {
            if($member->integral_total >= $group->value) {
                $member->group_id = $group->id;
                $member->save();
            }
        }
    }

}
