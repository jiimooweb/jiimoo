<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commons\AdminUser;

class Xcx extends Model
{
    //
    protected $guarded=[];
    protected $table = 'xcxs';

    public function user(){
        return $this->belongsToMany(AdminUser::class,'users_xcxs',
            'xcx_id','user_id')->
            withPivot(['xcx_id','user_id']);
    }

    public function hasUser($id) {
        return $this->user()->where('user_id', $id)->count();
    }
    public function assignUser($user){
        return $this->xcxs()->save($user);
    }
    public function detachUser($user){
        return $this->detachXcx()->detach($user);
    }

}
