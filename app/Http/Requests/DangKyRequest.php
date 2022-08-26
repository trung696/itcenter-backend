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
                            "ho_ten" => "required|min:5",
                            "ngay_sinh" => "required",
                            "so_dien_thoai" => "required|max:10",
                            "email" => "required",
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
            "ho_ten.min" => "Họ tên nhập tối thiểu 5 kí tự",
            "ngay_sinh.required" =>  "Không được để trống ngày sinh",
            "so_dien_thoai.required" =>  "Không được để trống số điện thoại",
            "so_dien_thoai.max" => "Số điện thoại không được vượt quá 10 kí tự",
            "email.required" =>  "Không được để trống email",
        ];
    }
}
