<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 15:57
 */

namespace App\Admin\Controllers;
use App\Admin\Controllers\Controller;
use App\Models\Commons\Combo;
use App\Models\Commons\Module;
class ComboControlle extends Controller
{
    public function index(){
        $combo=Combo::all();
        return view('',compact('combo'));
    }
    public function store(){
        $this->validate(request(),[
            'name'=>'required',
            'desc'=>'required',
            'modules'=>'required|array'
        ]);
        $saveCombo=Combo::create(request(['name','desc']));
        if ($saveCombo){
            $sss=$this->storeMoudle(request('modules'),$saveCombo);
            return $sss;
        }
        return "失败";
    }
    public function create(){
        $modules=Module::all();
        return view('admin/Combo/create',compact('modules'));
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
}