<?php

namespace App\Api\Controllers\Displays;

use App\Services\Token;
use App\Models\Commons\Fan;
use Illuminate\Http\Request;
use App\Models\Displays\Career;
use App\Models\Displays\Applicant;
use App\Api\Controllers\Controller;
use App\Models\Displays\FanApplicant;
use App\Http\Requests\Displays\ApplicantRequest;

class ApplicantController extends Controller
{
    public function index()
    {
        $uid = Token::getUid();
        $page = request()->get('page') ?? 1;
        $pagesize = config('common.pagesize');
        $offset = ($page - 1) * $pagesize;
        $applicants = Applicant::withCount(['fans'])->offset($offset)
        ->limit($pagesize)->get()->load('fans','career')->toArray();
        foreach($applicants as &$applicant) {
            foreach($applicant['fans'] as $fan) {
                if($fan['id'] == $uid) {
                    $applicant['collection'] = 1;
                    break;
                }
            }  
            unset($applicant['fans']);
        }
          
        return response()->json(['status' => 'success', 'data' => $applicants]);
    }

    public function show()
    {
        $uid = Token::getUid();
        $applicant=Applicant::find(request()->applicant)->load('fans')->toArray();
        foreach($applicant['fans'] as $fan) {
            if($fan['id'] == $uid) {
                $applicant['collection'] = 1;
                break;
            }
        }  
        unset($applicant['fans']);
        
        if ($applicant){
            return response()->json(["status"=>"success","data"=>$applicant]);
        }else{
            return response()->json(["status"=>"error"]);
        }
    }

    public function show_by_filter(){
        $like=request('like');
        $career_id=request('career_id');
        $pagesize=config('common.pagesize');
        $applicant=Applicant::where('status',0)->withCount('fans')
            ->with('career');
        if($like){
            $like="%".$like."%";
            $applicant=$applicant->Where('name','like',$like)->orWhere('duty','like',$like);
        }else if($career_id){
            $applicant=$applicant->whereHas('career', function ($query) {
               $career_id=request('career_id');
                $query->where('career_id',$career_id);
            });
        }
        $applicant=$applicant->orderBy('rank', 'desc')->paginate($pagesize);
        return response()->json(['status' => 'success', 'data' => $applicant]);
    }

    public function store(ApplicantRequest $request)
    {
        $save=Applicant::create(request(['name','experience','duty','resume','photo','salary']));
        $careers_id=request('careers');
        if ($careers_id){
            $careers=Career::findMany($careers_id);
            foreach ($careers as $career){
                $save->assginCareer($career);
            }
        }
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(ApplicantRequest $request)
    {
        $applicant_id=request()->applicant;
        $applicant=Applicant::find($applicant_id);
        $careers_id=request('careers');
        if ($careers_id){
            $careers=Career::findMany($careers_id);
            $hasCarrer=$applicant->career;
            $adds=$careers->diff($hasCarrer);
            foreach ($adds as $add){
                $applicant->assginCareer($add);
            }
            $detchs=$hasCarrer->diff($careers);
            foreach ($detchs as $detch){
                $applicant->detachCareer($detch);
            }
        }
        $update=$applicant->update(request(['name','experience','duty','resume','photo','salary','status'
        ,'evaluate','rank']));
        if($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }

    public function destroy()
    {
        $applicant_id=request()->applicant;
        $applicant=Applicant::find($applicant_id);
        $hasCareers=$applicant->career;
        foreach ($hasCareers as $hasCareer ){
            $applicant->detachCareer($hasCareer);
        }
        $delete=$applicant->delete();
        if ($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }

    public function collection()
    {
        $valid = \Validator::make(request()->all(), [
            'applicant_id'=>'required',
        ]);
        $fan_id = Token::getUid();
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $save=FanApplicant::create(['applicant_id' => request('applicant_id'),'fan_id' => $fan_id ]);
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function disCollection()
    {
        $valid=\Validator::make(request()->all(), [
            'applicant_id'=>'required',
        ]);
        $fan_id = Token::getUid();
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $fan=Fan::find($fan_id);
        $applicants=Applicant::findMany(request('applicant_id'));
        foreach ($applicants as $applicant){
            $fan->detchApplicant($applicant);
        }
        return response()->json(["status"=>"success","msg"=>"取消成功！"]);
    }

    public function showCollection()
    {
        $fan_id=Token::getUid();
        $fan=Fan::find($fan_id);
        $applicant=$fan->displayApplicant;
        return response()->json(['status' => 'success', 'data' => $applicant]);
    }

    public function addClick()
    {
        $valid=Validator::make(request()->all(), [
            'click_number'=>'required|int',
            'applicant_id'=>'required'
        ]);
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $update=Applicant::find(request('applicant_id'))->update(request(['click_number']));
        if($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }
}
