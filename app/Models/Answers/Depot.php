<?php

namespace App\Models\Answers;

use App\Models\Model;
use App\Models\Answers\Question;
use App\Models\Answers\Activitie;
class Depot extends Model
{
    //
    protected $guarded=[];
    protected $table = 'answers_depot';
    public function question()
    {
        return $this->hasMany(Question::class,'depot_id','id');
    }

    public function activitie()
    {
        return $this->belongsToMany(Activitie::class,'answers_activity_depot',
            'depot_id','activity_id')->withPivot(['activity_id','depot_id']);
    }

    public function assignActivitie($activity)
    {
        return $this->activitie()->save($activity);
    }

    public function detachActivitie($activity)
    {
        return $this->activitie()->detach($activity);
    }
}
