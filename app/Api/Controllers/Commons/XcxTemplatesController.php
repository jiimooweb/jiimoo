<?php

namespace App\Api\Controllers\Commons;

use App\Http\Requests\Commons\XcxTemplatesRequest;
use App\Models\Commons\Xcx;
use App\Models\Commons\XcxTemplates;
use App\Models\Wechat\Template;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;


class XcxTemplatesController extends Controller
{

    public function index(){
        $xcx_flag=request()->xcx_flag;
        $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
        $xcxTemplates=XcxTemplates::where('xcx_id',$xcx->id)->first();
        $xcxTemplates->templates=json_decode($xcxTemplates->templates);
        return response()->json(["status"=>"success","data"=>$xcxTemplates]);
    }

    public function store(XcxTemplatesRequest $request){
        $xcx_flag=request()->xcx_flag;
        $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
        $xcx_id=$xcx->id;
        $templates=request('templates');
        $templates=json_encode($templates);
        $save=XcxTemplates::create(compact('xcx_id','templates'));
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(XcxTemplatesRequest $request){
        $xcx_flag=request()->xcx_flag;
        $xcx=Xcx::where('xcx_flag',$xcx_flag)->first();
        $templates=request('templates');
        $templates=json_encode($templates);
        $update=XcxTemplates::where('xcx_id',$xcx->id)->first()->update(compact('templates'));
        if ($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }
}
