<?php

namespace App\Http\Requests\ApiRequest;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestContact extends FormRequest
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
            //'tên thuộc tính' => 'quy định điều kiện'
            'name' => 'bail|required|string',
            'email' => 'bail|required|unique:form_contacts|email:rfc,dns',
            'birthday' => 'bail|required|date',
            'phone' => 'bail|required|unique:form_contacts|digits:10'
        ];
    }
}
