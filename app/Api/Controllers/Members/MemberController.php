<?php

namespace App\Api\Controllers\Members;

use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Members\Member;
use App\Api\Controllers\Controller;
use App\Http\Requests\Members\MemberRequest;

class MemberController extends Controller
{
    
    public function index() 
    {
        $members = Member::all();
        return response()->json(['status' => 'success', 'data' => $members]);   
    }

    public function store(MemberRequest $request) 
    {   
        $data = request()->all();   
        $data['card_id'] = time();
        if(Member::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $member = Member::find(request()->member);
        $status = $member ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $member]);   
    }

    public function update(MemberRequest $request)
    {
        $data = request()->all();                      
        if(Member::where('id', request()->member)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Member::where('id', request()->member)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

    //加入会员
    public function join(MemberRequest $request) 
    {
        $data = request()->all();   
        $data['fan_id'] = Token::getUid();
        $data['card_id'] = time();
        if(Member::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '领取成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '领取失败！']); 
    }

}
