<?php

namespace App\Api\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Commons\Templet;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\TempletRequest;

class TempletController extends Controller
{
    public function index()
    {
        $xcx_id=session('xcx_id');
        $templet=Templet::orWhere('xcx_id','0')->orWhere('xcx_id',$xcx_id)->get();
        return response()->json(['status' => 'success', 'data' => $templet]);
    }

    public function store(TempletRequest $request)
    {
        $save=Templet::create($request);
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(TempletRequest $request)
    {
        $id=request()->templet;
        $update=Templet::find($id)->update($request);
        if ($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }

    public function destroy()
    {
        $id=request()->templet;
        $delete=Templet::find($id)->delete();
        if($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }
}
