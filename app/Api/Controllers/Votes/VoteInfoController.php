<?php

namespace App\Api\Controllers\Votes;

use App\Http\Requests\Votes\VoteAndOptStoreRequest;
use App\Models\Votes\Applicant;
use App\Models\Votes\Option;
use App\Services\Token;
use Illuminate\Http\Request;
use App\Models\Votes\Info;
use App\Api\Controllers\Controller;
use App\Http\Requests\Votes\VoteStoreRequest;
use App\Http\Requests\Votes\VoteUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;


class VoteInfoController extends Controller
{
    public function index()
    {
        $data = Info::withCount('fans')->with('applicants')->with('options')
            ->orderBy('vote_state', 'desc')->orderBy('created_at', 'desc')->paginate(config('common.pagesize'));

        // 总投票数
        foreach ($data as $item) {
            $fansCount = $item->fans_count;
            $applicants = $item->applicants;
            $options = $item->options;
            $countApplicant = count($applicants);
            $countOption = count($options);
            $item['applicants_counts'] = $countApplicant;
            $item['options_counts'] = $countOption;
            //总投票数：对比投票人数和选手（选项）总票数，取最大值
            if ($countApplicant > 0) {
                $total = 0;
                foreach ($applicants as $applicant) {
                    $total = $applicant->total + $total;
                }
                if ($fansCount < $total) {
                    $item->fans_count = $total;
                }
            } else if ($countOption > 0) {
                $total = 0;
                foreach ($options as $option) {
                    $total = $option->total + $total;
                }
                if ($fansCount < $total) {
                    $item->fans_count = $total;
                }
            }
            unset($item->applicants, $item->options);
        }

        return response()->json(['status' => 'success', 'data' => $data]);

    }

    public function store(VoteStoreRequest $request)
    {
        $list = request([
            'title', 'description', 'vote_start_date', 'vote_due_date', 'type', 'cycle', 'num', 'limit',
            'is_apply', 'apply_start_date', 'apply_due_date', 'is_check'
        ]);

        //状态判定
        $now = new Carbon( Carbon::now()->format('Y-m-d H:i')); //当前时间去除秒
        $voteStartDate = new Carbon($list['vote_start_date']);//投票开始时间
        if($now->gte($voteStartDate)) {
            $list['vote_state'] = 1; //投票开始
        }else{
            $list['vote_state'] = 0; //投票开始
        }
        $type = $list['type'];
        if ($type == 0) {//投票类型为活动
            if($list['is_apply'] == 1){
                $applyStartDate = new Carbon($list['apply_start_date']); //报名开始时间
                if($now->gte($applyStartDate)) {
                    $list['apply_state'] = 1; //报名开始
                }else{
                    $list['apply_state'] = 0; //报名开始
                }
            }
        }
        \DB::beginTransaction();
        try{
            $info = Info::create($list);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '新增失败！','Exception'=>$e]);
        }
        if ($info) {
            if($list['vote_state'] == 0){
                 Redis::hset('vote_start',$info->id,$list['vote_start_date']);
                 Redis::hset('vote_due',$info->id,$list['vote_due_date']);

            }else if($list['vote_state'] == 1){
                Redis::hset('vote_due',$info->id,$list['vote_due_date']);
            }
            if ($type == 0) {//投票类型为活动
                if($list['is_apply'] == 1){
                    if($list['apply_state'] == 0) {
                        Redis::hset('vote_apply_start',$info->id,$list['apply_start_date']);
                        Redis::hset('vote_apply_due',$info->id,$list['apply_due_date']);
                    }else if($list['apply_state'] == 1){
                        Redis::hset('vote_apply_due',$info->id,$list['apply_due_date']);
                    }
                }
            }
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);
        }
    }

    public function voteAndOptStore(VoteAndOptStoreRequest $request)
    {
        $list = request([
            'title', 'description', 'vote_start_date', 'vote_due_date', 'type',  'cycle', 'num', 'limit',
        ]);
        //状态判定
        $now = new Carbon( Carbon::now()->format('Y-m-d H:i')); //当前时间去除秒
        $voteStartDate = new Carbon($list['vote_start_date']);//投票开始时间
        if($now->gte($voteStartDate)) {
            $list['vote_state'] = 1; //投票开始
        }

        DB::beginTransaction();
        try{
            $info = Info::create($list);
            $voteID = $info->id;
            $options = request('options');
            $now2 = Carbon::now();
            $data = [];
            foreach ($options as $option){
                array_push($data,['vote_id'=>$voteID,'content'=>$option[0],'total'=>$option[1],'created_at'=>$now2,'updated_at'=>$now2]);
            }
            DB::table('votes_options')->insert($data);
            DB::commit();
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '新增失败！']);
        }
    }

    public function show()
    {
        /*
        $data = Info::with(['options' => function ($query) {
                                $query->withCount('fans');
                         }])->find(request()->info);
        $options = $data->options;
        //粉丝投票和自定义票数
        foreach ($options as $option) {
            $fansCount = $option->fans_count;
            if ($fansCount > $option->total){
                $option->total = $fansCount;
            }
            unset($option->fans_coun);
        }
        $status = $data ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $data]);
        */
        $data = Info::find(request()->info);
        $status = $data ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $data]);
    }

    public function update(VoteUpdateRequest $request)
    {
        $list = request([
            'title', 'description', 'vote_start_date', 'vote_due_date', 'type', 'cycle', 'num', 'limit',
            'is_apply', 'apply_start_date', 'apply_due_date', 'is_check'
        ]);

        $now = new Carbon( Carbon::now()->format('Y-m-d H:i')); //当前时间去除秒
        $voteStartDate = new Carbon($list['vote_start_date']);//投票开始时间
        if($now->gte($voteStartDate)) {
            $list['vote_state'] = 1; //投票开始
        }else{
            $list['vote_state'] = 0;
        }
        $type = $list['type'];
        if ($type == 0) {//投票类型为活动
            $applyStartDate = new Carbon($list['apply_start_date']); //报名开始时间
            if($now->gte($applyStartDate)) {
                $list['apply_state'] = 1; //报名开始
            }else{
                $list['apply_state'] = 0;
            }
        }
        if (Info::where('id', request()->info)->update($list)) {
            return response()->json(['status' => 'success', 'msg' => '更新成功！']);
        }
        return response()->json(['status' => 'error', 'msg' => '更新失败！']);
    }

    public function destroy()
    {
        $id = request()->info;
        $data = Info::find($id);
        $type = $data->type;
        DB::beginTransaction();
        try{
            Info::where('id', $id)->delete();
            if($type == 0){
                Applicant::where('vote_id',$id)->delete();
            }else{
                Option::where('vote_id',$id)->delete();
            }
            DB::commit();
            return response()->json(['status' => 'success', 'msg' => '删除成功！']);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '删除失败！']);
        }
    }

}