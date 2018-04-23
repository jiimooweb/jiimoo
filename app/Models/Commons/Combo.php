<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    //
    protected $guarded=[];
    protected $table = 'combo';

    public function module(){
        return $this->belongsToMany(module::class,'xcx_combo_module',
            'combo_id','module_id')->
        withPivot(['module_id','combo_id']);
    }

    public function assignModule($module){
        return $this->module()->save($module);
    }
    public  function  detachModule($module){
        return $this->module()->detach($module);
    }
    public function hasModule($module){
        return $this->module->contains($module);
    }
}
