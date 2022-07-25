<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class PaymentMethodRequest extends FormRequest
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
    //    dd($currentAction);
        switch ($this->method()):
            case 'POST':
                switch ($currentAction) {
                    case 'AddPaymentMethod':
                        $rules = [
                            "name" => "required",
                        ];
                        break;
                    case 'updatePaymentMethod':
                        $rules = [
                            "name" => "required",
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
            'name.required' => 'Bắt buộc phải nhập tên Phương thức thanh toán',
        ];
    }
}
