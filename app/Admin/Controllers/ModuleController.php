<?php
/**
 * Created by PhpStorm.
 * User: 29673
 * Date: 2018/4/20
 * Time: 16:01
 */

namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Commons\Module;
class ModuleController extends Controller
{
     public function query(){
         $module=Module::all();
         return $module;
     }
     public function create(){
         $this->validate(request(),[
             'name'=>'required',
             'desc'=>'required',
         ]);
         $save=Module::create(request(['name','desc']));
         return $save;
     }
     public function createIndex(){
         return view('admin/module/create');
     }
}