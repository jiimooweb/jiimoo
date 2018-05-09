<?php

namespace App\Http\Requests\Queues;

use \App\Http\Requests\CommonRequest;

class QueueUserRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
        ];
    }
}
