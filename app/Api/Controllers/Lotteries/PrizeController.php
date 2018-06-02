<?php

namespace App\Api\Controllers\Lotteries;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use Validator;
use App\Models\Lotteries\Prize;
use App\Http\Requests\Lotteries\PrizeRequest;
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
        $save=Prize::create(request(['coupo_id','probably']));
        if ($save){
            return response()->json(["status"=>"success","msg"=>"保存成功！"]);
        }else{
            return response()->json(["status"=>"error","msg"=>"保存失败！"]);
        }
    }

    public function update(PrizeRequest $request)
    {
        $prize_id=request('prize_id');
        $update=Prize::find($prize_id)->update(request(['coupo_id','probably']));
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
}