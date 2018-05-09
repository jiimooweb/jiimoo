<?php

namespace App\Http\Requests\Commons;

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
            'o_price' => 'required',
            'c_price' => 'required',
            'stock' => 'required|integer',
            'display' => 'required|integer',
            'hot' => 'required|integer',
            'recommend' => 'required|integer',
        ];
    }
}
