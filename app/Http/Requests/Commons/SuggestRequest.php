<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class SuggestRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required',
        ];
    }
}
