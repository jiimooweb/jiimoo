<?php


namespace App\Models;

use App\Models;

class AdminUser extends Model
{
    public static function check($username, $password) 
    {
        return self::where('username',$username)->where('password',bcrypt($password))->first();
    }

}
