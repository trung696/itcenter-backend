<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class DangKyRequest extends FormRequest
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
                    case 'themDangKy':
                        $rules = [
                            "ho_ten" => "required",
                            "ngay_sinh" => "required",
                            "so_dien_thoai" => "required",
                            "email" => "required",
                            "pham_tram_giam" => "required"

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
            "ho_ten.required" =>  "Không được để trống tên học viện",
            "ngay_sinh.required" =>  "Không được để trống ngày sinh",
            "so_dien_thoai.required" =>  "Không được để trống số điện thoại",
            "email.required" =>  "Không được để trống email",
            "pham_tram_giam.required" => "Không được để trống phần trăm giảm giá"
        ];
    }
}
