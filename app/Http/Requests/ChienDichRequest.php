<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class ChienDichRequest extends FormRequest
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
        //        dd($currentAction);
        switch ($this->method()):
            case 'POST':
                switch ($currentAction) {
                    case 'themChienDich':
                        $rules = [
                            "ten_chien_dich" => "required",
                            "ngay_bat_dau" => "required",
                            "ngay_ket_thuc" => "required",
                            // "email" => "required",


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
            "ten_chien_dich.required" =>  "Không được để trống tên chiến dịch",
            "ngay_bat_dau.required" =>  "Không được để trống ngày bắt đầu chiến dịch",
            "ngay_ket_thuc.required" =>  "Không được để trống ngày kết thúc chiến dịch",

        ];
    }
}
