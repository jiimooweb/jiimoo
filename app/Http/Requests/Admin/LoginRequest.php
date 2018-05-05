<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\CommonRequest;

class LoginRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username'=>'required',
            'password'=>'required',
        ];
    }
}
