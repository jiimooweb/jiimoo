<?php

namespace App\Api\Controllers\Commons;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Models\Commons\MiniProgram;
use App\Http\Requests\Commons\MiniProgramRequest;

class MiniProgramController extends Controller
{
    
    public function index() 
    {
        $miniprogram = MiniProgram::orderBy('created_at','desc')->get();      
        return response()->json(['status' => 'success', 'data' => $miniprogram]);
    }

    public function store(MiniProgramRequest $request) 
    {
        if(MiniProgram::create(request()->all())){
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                  
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);               
    }


    public function update(MiniProgramRequest $request) 
    {
        if(MiniProgram::where('id', request()->miniprogram)->update(request()->all())){
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                  
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);               
    }


    public function destroy()
    {
        // TODO:判断删除权限
        if(MiniProgram::where('id', request()->miniprogram)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);
    }
}
