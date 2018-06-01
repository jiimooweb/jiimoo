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
            'template_id' => 'required',
            'title' => 'required|string',
            'keywords' => 'required|string',
            'keyword_id_list' => 'required|string'
        ];
    }
}
