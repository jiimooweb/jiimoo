<?php

namespace App\Models\Commons;

use Illuminate\Database\Eloquent\Model;
use App\Models\Commons\Combo;
class Module extends Model
{
    //
    protected $guarded=[];
    protected $table = 'module';
    public function combo(){
        return $this->belongsToMany(Combo::class,'xcx_combo_module',
            'module_id','combo_id')->
             withPivot(['module_id','combo_id']);
    }
    public function deleteCombo($combo)
    {
        return $this->combo()->delete($combo);
    }
}
