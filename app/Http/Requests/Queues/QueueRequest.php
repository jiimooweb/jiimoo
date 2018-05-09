<?php

namespace App\Http\Requests\Queues;

use \App\Http\Requests\CommonRequest;

class QueueRequest extends CommonRequest
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
            'flag' => 'required',
            'template_id' => 'required'
        ];
    }
}
