<?php

namespace App\Http\Requests\Answers;

use App\Http\Requests\CommonRequest;

class DepotRequest extends CommonRequest
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
            'desc' => 'required',
        ];
    }
}
