<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\CommonRequest;

class UserRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'=>'required'
        ];
    }
}
