<?php

namespace App\Utils;

use App\Models\Members\Group;
use App\Models\Members\MiniMember;

class Member 
{
    public static function getGroup() 
    {
        return Common::convert_arr_key(Group::orderBy('value')->get()->toArray(), 'id');
    }

    public static function changeGroupLevel($uid) 
    {
        $member = MiniMember::find($uid);
        $groups = self::getGroup();
        foreach($groups as $group) {
            if($member->integral_total >= $group['value']) {
                $member->group_id = $group['id'];
                $member->save();
            }
        }
    }

    
}

