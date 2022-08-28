<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class CourseRequest extends FormRequest
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
                    case 'AddCourse':
                        $rules = [
                            "name" => "required|min:4|max:255|unique:course",
                            "price" => "required|numeric",
                            "image" => "required",
                            "description" => "required|max:255",
                            'category_id' => 'required',
                            'content' => "required",
                            'result' => "required"
                        ];
                        break;
                    case 'updateCourse':
                        $rules = [
                            "name" => "required|min:10|max:255|unique:course",
                            "price" => "required|numeric",
                            "image" => "required",
                            "description" => "required|max:255",
                            'content' => "required",
                            'result' => "required"
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
            'name.required' => 'Bắt buộc phải nhập tên khoá học',
            'name.min' => 'Tên khóa học bắt buộc phải nhập 4 kí tự trở lên',
            'name.max' => 'Tên khóa học không được nhập vượt quá 255 kí tự',
            'name.unique' => 'Tên khóa học đã tồn tại trên hệ thống',
            "price.required" => "Bắt buộc phải nhập giá khóa học",
            'price.numeric' => 'Giá nhập vào phải là số',
            'description.required' => 'Bắt buộc phải nhập mô tả khóa học',
            'description.max' => 'chỉ có thể nhập tối đa 255 kí tự',
            'image.required' => 'Bắt buộc phải nhập ảnh khóa học',
            'content.required' => 'Bắt buộc phải nhập nội dung khóa học',
            'result.required' =>'Bắt buộc phải nhập kết quả khóa học',
            'category_id.required' => 'Bắt buộc phải chọn danh mục khóa học'
            // 'image.image' => 'Dữ liệu nhập vào phải là file ảnh(jpeg, png, bmp, gif, or svg)',
        ];
    }
}
