<?php

namespace App\Api\Controllers\Members;

use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Members\Group;
use App\Api\Controllers\Controller;
use App\Http\Requests\Members\GroupRequest;

class GroupController extends Controller
{
    
    public function index() 
    {
        $groups = Group::getGroup();
        return response()->json(['status' => 'success', 'data' => $groups]);   
    }

    public function store(GroupRequest $request) 
    {   
        $data = request()->all();  
        if(Group::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $group = Group::find(request()->group);
        $status = $group ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $group]);   
    }

    public function update(GroupRequest $request)
    {
        $data = request()->all();                      
        if(Group::where('id', request()->group)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                            
    }

    public function destroy()
    {
        if(Group::where('id', request()->group)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

    //设为默认
    public function setDefault() 
    {
        $groups = Group::get();
        foreach($groups as $group) {
            if($group->default == 1) {
                $group->default = 0;
                $group->save();
            }
        }

        if(Group::where('id', request()->group_id)->update(['default' => 1])) {
            return response()->json(['status' => 'success', 'msg' => '修改成功！']);                           
        }

        return response()->json(['status' => 'error', 'msg' => '修改失败！']); 
        
    }
    

}
