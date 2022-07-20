<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class LopHocRequest extends FormRequest
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
                    case 'updateLopHoc':
                        $rules = [
                            "ten_lop_hoc" => "required",
                            "thoi_gian_bat_dau" => "required",
                            "thoi_gian_ket_thuc" => "required|greater_than:thoi_gian_bat_dau",
                            "lich_hoc" => "required",
                            "so_cho" => "required",
                            "id_khoa_hoc" => "required",
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
            "ten_lop_hoc.required" =>  "Không được để trống tên lớp học",
            "lich_hoc.required" =>  "Không được để trống ca học",
            "thoi_gian_bat_dau.required" =>  "Không được để trống thời gian khai giảng",
            "thoi_gian_ket_thuc.required" =>  "Không được để trống thời gian kết thúc",
            "id_dia_diem.required" =>  "Không được để trống địa điểm",
            "so_cho.required" =>  "Không được để trống số chỗ",

        ];
    }
}
