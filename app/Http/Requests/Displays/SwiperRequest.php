<?php

namespace App\Http\Requests\Displays;

use Illuminate\Foundation\Http\FormRequest;

class SwiperRequest extends FormRequest
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
