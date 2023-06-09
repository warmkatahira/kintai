<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MonthlyWorkableTimeRule;
use App\Rules\OverTimeStartRule;

class EmployeeCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'base_id' => 'required|exists:bases,base_id',
            'employee_category_id' => 'required|exists:employee_categories,employee_category_id',
            'employee_no' => 'required|unique:employees,employee_no,'.$this->employee_id.',employee_id',
            'employee_last_name' => 'required|max:10',
            'employee_first_name' => 'required|max:10',
            'monthly_workable_time' => ['required', new MonthlyWorkableTimeRule($this->monthly_workable_time)],
            'over_time_start' => ['required', new OverTimeStartRule($this->over_time_start)],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'exists' => ':attributeが存在しません。',
            'unique' => ':attributeは既に使用されています。',
        ];
    }

    public function attributes()
    {
        return [
            'base_id' => '拠点',
            'employee_category_id' => '従業員区分',
            'employee_no' => '従業員番号',
            'employee_last_name' => '従業員名(姓)',
            'employee_first_name' => '従業員名(名)',
            'monthly_workable_time' => '月間稼働可能時間',
            'over_time_start' => '残業開始時間',
        ];
    }
}
