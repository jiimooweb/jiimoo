<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Suggest;
use App\Admin\Controllers\Controller;

class SuggestController extends Controller
{
    
    public function index() 
    {
        $suggests = Suggest::orderBy('created_at','desc')->paginate(config('common.pagesize'));      
        return response()->json(['status' => 'success', 'data' => $suggests]);
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
