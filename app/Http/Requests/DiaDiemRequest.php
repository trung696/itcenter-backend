<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class DiaDiemRequest extends FormRequest
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
                    case 'themDiaDiem':
                        $rules = [
                            "ten_dia_diem" => "required|min:5",
                            "trang_thai" => "required|integer",
                        ];
                        break;
                    case 'update':
                        $rules = [
                            "ten_dia_diem" => "required",
                            "trang_thai" => "required|integer"
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
            'ten_dia_diem.required' => 'Bắt buộc phải nhập tên địa điểm',
            'ten_dia_diem.min' => 'Tên địa điểm phải nhập tối thiểu 5 kí tự trở lên',
            'trang_thai.required' => 'Bắt buộc phải nhập trạng thái địa điểm',

        ];
    }
}
