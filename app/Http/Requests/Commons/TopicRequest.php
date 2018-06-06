<?php

namespace App\Http\Requests\Commons;

use App\Http\Requests\CommonRequest;

class TopicRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'thumb' => 'required|string',
            'content' => 'required|string'
        ];
    }
}
