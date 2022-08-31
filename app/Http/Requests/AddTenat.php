<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AddTenat extends FormRequest
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
        return[
            'company_email_address' => ['required','regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'],
            'company_full_name' => ['required','string'],
            'office_phone_number' => ['required','string'],
            'reception_unit_number' => ['required','integer'],
            'levels' => ['required','string'],
            'unit' => ['required','string'],   
            'tenat_id' => ['integer'],
            'first_name' => ['required','string'],
            'last_name' => ['required','string'],   
            'work_email_address' => ['required','regex:/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/'],
            'job_postion' => ['required','string'],
            'mobile_number' => ['required','string'],
            'office_phone_number1' => ['string'],
            'assess_start_date' => ['string'],
            'office_unit_number' => ['required','integer'],

        ];
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
