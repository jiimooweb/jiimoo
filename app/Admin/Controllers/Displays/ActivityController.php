<?php

namespace App\Admin\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Activity;
use App\Admin\Controllers\Controller;

class ActivityController extends Controller
{
    
    public function index() 
    {
        $activitys = Activity::orderBy('created_at','desc')->withCount(['signlists'])->paginate(10);   

        return response()->json(['data' => $activity]);
        
    
    }

    public function create() 
    {
        // return view('admin.displays.activity.create');
    }

    public function store() 
    {   
        
        $this->validate(request(),[
            'name' => 'required',
            'places' => 'required|integer',
            'sign_time' => 'required',
            'activity_time' => 'required',
            'content' => 'required',
        ]);

        $data = request([
            'name', 'places', 'content'
        ]);

        $sign_time = explode('~',request('sign_time'));
        $data['sign_start_time'] =trim($sign_time[0]);
        $data['sign_end_time'] =trim($sign_time[1]);

        $activity_time = explode('~',request('activity_time'));
        $data['start_time'] =trim($activity_time[0]);
        $data['end_time'] =trim($activity_time[1]);
        $activity = Activity::create($data);

        return response()->json(['data' => $activity]);
    }

    public function show(Activity $activity)
    {
        // TODO: 待开发      
        // return view('admin.displays.activity.show',compact('activity'));
        return response()->json(['data' => $activity]);
        
    }

    public function edit(Activity $activity) 
    {      
        return response()->json(['data' => $activity]);
        
    }

    public function update(Activity $activity)
    {
        $this->validate(request(),[
            'name' => 'required',
            'places' => 'required|integer',
            'sign_time' => 'required',
            'activity_time' => 'required',
            'content' => 'required',
        ]);

        $data = request([
            'name', 'places', 'content'
        ]);

        $sign_time = explode('~',request('sign_time'));
        $data['sign_start_time'] =trim($sign_time[0]);
        $data['sign_end_time'] =trim($sign_time[1]);

        $activity_time = explode('~',request('activity_time'));
        $data['start_time'] =trim($activity_time[0]);
        $data['end_time'] =trim($activity_time[1]);
        
        $activity = Activity::where('id', $activity->id)->update($data);

        return response()->json(['data' => $activity]);
        

    }

    public function destroy(Activity $activity)
    {
        // TODO:判断删除权限
        $activity->delete();
        // return redirect('admin/displays/activitys');
        return response()->json(['data' => $activity]);
        
    }

}
