<?php

namespace App\Http\Requests\Votes;

use App\Models\Votes\Info;
use App\Http\Requests\CommonRequest;
use Carbon\Carbon;
class VoteUpdateRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $now = new Carbon( Carbon::now()->format('Y-m-d H:i'));//当前时间 去秒
        $info = Info::find(request()->info);

        $arr = request(['vote_start_date', 'vote_due_date', 'apply_start_date', 'num', 'type']);
        $voteStartDate = $arr['vote_start_date'];
        $voteDueDate = $arr['vote_due_date'];
        $num = $arr['num'];
        $type = $info->type;
        $rules = [
            'title' => 'bail|required|max:3',
            'description' => 'bail|required|max:5000',
            'vote_start_date' => "bail|required",
            'vote_due_date' => "bail|required|after_or_equal:{$now}|after:{$voteStartDate}",
            'num' => 'bail|required|integer|min:1',
            'limit' => "bail|required|integer|max:{$num}|min:1",
        ];
        if ($type == 0) {
            $applyStartDate = $arr['apply_start_date'];
            $rules['apply_start_date'] = "bail|required|before:{$voteDueDate}";
            $rules['apply_due_date'] = "bail|required|after:{$applyStartDate}|before_or_equal:{$voteDueDate}";
            $rules['is_check'] = 'required';
            $rules['is_apply'] = 'required';
        }
        return $rules;
    }
    //占位符
    public function attributes(){
        $attributes =[
            'title'           => '标题',
            'description'     => '描述',
            'vote_start_date' => '投票开始时间',
            'vote_due_date'   => '投票截止时间',
            'num'             => '可投票数',
            'limit'           => '可投上限',
            'is_check'        => '是否需要审核',
            'is_apply'        => '是否允许报名',
            'apply_start_date'=> '报名开始时间',
            'apply_due_date'  => '报名截止时间',
        ];
        return $attributes;
    }
    //错误信息
    public function messages(){
        $messages = [
            'required'                       => ':attribute必填',
            'integer'                        => ':attribute仅能输入数字',
            'title.max'                      => ':attribute不能超过50个字符',
            'vote_due_date.after'            => ':attribute不能小于投票开始时间',
            'num.min'                        => ':attribute不能小于1',
            'limit.max'                      => ':attribute不能超过可投票数',
            'limit.min'                      => ':attribute不能小于1',
            'apply_start_date.before'        => ':attribute不能超过投票截止时间',
            'apply_due_date.after'           => ':attribute不能小于报名开始时间',
            'apply_due_date.before_or_equal' => ':attribute不能超过投票截止时间',
        ];
        return $messages;
    }
}
