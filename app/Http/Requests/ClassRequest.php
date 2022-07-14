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
                            "name" => "required",
                            "price" => "required",
                            "slot" => "required",
                            "start_date" => "required",
                            "end_date" => "required",
                            "lecturer_id" => "required",
                            "location_id" => "required",
                            "course_id" => "required",
                        ];
                        break;
                    case 'updateClass':
                        $rules = [
                            "name" => "required",
                            "price" => "required",
                            "slot" => "required",
                            "start_date" => "required",
                            "end_date" => "required",
                            "lecturer_id" => "required",
                            "location_id" => "required",
                            "course_id" => "required",
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
            'price.required' => 'Bắt buộc phải nhập giá',
            'slot.required' => 'Bắt buộc phải nhập số chỗ',
            'start_date.required' => 'Bắt buộc phải nhập ngày bắt đầu',
            'end_date.required' => 'Bắt buộc phải nhập ngày kết thúc',
            'lecturer_id.required' => 'Bắt buộc phải nhập giảng viên',
            'location_id.required' => 'Bắt buộc phải nhập địa điểm',
            'course_id.required' => 'Bắt buộc phải nhập khóa học',
        ];
    }
}
