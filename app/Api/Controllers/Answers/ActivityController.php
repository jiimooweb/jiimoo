<?php

namespace App\Api\Controllers\Answers;

use Illuminate\Http\Request;
use App\Models\Answers\Activitie;
use App\Api\Controllers\Controller;
use App\Http\Requests\Answers\ActivityRequest;
use App\Models\Answers\Depot;
use Validator;
class ActivityController extends Controller
{
    public function index(){
        $xcx_id=session('xcx_id');
        $pagesize=config('common.pagesize');
        $activite=Activitie::orderBy('created_at','desc')->paginate($pagesize);
        return response()->json(["status"=>"success","data"=>$activite]);
    }

    public function store(ActivityRequest $request)
    {
        $valid=Validator::make(request()->all(), [
            'name'=>'unique:answers_activities,name',
        ]);
        if($valid->errors()->count()){
            return response()->json(["status"=>"error","data"=>$valid->errors()]);
        }
        $save=Activitie::create(request(['name','start_time','end_time','reward','reward_total']));
        $depots=request('depots');
        if ($depots){
            $depots=Depot::findMany($depots);
           $activitie=Activitie::find($save->id);
            foreach ($depots as $depot){
                $activitie->assignDepot($depot);
            }
        }
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(ActivityRequest $request)
    {
        $activity_id=request()->activity;
        $activity=Activitie::find($activity_id);
        $update=$activity->update(request(['name','start_time','end_time','reward','reward_total','status']));
        $depots=request('depots');
        if ($depots){
            $depots=Depot::findMany($depots);
            $hasDepots=$activity->depot;
            $adds=$depots->diff($hasDepots);
            foreach ($adds as $add){
                $activity->assignDepot($add);
            }
            $deatchs=$hasDepots->diff($depots);
            foreach ($deatchs as $deatch){
                $activity->detachDepot($deatch);
            }
        }
        if ($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }

    public function choiceDepot(){
        $depot=Depot::all();
        $activity_id=request('activity_id');
        if($activity_id){
            $activity=Activitie::find($activity_id);
            $hasDepot=$activity->depot;
        }else{
            $hasDepot="";
        }
        return response()->json(["status"=>"success","msg"=>compact('hasDepot','depot')]);
    }

    public function destroy()
    {
        $activity_id=request()->activity;
        $activity=Activitie::find($activity_id);
        $hasDepots=$activity->depot;
        foreach ($hasDepots as $hasDepot){
            $activity->detachDepot($hasDepot);
        }
        if ($activity->status=='N'){
            $delete=$activity->delete();
            if($delete){
                return response()->json(["status"=>"success","msg"=>"删除成功！"]);
            }else{
                return response()->json(["status"=>"error","msg"=>"删除失败！"]);
            }
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }
}
