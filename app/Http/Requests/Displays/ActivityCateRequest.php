<?php

namespace App\Http\Requests\Displays;

use App\Http\Requests\CommonRequest;

class ActivityCateRequest extends CommonRequest
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
