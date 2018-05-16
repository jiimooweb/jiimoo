<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;


class ProductCateRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:2',
            'pid' => 'integer',
        ];
    }
}
