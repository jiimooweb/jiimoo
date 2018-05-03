<?php

namespace App\Http\Requests\Displays;

use App\Http\Requests\CommonRequest;

class ProductRequest extends CommonRequest
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
            'cate_id' => 'required',
            'display' => 'integer',
            'desc' => 'required'
        ];
    }
}
