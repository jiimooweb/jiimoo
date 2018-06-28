<?php

namespace App\Http\Requests\Foods;

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
            'name' => 'required|min:2',
            'cate_id' => 'required|integer',
            'thumb' => 'required',
            'c_price' => 'required',
        ];
    }
}
