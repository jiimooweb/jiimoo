<?php

namespace App\Http\Requests\Displays;

use Illuminate\Foundation\Http\FormRequest;

class SuggestRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => 'required',
        ];
    }
}
