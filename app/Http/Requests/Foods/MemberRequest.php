<?php

namespace App\Http\Requests\Foods;

use App\Http\Requests\CommonRequest;

class MemberRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'mobile' => 'required|string'
        ];
    }
}
