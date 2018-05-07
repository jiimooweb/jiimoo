<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\CommonRequest;

class XcxRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'app_id'=>'required',
        ];
    }
}
