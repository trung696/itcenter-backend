<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class CourseCategoryRequest extends FormRequest
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
    //    dd($currentAction);
        switch ($this->method()):
            case 'POST':
                switch ($currentAction) {
                    case 'AddCourseCategory':
                        $rules = [
                            "name" => "required|min:10|max:255|unique:course_categories",
                        ];
                        break;
                    case 'updateCourseCategory':
                        $rules = [
                            "name" => "required|min:10|max:255|unique:course_categories",
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
            'name.required' => 'Bắt buộc phải nhập tên danh mục khóa học',
            'name.min' => 'Tên danh mục khóa học phải nhập 10 ký tự trở lên',
            'name.unique' => 'Danh mục khóa này đã tồn tại trên hệ thống'
        ];
    }
}
