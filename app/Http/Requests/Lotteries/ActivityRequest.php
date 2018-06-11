<?php

namespace App\Http\Requests\Lotteries;

use App\Http\Requests\CommonRequest;

class ActivityRequest extends CommonRequest
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
        ];
    }
}
