<?php

namespace App\Admin\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Activitie;
use App\Admin\Controllers\Controller;

class ActivityController extends Controller
{
    public function index(){
        $activite=Activitie::orderBy('created_at','desc')->paginate(6);
        return $activite;
    }
    public function create(){
        return view('admin/answers/activity/create');
    }
    public function store(){
        $this->validate(request(),[
            'name' => 'required|unique:answers_activities,name',
            'reward' => 'required',
            'start_time' => 'required_with:end_time',
            'end_time' => 'required_with:start_time',
            'reward_total' => 'required',
        ]);
        $save=Activitie::create(request(['name','start_time','end_time','reward','reward_total']));
        return $save;
    }
    public function edit(){}

    public function update(Activitie $activitie){
          $this->validate(request(),[
              'name' => 'required',
              'start_time' => 'required_if:end_time',
              'end_time' => 'required_if:start_time',
              'reward' => 'required',
              'reward_total' => 'required',
              'status'=>Rule::in(['N']),
          ]);
          $update=$activitie->
          update(request(['name','start_time','end_time','reward','reward_total','status']));
          return $update;
    }
    public function delete(Activity $activity)
    {
        if ($activity->status=='N'&&$activity->depot==null){
            $activity->delete();
            return  "删除";
        }else{
            return "不";
        }
    }
}
