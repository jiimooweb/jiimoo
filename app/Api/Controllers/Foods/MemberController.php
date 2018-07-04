<?php

namespace App\Api\Controllers\Foods;

use App\Models\Foods\Member;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Http\Requests\Foods\MemberRequest;

class MemberController extends Controller
{
    
    public function index() 
    {
        $members = Member::withCount('products')->get();
        
        return response()->json(['status' => 'success', 'data' => $members]);
    }

    public function store(MemberRequest $requset) 
    {   
        $member = Member::create(request(['name']));
        if($member) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $member]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        $member = Member::withCount('products')->find(request()->member);             
        $status = $member ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $member]);
    }

    public function update(MemberRequest $requset) 
    {
        if(Member::where('id', request()->member)->update(request(['name']))) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);               
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);           
    }

    public function destroy()
    {   
        // TODO:判断删除权限
        if(Member::where('id', request()->member)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);               
        }
        
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);         
    }
}
