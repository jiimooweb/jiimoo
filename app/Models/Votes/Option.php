<?php
namespace App\Models\Votes;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'votes_options';
    protected $guarded=[];
    public function fans()
    {
        return $this->hasMany(Fan::class,'vote_id','id');
    }
}