<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class BasicInfoRequest extends CommonRequest
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
