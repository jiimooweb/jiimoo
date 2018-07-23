<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class MiniProgramRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'thumb' => 'required',
            'price' => 'required',
            'app_id' => 'required'
        ];
    }
}
