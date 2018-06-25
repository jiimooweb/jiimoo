<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commons\Xcx;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AdminUser extends Authenticatable
{
    //
    protected $guarded=[];
    protected $table = 'admin_users';
    protected $rememberTokenName='';
    protected $hidden = ['password'];

    public function xcxs()
    {
        return $this->belongsToMany(Xcx::class,'users_xcxs',
            'user_id','xcx_id')->
        withPivot(['xcx_id','user_id','sort']);
    }

    public function assignXcx(Xcx $xcx)
    {
        return $this->xcxs()->save($xcx);
    }

    public function detachXcx(Xcx $xcx)
    {
        return $this->xcxs()->detach($xcx);
    }

    public static function check(string $username, string $password) 
    {
        $email=	$username;
        $userNameAttempt=compact('username','password');
        $userEmailAttempt=compact('email','password');
        if(\Auth::guard('admins')->attempt($userNameAttempt)||
            \Auth::guard('admins')->attempt($userEmailAttempt)){
                return \Auth::guard('admins')->user();
        }
        return false;
    }
}
