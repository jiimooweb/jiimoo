<?php

namespace App\Api\Controllers\Lotteries;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use Validator;
use App\Models\Lotteries\Activity;
use App\Models\Lotteries\Prize;
use App\Http\Requests\Lotteries\ActivityRequest;
class ActivityController extends Controller
{
    public function index()
    {
        $pagesize=config('common.pagesize');
        $activites=Activity::orderBy('created_at','desc')->paginate($pagesize);
        return response()->json(["status"=>"success","data"=>$activites]);
    }

    public function store(ActivityRequest $request)
    {
        $save=Activity::create(request(['name','start_time','end_time','partake']));
        $prizes=request('prizes');
        if ($prizes){
            $prizes=Prize::findMany($prizes);
            $activitiy=Activity::find($save->id);
            foreach ($prizes as $prize){
                $activitiy->assignPrize($prize);
            }
        }
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function choicePrize()
    {
        $prizes=Prize::orderBy('created_at','desc')->get();
        $activity_id=request('activity_id');
        if($activity_id){
            $activity=Activity::find($activity_id);
            $hasPrizes=$activity->prizes;
        }else{
            $hasPrizes="";
        }
        return response()->json(["status"=>"success","data"=>compact('hasPrizes','prizes')]);
    }

    public function update(ActivityRequest $request)
    {
        $activity_id=request()->activity;
        $activity=Activity::find($activity_id);
        $prizes=request('prizes');
        if ($prizes){
            $depots=Prize::findMany($prizes);
            $hasPrizes=$activity->prizes;
            $adds=$prizes->diff($hasPrizes);
            foreach ($adds as $add){
                $activity->assignPrize($add);
            }
            $deatchs=$hasPrizes->diff($prizes);
            foreach ($deatchs as $deatch){
                $activity->detachPrize($deatch);
            }
        }
        $update=$activity->update(request(['name','start_time','end_time','partake','status']));
        if ($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }

    public function destroy()
    {
        $activity_id=request()->activity;
        $activity=Activity::find($activity_id);
        $hasPrizes=$activity->prizes;
        foreach ($hasPrizes as $hasPrize ){
            $activity->detachCareer($hasPrize);
        }
        $delete=$activity->delete();
        if ($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }

    }
}
