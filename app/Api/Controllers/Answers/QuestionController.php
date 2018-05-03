<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Question;
use App\Api\Controllers\Controller;

class QuestionController extends Controller
{
        public function index(){
            $question=Question::paginate(15);
            return $question;
        }
        public function create(){

        }
        public function store(){
            $this->validate(request(),[
                'question' => 'required',
                'answer' => 'required|array',
                'positive' => 'required',
                'depot_id'=>'required',
            ]);
            $question=request('question');
            $answer=json_encode(request('answer'));
            $positive=request('positive');
            $depotId=request('depot_id');
            $type=request('type')?request('type'):"";
            $save=Question::create(compact('question','answer','positive','$depotId'
            ,'type'));
            return $save;
        }
        public function edite(){}
        public function update(Question $question){
            $this->validate(request(),[
                'question' => 'required',
                'answer' => 'required|array',
                'positive' => 'required',
                'depot_id'=>'required',
            ]);
            $question=request('question');
            $answer=json_encode(request('answer'));
            $positive=request('positive');
            $depotId=request('depot_id');
            $type=request('type')?request('type'):"";
            $save=$question->update(compact('question','answer','positive','depotId'
                ,'type'));
        }
        public function delete(Question $question){

            $question->delete();
        }
}
