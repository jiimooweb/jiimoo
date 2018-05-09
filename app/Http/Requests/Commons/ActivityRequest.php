<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class ActivityRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'places' => 'required|integer',
            'sign_time' => 'required',
            'activity_time' => 'required',
            'content' => 'required',
        ];
    }
}
