<?php

namespace App\Http\Requests\Lotteries;

use App\Http\Requests\CommonRequest;

class PrizeRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'coupo_id'=>'required',
            'probably'=>'required'
        ];
    }
}
