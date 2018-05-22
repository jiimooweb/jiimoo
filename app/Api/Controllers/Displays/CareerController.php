<?php

namespace App\Api\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Career;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\CareerRequest;

class CareerController extends Controller
{
    public function index()
    {
        $careers=Career::paginate(config('common.pagesize'));
        return response()->json(['status' => 'success', 'data' => $careers]);
    }

    public function store(CareerRequest $request)
    {
        $save=Career::create($request);
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(CareerRequest $request)
    {
        $id=request()->career;
        $update=Career::find($id)->update($request);
        if ($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }

    public function destroy()
    {
        $id=request()->career;
        $delete=Templet::find($id)->delete();
        if($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }
   
    public function xcxShow(){
        $careers=Career::with('applicant')->get();
        return response()->json(['status' => 'success', 'data' => $careers]);
    }
}
