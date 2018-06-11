<?php

namespace App\Api\Controllers\Displays;

use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Displays\Career;
use App\Models\Displays\Applicant;
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
        $careers_id=request()->career;
        $uid = Token::getUid();
        $page = request('page') ?? 1;
        $pagesize = config('common.pagesize');
        $offset = ($page - 1) * $pagesize;
        if($careers_id){
            $careers=Career::with((['applicant' => function ($query) {
                $query->where('status','Y')->withCount(['fans'])->offset($offset)->limit($pagesize)->orderBy('rank', 'desc')->get();
            }]))->find($careers_id);            
            $applicants = $careers->applicant->load('fans', 'career');
        }else{
            $applicants = Applicant::withCount(['fans'])->offset($offset)->limit($pagesize)->get()->load('fans','career')->toArray();
        }

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
}
