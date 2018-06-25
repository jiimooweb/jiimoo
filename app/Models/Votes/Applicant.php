<?php
namespace App\Models\Votes;
use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $table = 'votes_applicants';
    protected $guarded=[];
    public function fans()
    {
        return $this->hasMany(Fan::class,'opt','id');
    }
}