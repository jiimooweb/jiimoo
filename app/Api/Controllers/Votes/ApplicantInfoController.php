<?php

namespace App\Api\Controllers\Votes;

use App\Http\Requests\Votes\ApplicantStoreRequest;
use App\Models\Votes\Applicant;
use App\Models\Votes\Info;
use App\Api\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ApplicantInfoController extends Controller
{
    public function index()
    {
        $voteID = request()->voteID;
        $voteInfo = Info::find($voteID);
        $all = Applicant::where('vote_id', $voteID)->withCount('fans')->get();
        $isCheck = $voteInfo['is_check'];
        $unaudited = []; //未审核
        $audited = [];   //审核通过
        foreach ($all as $item) {
            $fansCount = $item->fans_count;
            if ($item->total < $fansCount) {
                $item->total = $fansCount;
            }
            unset($item->fans_count);
            if ($isCheck == 1) {
                if ($item->is_pass == 0) {
                    array_push($unaudited, $item);
                } else {
                    array_push($audited, $item);
                }
            }
        }
        $data['all'] = $all;
        if ($isCheck == 1) {
            $data['unaudited'] = $unaudited;
            $data['audited'] = $audited;
            return response()->json(['status' => 'success', 'isCheck' => 1, 'data' => $data]);
        } else {
            return response()->json(['status' => 'success', 'isCheck' => 0, 'data' => $data]);
        }
    }

    public function store(ApplicantStoreRequest $request)
    {
        $list = request(['vote_id', 'fan_id', 'name', 'phone', 'address', 'image', 'description', 'total', 'is_pass']);
        $voteID = $list['vote_id'];
        $voteInfo = Info::find($voteID);
        $isCheck = $voteInfo['is_check'];
        if ($isCheck == 1) {
            $isPass = $list['is_pass'];
            if ($isPass == 1) {
                $list['num'] = Applicant::where('vote_id', $list['vote_id'])->max('num') + 1;
            }
        } else {
            $list['num'] = Applicant::where('vote_id', $list['vote_id'])->max('num') + 1;
        }
        DB::beginTransaction();
        try {
            Applicant::create($list);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '新增失败！']);
        }
        return response()->json(['status' => 'success', 'msg' => '新增成功！']);
    }

    public function show()
    {
        $data = Applicant::find(request()->applicant);
        $status = $data ? 'success' : 'error';
        return response()->json(['status' => $status, 'data' => $data]);
    }

    public function update(ApplicantStoreRequest $request)
    {
        $list = request(['vote_id', 'fan_id', 'name', 'phone', 'address', 'image', 'description', 'total']);

        DB::beginTransaction();
        try {
            Applicant::where('id', request()->applicant)->update($list);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => "修改:".$e]);
        }
        return response()->json(['status' => 'success', 'msg' => '更新成功！']);
    }

    public function destroy()
    {
        DB::beginTransaction();
        try {
            Applicant::where('id', request()->applicant)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' => '删除失败！']);
        }
        return response()->json(['status' => 'success', 'msg' => '删除成功！']);
    }

    public function doAudited()
    {
        $list = request(['vote_id', 'applicant_id', 'is_pass']);
        $isPass = $list['is_pass'];
        if ($isPass != 0) {
            $applicant = Applicant::where('id', $list['applicant_id'])->get();
            if($applicant->num == null){
                $list['num'] = Applicant::where('vote_id', $list['vote_id'])->max('num') + 1;
            }
        }
        DB::beginTransaction();
        try {
            Applicant::where('id', $list['applicant_id'])->update(['is_pass'=>$list['is_pass']]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'msg' =>"审核：".$e]);
        }
        return response()->json(['status' => 'success', 'msg' => '审核成功！']);
    }

}