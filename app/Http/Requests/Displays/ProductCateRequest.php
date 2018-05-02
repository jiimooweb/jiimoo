<?php

namespace App\Http\Requests\Displays;

use Illuminate\Foundation\Http\FormRequest;

class ProductCateRequest extends FormRequest
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
