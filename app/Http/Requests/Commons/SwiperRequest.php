<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class SwiperRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required',
            'url' => 'required',
            'remake' => 'required',
            'display' => 'required|integer'
        ];
    }
}
