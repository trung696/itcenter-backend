<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;

class ClassRequest extends FormRequest
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
        // dd($this->method());
        switch ($this->method()):
            case 'POST':
                switch ($currentAction) {
                    case 'addClass':
                        $rules = [
                            "name" => "required|min:5|max:199|unique:class",
                            "slot" => "required|numeric",
                            "start_date" => "required",
                            "end_date" => "required",
                            "lecturer_id" => "required",
                            "location_id" => "required",
                            "course_id" => "required",
                            'id_ca' => 'required',
                        ];
                        break;
                    case 'updateClass':
                        $rules = [
                            "name" => "required|min:5|max:199",
                            "slot" => "required|numeric",
                            "start_date" => "required",
                            "end_date" => "required",
                            "lecturer_id" => "required",
                            "location_id" => "required",
                            "course_id" => "required",
                            'id_ca' => 'required',
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
            'name.required' => 'Bắt buộc phải nhập lớp học',
            'name.min' => 'Tên lớp học phải nhập tối thiểu 5 kí tự',
            'name.max' => 'Tên lớp học phải nhập tối đa 199 kí tự',
            'name.unique' => 'Tên lớp học đã tồn tại trên hệ thống',
            'slot.required' => 'Bắt buộc phải nhập số chỗ',
            'start_date.required' => 'Bắt buộc phải nhập ngày bắt đầu',
            'end_date.required' => 'Bắt buộc phải nhập ngày kết thúc',
            'lecturer_id.required' => 'Bắt buộc phải nhập giảng viên',
            'location_id.required' => 'Bắt buộc phải nhập địa điểm',
            'course_id.required' => 'Bắt buộc phải nhập khóa học',
            'id_ca.required' => 'Bắt buộc phải chọn ca học',
        ];
    }
}
