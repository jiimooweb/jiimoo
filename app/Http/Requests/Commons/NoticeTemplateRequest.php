<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class NoticeTemplateRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'template_id' => 'required',  //微信模板ID 如：AT0009
            'title' => 'required|string',  //标题 如：订单支付成功通知
            'keywords' => 'required|string', //关键词的展示 如：订单编号、商品名称、金额、订单状态、支付金额、支付时间
            'keyword_id_list' => 'required|string', //对应的关键词的ID（字符串，用英文的逗号隔开） 9,10,2,8,11,6
            'mark' => 'required|string' //模板标识 如 PAY_SUCCESS
        ];
    }
}
