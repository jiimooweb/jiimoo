<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Question;
use App\Api\Controllers\Controller;
use App\Http\Requests\Answers\QusetionRequest;
class QuestionController extends Controller
{
        public function index()
        {
            $depot_id=request('depot_id');
            $question=Question::where('depot_id',$depot_id)->paginate(15);
            return $question;
        }

        public function store(QusetionRequest $request){
            $question=request('question');
            $answer=json_encode(request('answer'));
            $positive=request('positive');
            $depotId=request('depot_id');
            $type=request('type')?request('type'):"";
            $save=Question::create(compact('question','answer','positive','depotId'
            ,'type'));
            if ($save){
                return response()->json(["status"=>"success","msg"=>"保存成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"保存失败！"]);
            }
        }

        public function update(QusetionRequest $request)
        {
            $question_id=request()->question;
            $question=Question::find($question_id);
            $answer=json_encode(request('answer'));
            $positive=request('positive');
            $depotId=request('depot_id');
            $type=request('type')?request('type'):"";
            $update=$question->update(compact('question','answer','positive','depotId'
                ,'type'));
            if ($update){
                return response()->json(["status"=>"success","msg"=>"修改成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"修改失败！"]);
            }
        }

        public function destroy(){
            $question_id=request()->question;
            $question=Question::find($question_id);
            $delete=$question->delete();
            if($delete){
                return response()->json(["status"=>"success","msg"=>"删除成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"删除失败！"]);
            }
        }
}
