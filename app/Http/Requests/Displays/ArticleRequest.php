<?php

namespace App\Http\Requests\Displays;

use App\Http\Requests\CommonRequest;

class ArticleRequest extends CommonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'thumb' => 'required',
            'cate_id' => 'required',
            'author' => 'required',
            'content' => 'required',
        ];
    }
}
