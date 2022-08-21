<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class DocumentRequest extends FormRequest
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
                    case 'AddDocument':
                        $rules = [
                            "name" => "required",
                            "file" => "required",
                        ];
                        break;
                    case 'updateKhoaHoc':
                        $rules = [
                            "name" => "required",
                            "file" => "required",
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
            'name.required' => 'Bắt buộc phải nhập tên tài liệu',
            'file.required' => 'Bắt buộc phải nhập file tài liệu',
        ];
    }
}
