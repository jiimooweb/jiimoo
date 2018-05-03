<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 16:01
 */

namespace App\Api\Controllers;
use App\Api\Controllers\Controller;
use App\Models\Commons\Module;
class ModuleController extends Controller
{
     public function index(){
         $module=Module::all();
         return response()->json(["status"=>"success","data"=>$module]);
     }
     public function store(){
         $this->validate(request(),[
             'name'=>'required',
             'desc'=>'required',
         ]);
         $save=Module::create(request(['name','desc']));
         if ($save){
             return response()->json(["status"=>"success","msg"=>"保存成功！"]);
         }else{
             return response()->json(["status"=>"error","msg"=>"保存失败！"]);
         }
     }
     public function create(){
         return view('admin/module/create');
     }
     public function delete(){
            $module_id=request('module_id');
            $module=Module::find($module_id);
            $combos=$module->combo;
            foreach ($combos as $combo){
                $module->deleteCombo($combo->id);
            }
            $delete=$module->delete();
             if ($delete){
                 return response()->json(["status"=>"success","msg"=>"删除成功！"]);
             }else{
                 return response()->json(["status"=>"error","msg"=>"删除失败！"]);
             }
     }
}