<?php

namespace App\Http\Requests\Displays;

use App\Http\Requests\CommonRequest;

class CommentRequest extends CommonRequest
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
            'article_id' => 'required|integer',
            'content' => 'required'
        ];
    }
}
