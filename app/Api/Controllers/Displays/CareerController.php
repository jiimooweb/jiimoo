<?php

namespace App\Api\Controllers\Displays;

use Illuminate\Http\Request;
use App\Models\Displays\Career;
use App\Api\Controllers\Controller;
use App\Http\Requests\Displays\CareerRequest;

class CareerController extends Controller
{
    public function index()
    {
        $careers=Career::paginate(config('common.pagesize'));
        return response()->json(['status' => 'success', 'data' => $careers]);
    }

    public function store(CareerRequest $request)
    {
        $save=Career::create(request(['career']));
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(CareerRequest $request)
    {
//        $id=request()->career;
//        return $id;
//        $update=Career::find($id)->update(request(['career']));
//        if ($update){
//            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
//        }else{
//            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
//        }
    }

    public function destroy()
    {
        $id=request()->career;
        $career=Career::find($id);
        $hasApplicants=$career->applicant;
        foreach ($hasApplicants as $hasApplicant ){
            $career->detachApplicant($hasApplicant);
        }
        $delete=$career->delete();
        if($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }
   
    public function xcxShow(){
        $careers_id=request()->careers;
        if($careers_id){
            $careers=Career::find($careers_id)->with((['applicant' => function ($query) {
                $page=request('page');
                $pagesize=config('common.pagesize');
                $query->where('status','Y')->orderBy('rank', 'desc')->paginate($pagesize);
            }]))->get();
        }else{
            $pagesize=config('common.pagesize');
            $careers=Career::with('applicant')->with((['applicant' => function ($query) {
                $page=request('page');
                $pagesize=config('common.pagesize');
                $query->where('status','Y')->orderBy('rank', 'desc')->paginate($pagesize);
            }]))->get();
        }
        return response()->json(['status' => 'success', 'data' => $careers]);
    }
}
