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
use App\Http\Requests\Admin\ComboRequest;
class ComboControlle extends Controller
{
    public function index()
    {
        $pagesize=config('common.pagesize');
        $combo=Combo::paginate($pagesize);
        return response()->json(["status"=>"success","data"=>$combo]);
    }
    public function store(ComboRequest $request)
    {
        $saveCombo=Combo::create(request(['name','desc']));
        if ($saveCombo){
            $store=$this->storeMoudle(request('modules'),$saveCombo);
            return response()->json(["status"=>"success","msg"=>"保存成功" ]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }
    public function update(ComboRequest $request)
    {
        $combo_id=request()->combo;
        $combo=Combo::where('id',$combo_id)->update(request(['name','	desc']));
        $combo=Combo::find($combo_id);
        $store=$this->storeMoudle(request('modules'),$combo);
        if($store&&$combo){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }
    public function create()
    {
        $modules=Module::all();
        return view("admin/combo/create",compact('modules'));
        return response()->json(["status"=>"success","data"=>$modules]);
    }
    public function storeMoudle($modules,$combo)
    {
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
        return compact('save','delete');
    }
    public function destroy()
    {
        $combo_id=request()->combo;
        $combo=Combo::find($combo_id);
        $hasModules=$combo->module;
        foreach($hasModules as $hasModule){
            $combo->detachModule($hasModule->id);
        }
        $delete=$combo->delete();
        if ($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }
}