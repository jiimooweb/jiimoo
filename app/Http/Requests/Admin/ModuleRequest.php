<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\CommonRequest;

class ModuleRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required',
            'desc'=>'required',
        ];
    }
}
