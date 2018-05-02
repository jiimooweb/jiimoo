<?php

namespace App\Http\Requests\Displays;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
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
            'places' => 'required|integer',
            'sign_time' => 'required',
            'activity_time' => 'required',
            'content' => 'required',
        ];
    }
}
