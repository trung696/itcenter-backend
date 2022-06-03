<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class DonViRequest extends FormRequest
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
                    case 'themDonVi':
                        $rules = [
                            "ten_don_vi" => "required",
//                            "trang_thai" => "required",
                        ];
                        break;
                    case 'updateChiTietDonVi':
                        $rules = [
                            "ten_don_vi" => "required",
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
            'ten_don_vi.required' => 'Bắt buộc phải nhập tên đơn vị',
            'trang_thai.required' => 'Bắt buộc phải chọn trạng thái',
        ];
    }
}
