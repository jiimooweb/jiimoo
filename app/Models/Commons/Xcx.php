<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commons\CommunalUser;

class Xcx extends Model
{
    //
    protected $guarded=[];
    protected $table = 'xcxs';

    public function user(){
        return $this->belongsToMany(CommunalUser::class,'users_xcxs',
            'xcx_id','user_id')->
            withPivot(['xcx_id','user_id']);
    }
}
