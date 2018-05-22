<?php

namespace App\Http\Requests\Displays;

use App\Http\Requests\CommonRequest;

class ApplicantRequest extends CommonRequest
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
            'salary' => 'required',
            'experience' => 'required',
            'duty' => 'required',
            'photo' => 'required',
        ];
    }
}
