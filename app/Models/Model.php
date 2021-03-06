<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;


class Model extends BaseModel
{
    protected $guarded = [];
    
    public static function boot() {
        parent::boot();
        
        static::created(function($model) {
            $model->xcx_id = session('xcx_id');
            $model->save();
        });

        static::addGlobalScope('xcx_id', function(Builder $builder) {
            $builder->where('xcx_id', session('xcx_id'));
        });

    }

}
