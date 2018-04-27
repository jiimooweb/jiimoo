<?php

namespace App\Models\Answers;

use Illuminate\Database\Eloquent\Model;
use App\Models\Answers\Depot;
class Activitie extends Model
{
    //
    protected $guarded=[];
    protected $table = 'answers_activities';
    public function depot(){
        return $this->hasMany(Depot::class,'activity','id');
    }
}
