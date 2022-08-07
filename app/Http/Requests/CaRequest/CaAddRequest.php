<?php

namespace App\Http\Requests\CaRequest;

use Illuminate\Foundation\Http\FormRequest;

class CaAddRequest extends FormRequest
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
        return [
            //'tên thuộc tính' => 'quy định điều kiện'
            'ca_hoc' => 'bail|required|max:200|unique:cas',
            'trang_thai' => 'required'
        ];
    }
    //câu thông báo lỗi
    public function messages()
    {
        return [
            'ca_hoc.required' => 'Tên ca học không được để trống',
            'ca_hoc.max' => 'Tên ca học không vượt quá 200 kí tự',
            'ca_hoc.unique' => 'Tên ca học đã tồn tại',
            //trang_thai
            'trang_thai.required' => 'Trạng thái không được để trống',
            
        ];
    }
}
