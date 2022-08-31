<?php

namespace Modules\Tenat\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TenatNewLoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['required','regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'],
            'password' => ['required','string'],
            'phone_number' => ['optional','string'],
            'save_account' => ['optional','integer']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
         'success'   => false,
         'message'   => 'Validation errors',
         'data'      => $validator->errors()
       ]));
    }
}
