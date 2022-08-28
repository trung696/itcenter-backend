<?php

namespace App\Http\Requests\UserRequest;

use Illuminate\Foundation\Http\FormRequest;

class UserAddRequest extends FormRequest
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
            'name' => 'bail|required|max:200|min:2',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|max:50|string',
            'address' => 'bail|required|max:200|min:2',
            'phone' => 'bail|required|numeric|unique:users',
            'detail' => 'bail|required',
            
        ];
    }
    //câu thông báo lỗi
    public function messages()
    {
        return [
            //name
            'name.required' => 'Tên người dùng không được để trống',
            'name.max' => 'Tên người dùng  không vượt quá 200 kí tự',
            'name.min' => 'Tên người dùng không dưới 2 kí tự',
            //email
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email Đã tồn tại trên hệ thống',
            //password
            'password.required' => 'Mật khẩu không được để trống',
            'password.max' => 'Mật khẩu không vượt quá 200 kí tự',
            'password.string' => 'Mật khẩu không đúng định dạng',
            //address
            'address.required' => 'Địa chỉ không được để trống',
            'address.max' => 'Địa chỉ không vượt quá 200 kí tự',
            'address.min' => 'Địa chỉ không dưới 2 kí tự',
            //phone
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.numeric' => 'Số điện thoại là dạng số',
            'phone.unique' => 'Số điện thoại tồn tại',
            
             //detail
             'detail.required' => 'Mô tả chi tiết không được để trống',

          
        ];
    }
}
