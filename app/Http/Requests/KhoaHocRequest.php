<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class KhoaHocRequest extends FormRequest
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
                    case 'themKhoaHoc':
                        $rules = [
                            "ten_khoa_hoc" => "required",
                            "thoi_gian" => "required|integer",
                            "thong_tin_khoa_hoc" => "required",
                            "hoc_phi" => "required",
                        ];
                        break;
                    case 'updateKhoaHoc':
                        $rules = [
                            "ten_khoa_hoc" => "required",
                            "thoi_gian" => "required|integer",
                            "thong_tin_khoa_hoc" => "required",
                            "hoc_phi" => "required",
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
            'ten_khoa_hoc.required' => 'Bắt buộc phải nhập tên khoá học',
            'thoi_gian.required' => 'Bắt buộc phải nhập thời gian',
            'thong_tin_khoa_hoc.required' => 'Bắt buộc phải nhập thông tin khoá học',
            'hoc_phi.required' => 'Bắt buộc phải nhập học phí',
        ];
    }
}
