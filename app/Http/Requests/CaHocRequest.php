<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class CaHocRequest extends FormRequest
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
        $rules = [];

        $dataRequest = $this->request->all();

        Session::push('post_form_data', $dataRequest);

        $currentAction = $this->route()->getActionMethod();
        switch ($this->method()):
            case 'POST':
                switch ($currentAction) {
                    case 'addCa':
                        $rules = [
                            "ca_hoc" => "required",
                        ];
                        break;
                    case 'updateCa':
                        $rules = [
                            "ca_hoc" => "required",
                        ];
                        break;

                    default:
                        break;
                }
                break;
            default:
                break;
        endswitch;

        return $rules;
    }

    public function messages()
    {
        return [
            'ca_hoc.required' => 'Bắt buộc phải nhập ca học',
        ];
    }
}
