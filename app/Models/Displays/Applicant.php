<?php

namespace App\Models\Displays;

use App\Models\Model;
use App\Models\Displays\Career;
class Applicant extends Model
{
    protected $table = 'displays_applicants';

    public function career()
    {
        return $this->belongsToMany(Career::class,'displays_career_applicant',
            'applicant_id','career_id')->withPivot(['applicant_id','career_id']);
    }

    public function assginCareer($career)
    {
        return $this->career()->save($career);
    }

    public function detachCareer($career)
    {
        return $this->career()->detach($career);
    }
}
