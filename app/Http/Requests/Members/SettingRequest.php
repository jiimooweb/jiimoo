<?php

namespace App\Http\Requests\Members;

use \App\Http\Requests\CommonRequest;

class SettingRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|integer',
            'scale' => 'required_if:status,1',
            'offer_status' => 'required_if:status,1',
            'auto_status' => 'required_if:status,1',
            'offer' => 'bail|required_if:status,1|required_unless:offer_status,0'
        ];
    }
}
