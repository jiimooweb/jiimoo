<?php

namespace App\Api\Controllers\Answers;

use App\Models\Answers\Activitie;
use App\Models\Commons\Fan;
use Illuminate\Http\Request;
use App\Models\Answers\AnswersUser;
use App\Api\Controllers\Controller;
use App\Models\Answers\ActivitieFans;
class FanController extends Controller
{

        public function index()
        {

        }

        public function fanShow(){
            $fan_id=Token::getUid();;
            $activity_id=request('activity_id');
            $answerFan=AnswersUser::find($fan_id);
            $fan=Fan::find($answerFan->fans_id);
            $activityFan=ActivitieFans::where('fan_id',$fan_id)->where('activity_id',$activity_id)->first();
            return response()->json(["status"=>"success","data"=>compact('answerFan','fan','activityFan')]);
        }

        public function store()
        {
            $fans_id=Token::getUid();;
            $save=AnswersUser::create(compact('fans_id'));
            if ($save){
                return response()->json(["status"=>"success","msg"=>"保存成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"保存失败！"]);
            }
        }

        public function update()
        {
            $valid=Validator::make(request()->all(), [
                'take_part'=>'required',
                'number_reward'=>'required',
                'reward'=>'required',
                'acityvity_id'=>'required',
                'fan_id'=>'required'
            ]);
            if($valid->errors()->count()){
                return response()->json(["status"=>"error","data"=>$valid->errors()]);
            }
            $fan_id=Token::getUid();;
            $activity=Activitie::find(request('acityvity_id'));
            $user=AnswersUser::where('fans_id',$fan_id)->update(request(['take_part','number_reward','reward']));
            if($activity->hasFan($fan_id)){
                ActivitieFans::where('fan_id',$fan_id)->where('activity_id',$activity->id)
                    ->update(request('partake'));
            }else{
                $activity->assignFan($fan_id);
            }
        }
}
