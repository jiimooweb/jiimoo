<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\AnswersUser;
use App\Api\Controllers\Controller;

class FanController extends Controller
{

        public function index()
        {
           $ssss= AnswersUser::get();
        }

        public function update($fanid)
        {
            $this->validate(request(), [
                'win' => 'required|Boolean',
                'reward' => 'required',
            ]);
            $user=AnswersUser::where('fans_id',$fanid)->get();
            if($user){
                if(win){
                    $user->number_reward=$user->number_reward	+1;
                }
                $user->reward=$user->reward+request('reward');
                $user->take_part=$user->take_part+1;
                $user->save();
            }
        }
}
