<?php

namespace App\Models\Answers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Answers\Question;
class Depot extends Model
{
    //
    protected $guarded=[];
    protected $table = 'answers_depot';
    public function question(){
        $this->hasMany(Question::class,'depot_id','id');
    }
}
