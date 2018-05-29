<?php

namespace App\Api\Controllers\Answers;

use App\Models\Answers\Activitie;
use Illuminate\Http\Request;
use App\Models\Answers\Question;
use App\Api\Controllers\Controller;
use App\Http\Requests\Answers\QusetionRequest;
class QuestionController extends Controller
{
        public function index()
        {
            $pagesize=config('common.pagesize');
            $question=Question::paginate($pagesize);
            return response()->json(["status"=>"success","data"=>$question]);
        }

        public function show()
        {
            $question = Activity::find('id', requset()->question);
            $status = $question ? 'success' : 'error';
            return response()->json(['status' => $status, 'data' => $question]);
        }

        public function store(QusetionRequest $request){
            $questions=request('questions');
            $answer=json_encode(request('answer'));
            $positive=request('positive');
            $depot_id=request('depot_id');
            $type=request('type');
            $save=Question::create(compact('questions','answer','positive','depot_id'
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
            $questions=request('questions');
            $answer=json_encode(request('answer'));
            $positive=request('positive');
            $depot_id=request('depot_id');
            $type=request('type');
            $update=Question::where('id',$question_id)->update(compact('questions','answer','positive','depot_id'
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

        public function randomQus()
        {
            $activity=Activitie::find(request('activity_id'));
            $question_number=$activity->question_number;
            $depot=$activity->depot;
            $questions=Question::whereIn('depot_id',$depot)->inRandomOrder()->limit($question_number)->get()->toArray();
            return response()->json(["status"=>"success","data"=>$questions]);
        }
}
