<?php

namespace App\Api\Controllers\Votes;

use App\Models\Votes\Applicant;
use App\Models\Votes\Option;
use App\Models\Votes\Info;
use App\Api\Controllers\Controller;
use App\Http\Requests\Votes\VoteStoreRequest;
use App\Http\Requests\Votes\VoteUpdateRequest;
use Carbon\Carbon;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Monolog\Logger;


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
        $log = new Logger('vote');
        $log->pushHandler(new StreamHandler(storage_path('logs/vote.log'), Logger::INFO));
        $log->addInfo('投票执行保存');

        $list = request([
            'title', 'description', 'vote_start_date', 'vote_due_date', 'type', 'cycle', 'num', 'limit',
            'is_apply', 'apply_start_date', 'apply_due_date', 'is_check'
        ]);
        $string = implode('!', $list);
        $log->addInfo('投票资料1：'.$string);

        //状态判定
        $now = new Carbon(Carbon::now()->format('Y-m-d H:i')); //当前时间去除秒
        $voteStartDate = new Carbon($list['vote_start_date']);//投票开始时间
        if ($now->gte($voteStartDate)) {
            $list['vote_state'] = 1; //投票开始
        } else {
            $list['vote_state'] = 0; //投票开始
        }
        $type = $list['type'];
        if ($type == 0) {//投票类型为活动
            if ($list['is_apply'] == 1) {
                $applyStartDate = new Carbon($list['apply_start_date']); //报名开始时间
                if ($now->gte($applyStartDate)) {
                    $list['apply_state'] = 1; //报名开始
                } else {
                    $list['apply_state'] = 0; //报名开始
                }
            }
        }
        $string = implode('!', $list);
        $log->addInfo('投票资料2：'.$string);

        DB::beginTransaction();
        try {
            $info = Info::create($list);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $log->addInfo('投票新增失败：'.$e);
            return response()->json(['status' => 'error', 'msg' => '新增失败']);
        }
        if ($info) {
            if ($list['vote_state'] == 0) {
                Redis::hset('vote_start', $info->id, $list['vote_start_date']);
                Redis::hset('vote_due', $info->id, $list['vote_due_date']);

            } else if ($list['vote_state'] == 1) {
                Redis::hset('vote_due', $info->id, $list['vote_due_date']);
            }
            if ($type == 0) {//投票类型为活动
                if ($list['is_apply'] == 1) {
                    if ($list['apply_state'] == 0) {
                        Redis::hset('vote_apply_start', $info->id, $list['apply_start_date']);
                        Redis::hset('vote_apply_due', $info->id, $list['apply_due_date']);
                    } else if ($list['apply_state'] == 1) {
                        Redis::hset('vote_apply_due', $info->id, $list['apply_due_date']);
                    }
                }
            }
            $log->addInfo('投票新增成功');
            return response()->json(['status' => 'success', 'msg' => '新增成功！']);
        }

    }

    public function show()
    {
        $data = Info::find(request()->info);
        $status = $data ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $data]);
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
    }

    public function update(VoteUpdateRequest $request)
    {
        $list = request([
            'title', 'description', 'vote_start_date', 'vote_due_date', 'type', 'cycle', 'num', 'limit',
            'is_apply', 'apply_start_date', 'apply_due_date', 'is_check'
        ]);

        $now = new Carbon(Carbon::now()->format('Y-m-d H:i')); //当前时间去除秒
        $voteStartDate = new Carbon($list['vote_start_date']);//投票开始时间
        if ($now->gte($voteStartDate)) {
            $list['vote_state'] = 1; //投票开始
        } else {
            $list['vote_state'] = 0;
        }
        $type = $list['type'];
        if ($type == 0) {//投票类型为活动
            $applyStartDate = new Carbon($list['apply_start_date']); //报名开始时间
            if ($now->gte($applyStartDate)) {
                $list['apply_state'] = 1; //报名开始
            } else {
                $list['apply_state'] = 0;
            }
        }

        $id = request()->info;
        DB::beginTransaction();
        try {
            $Info = Info::where('id', $id)->update($list);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '修改失败！']);
        }

        if ($Info) {
            if ($list['vote_state'] == 1) {
                Redis::hdel('vote_start', $id);
            } else {
                Redis::hset('vote_start', $id, $list['vote_start_date']);
            }
            Redis::hset('vote_due', $id, $list['vote_due_date']);
            if ($type == 0) {
                if ($list['apply_state'] == 1) {
                    Redis::hdel('vote_apply_start', $id);
                } else {
                    Redis::hset('vote_apply_start', $id, $list['apply_start_date']);
                }
                Redis::hset('vote_apply_due', $id, $list['apply_due_date']);
            }
        }
        return response()->json(['status' => 'success', 'msg' => '更新成功！']);
    }

    public function destroy()
    {
        $id = request()->info;
        $data = Info::find($id);
        $type = $data->type;
        DB::beginTransaction();
        try {
            Info::where('id', $id)->delete();
            if ($type == 0) {
                Applicant::where('vote_id', $id)->delete();
            } else {
                Option::where('vote_id', $id)->delete();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '删除失败！']);
        }
        Redis::hdel('vote_start', $id);
        Redis::hdel('vote_due', $id);
        Redis::hdel('vote_apply_start', $id);
        Redis::hdel('vote_apply_due', $id);
        return response()->json(['status' => 'success', 'msg' => '删除成功！']);
    }

}