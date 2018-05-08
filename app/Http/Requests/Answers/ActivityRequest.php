<?php

namespace App\Http\Requests\Answers;

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
            'reward' => 'required',
            'start_time' => 'required_with:end_time',
            'end_time' => 'required_with:start_time',
            'reward_total' => 'required',
            'depots'=>'array'
        ];
    }
}
