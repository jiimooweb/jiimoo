<?php

namespace App\Models\Commons;

use App\Models\Model;

class Fan extends Model
{
    public static function getByOpenID($openid) {
        return self::where('openid', $openid)->first();
    }
}
