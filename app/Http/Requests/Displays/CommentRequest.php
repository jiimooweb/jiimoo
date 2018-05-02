<?php

namespace App\Http\Requests\Displays;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
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
