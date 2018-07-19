<?php

namespace App\Api\Controllers\Lotteries;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use Validator;
use App\Models\Lotteries\Prize;
use App\Http\Requests\Lotteries\PrizeRequest;
use App\Models\Lotteries\Activity;
class PrizeController extends Controller
{
    public function index()
    {
        $pagesize=config('common.pagesize');
        $prizes=Prize::with('coupon')
            ->orderBy('created_at','desc')->paginate($pagesize);
        return response()->json(["status"=>"success","data"=>$prizes]);
    }

    public function store(PrizeRequest $request)
    {
        $save=Prize::create(request(['activity_id','level','coupon_id','probably']));
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(PrizeRequest $request)
    {
        $prize_id=request('prize_id');
        $update=Prize::find($prize_id)->update(request(['level','coupon_id','probably']));
        if ($update){
            return response()->json(["status"=>"success","msg"=>"修改成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"修改失败！"]);
        }
    }

    public function destroy()
    {
        $prize_id=request('prize_id');
        $prize=Prize::find($prize_id);
        $hasActivities=$prize->activities;
        foreach ($hasActivities as $hasActivity ){
            $prize->detachActivity($hasActivity);
        }
        $delete=$prize->delete();
        if ($delete){
            return response()->json(["status"=>"success","msg"=>"删除成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"删除失败！"]);
        }
    }

    public function get_prizes(){
        $activity_id=request()->activity;
        $prizes=Prize::where('activity_id',$activity_id)->with('coupon')->get();
        $noProbably=100;
        foreach ($prizes as $prize){
            $prize['prize_name']=$prize['coupon']['name'];
            $prize['prize_desc']=$prize['coupon']['desc'];
            $prize['prize_thumb']=$prize['coupon']['thumb'];
            $noProbably=$noProbably-$prize['probably'];
            unset($prize['coupon']);
        }
        $prizes=$prizes->toArray();
        if($noProbably!='0'){
            $noPrize=array('id'=>'no','activity_id'=>$activity_id,'coupon_id'=>0,
                'probably'=>$noProbably,'prize_name'=>'感谢参与','prize_desc'=>'','prize_thumb'=>'');
            array_push($prizes,$noPrize);
        }
        return response()->json(["status"=>"success","data"=>$prizes]);
    }
}
