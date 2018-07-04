<?php

namespace App\Api\Controllers\Foods;

use App\Services\Token;
use App\Models\Foods\Member;
use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use App\Http\Requests\Foods\MemberRequest;

class MemberController extends Controller
{
    
    public function index() 
    {
        $members = Member::get();
        
        return response()->json(['status' => 'success', 'data' => $members]);
    }

    public function store(MemberRequest $requset) 
    {   
        $data = request()->all();
        $data['fan_id'] = Token::getUid();
        $member = Member::create($data);
        if($member) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！', 'data' => $member]);               
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);           
    }
    
    public function show() 
    {
        $member = Member::find(request()->member);             
        $status = $member ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $member]);
    }

    public function update(MemberRequest $requset) 
    {
        if(Member::where('id', request()->member)->update(request()->all())) {
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
