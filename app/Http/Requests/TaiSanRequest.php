<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class TaiSanRequest extends FormRequest
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
                    case 'themDanhMucTaiSan':
                        $rules = [
                            "ten_danh_muc" => "required",
//                            "trang_thai" => "required",
                        ];
                        break;
                    case 'updateChiTietDanhMucTaiSan':
                        $rules = [
                            "ten_danh_muc" => "required",
                            "trang_thai" => "required",
                        ];
                        break;
                    case 'themTaiSan':
                        $rules = [
                            "ten_tai_san" => "required",
                            "danh_muc_tai_san_id" => "required",
//                            "trang_thai" => "required",
                        ];
                        break;
                    case 'updateChiTietTaiSan':
                        $rules = [
                            "ten_tai_san" => "required",
                            "danh_muc_tai_san_id" => "required",
                            "trang_thai" => "required",
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
            'ten_danh_muc.required' => 'Bắt buộc phải nhập tên danh mục',
            'ma_tai_san.required' => 'Bắt buộc phải nhập mã tài sản',
            'ten_tai_san.required' => 'Bắt buộc phải nhập tên tài sản',
            'danh_muc_tai_san_id.required' => 'Bắt buộc phải nhập danh mục tài sản',
            'trang_thai.required' => 'Bắt buộc phải chọn trạng thái',
        ];
    }

}
