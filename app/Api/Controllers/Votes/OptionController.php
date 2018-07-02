<?php

namespace App\Api\Controllers\Votes;
use App\Models\Votes\Option;
use App\Api\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OptionController extends Controller
{
    public function index()
    {
        $voteID = request()->voteID;
        $data = Option::where('vote_id', $voteID)->withCount('fans')->get();
        foreach ($data as $item) {
            $fansCount = $item->fans_count;
            if($item->total < $fansCount){
                $item->total = $fansCount;
            }
            unset($item->fans_count);
        }
        return response()->json(['status' => 'success', 'data' => $data ]);
    }

    public function store()
    {
        $list = request([ 'vote_id','options']);
        $voteID = $list['vote_id'];
        $options = $list['options'];
        $now = Carbon::now();
        $data = [];
        foreach ($options as $option){
            array_push($data,['vote_id'=>$voteID,'content'=>$option['content'],'total'=>$option['total'],'created_at'=>$now,'updated_at'=>$now]);
        }
        DB::beginTransaction();
        try{
            Option::where('vote_id', $voteID)->delete();
            DB::table('votes_options')->insert($data);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '新增失败！']);
        }
        return response()->json(['status' => 'success', 'msg' => '新增成功！']);
    }

}