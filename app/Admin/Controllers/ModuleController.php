<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 16:01
 */

namespace App\Admin\Controllers;
use App\Admin\Controllers\Controller;
use App\Models\Commons\Module;
class ModuleController extends Controller
{
     public function index(){
         $module=Module::all();
         return $module;
     }
     public function store(){
         $this->validate(request(),[
             'name'=>'required',
             'desc'=>'required',
         ]);
         $save=Module::create(request(['name','desc']));
         return $save;
     }
     public function create(){
         return view('admin/module/create');
     }
     public function delete(Module $module){
            $combos=$module->combo;
            foreach ($combos as $combo){
                $module->deleteCombo($combo);
            }
            $module->delete();
     }
}