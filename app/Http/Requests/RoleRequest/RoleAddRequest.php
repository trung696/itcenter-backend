<?php

namespace App\Http\Requests\RoleRequest;

use Illuminate\Foundation\Http\FormRequest;

class RoleAddRequest extends FormRequest
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
            'name' => 'bail|required|max:200|min:2|unique:roles',
            'description' => 'bail|required|max:200|min:2'
        ];
    }
    //câu thông báo lỗi
    public function messages()
    {
        return [
            'name.required' => 'Tên vai trò(role) không được để trống',
            'name.max' => 'Tên vai trò(role) không vượt quá 200 kí tự',
            'name.min' => 'Tên vai trò(role) không dưới 2 kí tự',
            'name.unique' => 'vai trò(role) đã tồn tại',
            //description
            'description.required' => 'Mô tả vai trò(role) không được để trống',
            'description.max' => 'Mô tả vai trò(role) không vượt quá 200 kí tự',
            'description.min' => 'Mô tả vai trò(role) không dưới 2 kí tự',
        ];
    }
}
