<?php

namespace App\Models\Answers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commons\Fans;
class AnswersUser extends Model
{
    //
    protected $guarded=[];
    protected $table = 'answers_users';
    public function fans(){
        return $this->hasOne(Fans::class,'id','fans_id');

    }
}
