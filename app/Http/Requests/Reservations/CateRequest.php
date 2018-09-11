<?php

namespace App\Http\Requests\Reservations;

use App\Http\Requests\CommonRequest;

class CateRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2'
        ];
    }
}
