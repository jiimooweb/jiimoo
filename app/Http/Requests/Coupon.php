<?php

namespace App\Http\Requests;

use App\Http\Requests\CommonRequest;

class Coupon extends CommonRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'module' => 'required',
            'use_price' => 'required',
            'price' => 'required',
            'thumb' => 'required',
            'desc' => 'required',
            'receive_num' => 'required',
            'display' => 'required|integer',
            'time_type' => 'required|integer',
            'limit' => 'required|integer',
            'total' => 'required|integer',
        ];
    }

    

}
