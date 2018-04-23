<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commons\Xcx;
use Illuminate\Foundation\Auth\User as Authenticatable;
class CommunalUser extends Authenticatable
{
    //
    protected $guarded=[];
    protected $table = 'communal_users';
    protected $rememberTokenName='';

    public function xcxs(){
        return $this->belongsToMany(Xcx::class,'users_xcxs',
            'user_id','xcx_id')->
        withPivot(['xcx_id','user_id']);
    }
    public function assignXcx($xcx){
        return $this->xcxs()->save($xcx);
    }
    public  function  detachXcx($xcx){
        return $this->detachXcx()->detach($xcx);
    }
}
