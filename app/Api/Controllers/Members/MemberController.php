<?php

namespace App\Api\Controllers\Members;

use App\Utils\Member;
use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Members\MemberTag;
use App\Models\Members\MiniMember;
use App\Api\Controllers\Controller;
use App\Http\Requests\Members\MemberRequest;

class MemberController extends Controller
{
    
    public function index(Request $request) 
    {
        $keyword = $request->name;
        $group_id = $request->group_id;
        $tag_id = $request->tag_id;
        $members = MiniMember::when($keyword , function($query) use ($keyword) {
            return $query->where('name', 'like', '%'.$keyword.'%')->orWhere('mobile', 'like', '%'.$keyword.'%');
        })->when($group_id , function($query) use ($group_id) {
            return $query->where('group_id', $group_id);
        })->with(['tags' => function ($query) use ($tag_id){
            $query->when($tag_id, function($query) use ($tag_id) {
                return $query->where('tag_id', $tag_id);
            });
        }])->get()->toArray();
        
        if($tag_id) {
            foreach($members as $k => &$member) {
                if(empty($member['tags'])) {
                    unset($members[$k]);
                }
            }
        }

        return response()->json(['status' => 'success', 'data' => $members]);   
    }

    public function store(MemberRequest $request) 
    {   
        $data = request()->all();   
        $data['card_id'] = time();
        if(MiniMember::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
    }

    public function show()
    {
        $member = MiniMember::find(request()->member);
        $status = $member ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $member]);   
    }

    public function update(MemberRequest $request)
    {
        $data = request()->all();                      
        if(MiniMember::where('id', request()->member)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(MiniMember::where('id', request()->member)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

    public function group() {
        if(MiniMember::where('id', request()->member)->update(['group_id' => request()->group_id])) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }
        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    //加入会员
    public function join(MemberRequest $request) 
    {
        $data = request()->all();   
        $data['fan_id'] = Token::getUid();
        $data['card_id'] = session('xcx_id').time();
        if(MiniMember::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '领取成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '领取失败！']); 
    }

    public function changeIntegral()
    {
        $member_id = request('member_id');        
        $value = request('value');
        MiniMember::changeIntegral($member_id, $value);
        return response()->json(['status' => 'success', 'msg' => '更新成功！']);  
    }

    public function changeMoney()
    {
        $member_id = request('member_id');
        $value = request('value');        
        MiniMember::changeMoney($member_id, $value);
        return response()->json(['status' => 'success', 'msg' => '更新成功！']);  
    }

    public function addTag()
    {
        if(MemberTag::create(request()->all())) {
            return response()->json(['status' => 'success', 'msg' => '添加成功！']);  
        }

        return response()->json(['status' => 'error', 'msg' => '添加失败！']);  
    }

    public function deleteTag()
    {
        if(MemberTag::where(request()->all())->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);  
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']); 
    }

}
