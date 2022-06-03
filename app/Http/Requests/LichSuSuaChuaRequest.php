<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class LichSuSuaChuaRequest extends FormRequest
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
                    case 'themLichSuSuaChua':
                        $rules = [
                            'ngay_sua_chua' => "required",
                            'noi_dung' => "required",
                            'nguyen_nhan' => "required",
                            'chi_phi' => "required",
                            'id_tai_san_con' => "required",
                        ];
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
            "ngay_sua_chua.regex" =>  "Không được để trống ngày sửa chữa",
            "noi_dung.required" =>  "Không được để trống nội dung sửa chữa",
            "nguyen_nhan.required" =>  "Không được để trống nguyên nhân sửa chữa",
            "chi_phi.required" =>  "Không được để trống chi phí sửa chữa",
            "id_tai_san_con.required" =>  "Không được để trống tài sản con",
        ];
    }
}
