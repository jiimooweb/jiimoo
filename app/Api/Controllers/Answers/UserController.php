<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\AnswersUser;
use App\Api\Controllers\Controller;

class UserController extends Controller
{

        public function index(){
           $ssss= AnswersUser::get();
        }
        public function edite(){}
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
