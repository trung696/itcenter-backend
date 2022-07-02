<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class TeacherRequest extends FormRequest
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
                    case 'add':
                        $rules = [
                            "email" => "required|unique:users",
                            "name" => "required",
                            'password' => 'required',
                            'level' => "required|integer"

                        ];
                        break;
                    case 'update':
                        $rules = [
                            "email" => "required",
                            "name" => "required",
                            'level' => "required|integer",
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
            'email.required' => 'Bắt buộc phải nhập email',
            'name.required' => 'Bắt buộc phải nhập tên người dùng',
            'password.required' => 'Bắt buộc phải nhập password',
            'level.required' => 'Bắt buộc phải chọn quyền',
            'email.unique' => 'Email đã tồn tại',
        ];
    }
}
