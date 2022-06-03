<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class TaiSanConRequest extends FormRequest
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
                    case 'updateChiTietTaiSanCon':
                        $rules = [
                            "nam_su_dung" => "required",
                            "thong_so_ky_thuat" => "required",
                            "xuat_xu" => "required",
                            "id_tai_san" => "required",
                            "nguon_kinh_phi" => "required",
                            "nguyen_gia" => "required",
                            "thoi_gian_khau_hao" => "required",
                            "gia_tri_con_lai" => "required",
                            "thoi_han_bao_hanh" => "required",
                            "trang_thai" => "required",
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
            'nam_su_dung.required' => 'Bắt buộc phải nhập năm sử dụng',
            'thong_so_ky_thuat.required' => 'Bắt buộc phải nhập thông số kĩ thuật',
            'xuat_xu.required' => 'Bắt buộc phải nhập nguồn gốc xuất sứ',
            'id_tai_san.required' => 'Bắt buộc phải chọn tài sản',
            'nguon_kinh_phi.required' => 'Bắt buộc phải nhập nguồn kinh phí',
            'nguyen_gia.required' => 'Bắt buộc phải nhập nguyên giá',
            'thoi_gian_khau_hao.required' => 'Bắt buộc phải nhập thời gian khấu hao',
            'gia_tri_con_lai.required' => 'Bắt buộc phải nhập giá trị còn lại',
            'thoi_han_bao_hanh.required' => 'Bắt buộc phải nhập thời hạn bảo hành',
            'trang_thai.required' => 'Bắt buộc phải chọn trạng thái',
        ];
    }

}
