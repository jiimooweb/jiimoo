<?php

namespace App\Http\Requests\Answers;

use App\Http\Requests\CommonRequest;

class CashRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ratio' => 'required',
        ];
    }
}
