<?php

namespace App\Http\Requests\Answers;

use App\Http\Requests\CommonRequest;

class QusetionRequest extends CommonRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'questions' => 'required',
            'answer' => 'required|array',
            'positive' => 'required',
            'depot_id'=>'required',
        ];
    }
}
