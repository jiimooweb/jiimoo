<?php

namespace App\Http\Requests\Votes;

use App\Http\Requests\CommonRequest;


class OptionUpdateRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'options'      =>'bail|required|array',
            'options.*.2'      =>'required',
            'options.*.0'      => 'required|max:50',
            'options.*.1'      => 'bail|required|integer|min:0',
        ];
    }
    //占位符
    public function attributes(){
        $attributes =[
            'options.*.2'      => 'opt_id',
            'options.*.0'      => '选项内容',
            'options.*.1'      => '票数',
        ];
        return $attributes;
    }
    //错误信息
    public function messages(){
        $messages = [
            'integer'         => 'attribute需为整数',
            'required'        => ':attribute必填',
            'options.*.0.max' => ':attribute不能超过50个字符',
            'options.*.1.min' => ':attribute不能小于0',
        ];
        return $messages;
    }
}