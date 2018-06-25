<?php

namespace App\Http\Requests\Votes;

use App\Http\Requests\CommonRequest;


class ApplicantStoreRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vote_id' => 'required',
            'name' => 'bail|required|max:20',
            'phone' => "bail|required|digits:11",
            'address' => "bail|required|max:100",
            'image' => 'bail|required|',
            'description' => "bail|required|max:800",
            'total'=>'integer|min:0',
        ];
    }
    //占位符
    public function attributes(){
        $attributes =[
            'vote_id'     => '投票ID',
            'description' => '描述',
            'name'        => '姓名',
            'phone'       => '手机号码',
            'address'     => '地址',
            'image'       => '图片',
            'is_pass'     => '是否通过审核',
            'total'       =>'票数',
        ];
        return $attributes;
    }
    //错误信息
    public function messages(){
        $messages = [
            'integer'         => 'attribute需为整数',
            'required'        => ':attribute必填',
            'description.max' => ':attribute不能超过800个字符',
            'name.max'        => ':attribute不能超过20个字符',
            'phone.digits'    => ':attribute需为11位数字',
            'address.max'     => ':attribute不能超过100个字符',
            'total.min'       => ':attribute不能小于0',
        ];
        return $messages;
    }

}