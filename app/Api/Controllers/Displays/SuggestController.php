<?php

namespace App\Api\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Suggest;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\SuggestRequest;

class SuggestController extends Controller
{
    
    public function index() 
    {
        $suggests = Suggest::orderBy('created_at','desc')->paginate(config('common.pagesize'));      
        return response()->json(['status' => 'success', 'data' => $suggests]);
    }

    public function store(SuggestRequest $request) 
    {
        if(Suggest::create(request()->all())){
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                  
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);               
    }


    public function update(SuggestRequest $request) 
    {
        if(Suggest::where('id', request()->comment)->update(request()->all())){
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                  
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);               
    }


    public function destroy()
    {
        // TODO:判断删除权限
        if(Suggest::where('id', request()->suggest)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);   
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);
    }
}
