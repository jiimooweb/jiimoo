<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\Templet;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\TempletRequest;

class AdminTempletController extends Controller
{
    public function index()
    {
        $templet=Templet::where('xcx_id','0')->get();
        return response()->json(['status' => 'success', 'data' => $templet]);
    }

    public function store(TempletRequest $request)
    {
        $data=request(['desc','content']);
        $save=Templet::create($data);
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(TempletRequest $request)
    {
        $id=request()->templet;
        $update=Templet::find($id)->update(request(['desc','content']));
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
