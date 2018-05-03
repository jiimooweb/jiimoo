<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 15:57
 */

namespace App\Api\Controllers;
use App\Api\Controllers\Controller;
use App\Models\Commons\Combo;
use App\Models\Commons\Module;
class ComboControlle extends Controller
{
    public function index(){
        $combo=Combo::all();
        return response()->json(["status"=>"success","data"=>$combo]);
    }
    public function store(){
        $this->validate(request(),[
            'name'=>'required',
            'desc'=>'required',
            'modules'=>'required|array'
        ]);
        $saveCombo=Combo::create(request(['name','desc']));
        if ($saveCombo){
            $store=$this->storeMoudle(request('modules'),$saveCombo);
            return response()->json(["status"=>"success","msg"=>"保存成功" ]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }
    public function create(){
        $modules=Module::all();
        return view("admin/combo/create",compact('modules'));
        return response()->json(["status"=>"success","data"=>$modules]);
    }
    public function storeMoudle($modules,$combo){
        $modules=Module::findMany($modules);
        $hasModules=$combo->module;
        $adds=$modules->diff($hasModules);
        foreach ($adds as $add){
            $save=$combo->assignModule($add);
        }
        $detachs=$hasModules->diff($modules);
        foreach ($detachs as $detach){
           $delete=$combo->detachModule($detach);
        }
        return compact('save');
    }
    public function delete(){
        $combo_id=request('combo_id');
        $combo=Combo::find($combo_id);
        $hasModules=$combo->module;
        foreach($hasModules as $hasModule){
            $combo->deleteModule($hasModule->id);
        }
        $delete=$combo->delete();
        if ($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }
}