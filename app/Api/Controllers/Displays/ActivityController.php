<?php

namespace App\Api\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Activity;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\ActivityRequest;

class ActivityController extends Controller
{
    
    public function index() 
    {
        $activitys = Activity::orderBy('created_at','desc')->withCount(['signlists'])->paginate(config('common.pagesize'));   
        return response()->json(['status' => 'success', 'data' => $activitys]);
    }

    public function store(ActivityRequest $request) 
    {   
        $data = request([
            'name', 'places', 'content'
        ]);

        $sign_time = explode('~',request('sign_time'));
        $data['sign_start_time'] =trim($sign_time[0]);
        $data['sign_end_time'] =trim($sign_time[1]);

        $activity_time = explode('~',request('activity_time'));
        $data['start_time'] =trim($activity_time[0]);
        $data['end_time'] =trim($activity_time[1]);
        if(Activity::create($data)) {
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);                             
        }

        return response()->json(['status' => 'error', 'msg' => '新增失败！']);                           
        
    }

    public function show()
    {
        $activity = Activity::find('id', requset()->activity);
        $status = $activity ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $activity]);   
    }

    public function update(ActivityRequest $request)
    {
        $data = request([
            'name', 'places', 'content'
        ]);

        $sign_time = explode('~',request('sign_time'));
        $data['sign_start_time'] =trim($sign_time[0]);
        $data['sign_end_time'] =trim($sign_time[1]);

        $activity_time = explode('~',request('activity_time'));
        $data['start_time'] =trim($activity_time[0]);
        $data['end_time'] =trim($activity_time[1]);
        
        if(Activity::where('id', request()->activity)->update($data)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);                              
        }

        return response()->json(['status' => 'error', 'msg' => '更新失败！']);                           
        
        

    }

    public function destroy()
    {
        if(Activity::where('id', request()->activity)->delete()) {
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);                              
        }
        return response()->json(['status' => 'error', 'msg' => '删除失败！']);     
    }

}
