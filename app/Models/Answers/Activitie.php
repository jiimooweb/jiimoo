<?php

namespace App\Models\Answers;

use App\Models\Model;
use App\Models\Answers\Depot;
class Activitie extends Model
{
    //
    protected $guarded=[];
    protected $table = 'answers_activities';
    public function depot()
    {
        return $this->belongsToMany(Depot::class,'answers_activity_depot'
            ,'activity_id','depot_id')->withPivot(['activity_id','depot_id']);
    }

    public function assignDepot($depot)
    {
        return $this->depot()->save($depot);
    }

    public function detachDepot($depot)
    {
        return $this->depot()->detach($depot);
    }

}
