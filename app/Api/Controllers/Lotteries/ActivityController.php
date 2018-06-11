<?php

namespace App\Api\Controllers\Lotteries;

use Illuminate\Http\Request;
use App\Api\Controllers\Controller;
use Validator;
use App\Models\Lotteries\Activity;
use App\Models\Lotteries\Prize;
use App\Http\Requests\Lotteries\ActivityRequest;
use App\Services\Token;
class ActivityController extends Controller
{
    public function index()
    {
        $pagesize=config('common.pagesize');
        $page = request('page') ?? 1;
        $offset = ($page - 1) * $pagesize;
        $activites=Activity::orderBy('created_at','desc')->
        offset($offset)->limit($pagesize)->get()->toArray();;
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

    public function show_by_filter(){
        $pagesize=config('common.pagesize');
        $page = request('page') ?? 1;
        $offset = ($page - 1) * $pagesize;
        $activites=Activity::where('status',0)->with(['fans'=>function ($query){
            $fan_id = Token::getUid();
            $query->where('fan_id',$fan_id);
        }])->orderBy('created_at','desc')->
        offset($offset)->limit($pagesize)->get();
        foreach ($activites as $activite){
            if(count($activite['fans'])){
                if($activite->fans->partook>$activite->partake){
                    $activite['partake_flag']=0;
                }else{
                    $activite['partake_flag']=1;
                }
            }else{
                $activite['partake_flag']=1;
            }
        }
        return response()->json(["status"=>"success","data"=>$activites->toArray()]);
    }

    public function getPrizeResult()
    {
        $activity_id=request()->activity;
        $activity=Activity::find($activity_id);
        $prizes=$activity->prizes->toArray();
        $noProbably=100;
        foreach ($prizes as $prize){
            $noProbably=$noProbably-$prize['probably'];
        }
        $noPrize=array('id'=>'no','xcx_id'=>session('xcx_id'),'coupon_id'=>0,
            'probably'=>$noProbably);
        array_push($prizes,$noPrize);
        foreach ($prizes as $key => $val) {
            $arr[$val['id']] = $val['probably'];
        }
        $rid = $this->get_rand($arr); //根据概率获取奖项id
        $result='';
        foreach ($prizes as $prize){
            if($prize['id']==$rid){
                $result=$prize;
                break;
            }
        }
        if($result){
            return response()->json(["status"=>"success","data"=>$result]);
        }else{
            return response()->json(["status"=>"error","msg"=>"系统失误！"]);
        }
    }

    public function get_rand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }
}
