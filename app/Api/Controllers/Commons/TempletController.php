<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Models\Commons\Templet;
use App\Api\Controllers\Controller;
use App\Http\Requests\Commons\TempletRequest;

class TempletController extends Controller
{
    public function index()
    {
        //用户个人范文
        $xcx_id=session('xcx_id');
        $templet=Templet::where('xcx_id',$xcx_id)->get();
        return response()->json(['status' => 'success', 'data' => $templet]);
    }

    public function templetCombox(){
        //用户使用范文（包过自身的以及公用的）
        $xcx_id=session('xcx_id');
        $templet=Templet::orWhere('xcx_id',$xcx_id)->orWhere('xcx_id',$xcx_id)->get();
        return response()->json(['status' => 'success', 'data' => $templet]);
    }

    public function store(TempletRequest $request)
    {
        $xcx_id=session('xcx_id');
        $data=request(['desc','content']);
        $data['xcx_id']=$xcx_id;
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
