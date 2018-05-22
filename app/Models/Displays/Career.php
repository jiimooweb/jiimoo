<?php

namespace App\Models\Displays;

use App\Models\Model;
use App\Models\Displays\Applicant;
class Career extends Model
{
    protected $table = 'displays_careers';

    public function applicant()
    {
        return $this->belongsToMany(Applicant::class,'displays_career_applicant',
            'career_id','applicant_id')->withPivot(['applicant_id','career_id']);
    }

}
