<?php

namespace App\Http\Requests\Displays;

use Illuminate\Foundation\Http\FormRequest;

class BasicInfoRequest extends FormRequest
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
            'tel' => 'required',
            'address' => 'required',
            'logo' => 'required|file',
            'intro' => 'required',
            'desc' => 'required',
        ];
    }
}
