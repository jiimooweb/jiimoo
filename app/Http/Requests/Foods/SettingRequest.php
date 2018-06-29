<?php

namespace App\Http\Requests\Foods;

use App\Http\Requests\CommonRequest;

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
            //
        ];

        return [
            'name' => 'required',
            'thumb' => 'required',
            'offer_status' => 'required|integer',
            'offer' => 'required_if:offer_status,1'
        ];
    }
}
