<?php
namespace App\Models\Votes;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Info extends  Model
{
    protected $table = 'votes_infos';
    protected $hidden = ['created_at'];
//    use SoftDeletes;
//    protected $dates = ['deletel_at'];

    public function fans()
    {
        return $this->hasMany(Fan::class,'vote_id','id');
    }
    public function applicants()
    {
        return $this->hasMany(Applicant::class,'vote_id','id')->select(['vote_id','total']);
    }
    public function options()
    {
        return $this->hasMany(Option::class,'vote_id','id');
    }
}