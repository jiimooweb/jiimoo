<?php

namespace App\Api\Controllers\Displays;

use App\Models\Commons\Fan;
use Illuminate\Http\Request;
use App\Models\Displays\Applicant;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\ApplicantRequest;
use App\Models\Displays\Career;
use App\Models\Displays\FanApplicant;
class ApplicantController extends Controller
{
    public function index()
    {
        $applicant=Applicant::paginate(config('common.pagesize'));
        return response()->json(['status' => 'success', 'data' => $applicant]);
    }

    public function show(){
        $like=request('like');
        $applicant=Applicant::orWhere('name','like',$like)->orWhere('duty','like',$like)->
        where('status','Y')->withCount('fans')->get();
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
        $valid=Validator::make(request()->all(), [
            'applicant_id'=>'required',
            'fan_id'=>'required'
        ]);
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $save=FanApplicant::create(request(['applicant_id','fan_id']));
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function disCollection()
    {
        $valid=Validator::make(request()->all(), [
            'applicant_ids'=>'required|array',
            'fan_id'=>'required'
        ]);
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $fan=Fan::find(request('fan_id'));
        $applicants=Applicant::findMany(request('applicant_ids'));
        foreach ($applicants as $applicant){
            $fan->detchApplicant($applicant);
        }
        return response()->json(["status"=>"success","msg"=>"取消成功！"]);
    }

    public function showCollection()
    {
        $fan_id=request()->fan;
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
