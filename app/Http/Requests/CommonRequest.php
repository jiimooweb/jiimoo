<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

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
    }

    public function failedValidation( \Illuminate\Contracts\Validation\Validator $validator ) {
        exit(json_encode(array(
            'status' => 'error',
            'data' => $validator->getMessageBag()->toArray()
        ))); 
        
    }
}
