<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MonthlyWorkableTimeRule;
use App\Rules\OverTimeStartRule;
use App\Enums\CommutingAllowanceEnum;

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
            'over_time_start' => ['nullable', new OverTimeStartRule($this->over_time_start)],
            'hourly_wage' => ['nullable', 'integer', 'min:0', 'max:99999'],
            'paid_leave_working_time' => ['nullable', 'numeric', 'min:0', 'max:24', 'multiple_of:0.25'],
            'commuting_allowance_type' => ['nullable', Rule::in(array_keys(CommutingAllowanceEnum::TYPE_LIST))],
            'commuting_allowance_amount' => ['nullable', 'required_if:commuting_allowance_type,'.implode(',', [CommutingAllowanceEnum::MONTHLY_FIXED, CommutingAllowanceEnum::WORKING_DAYS, CommutingAllowanceEnum::COMMENT_DAYS]), 'integer', 'min:0', 'max:999999'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'exists' => ':attributeが存在しません。',
            'unique' => ':attributeは既に使用されています。',
            'in' => ':attributeの値が不正です。',
            'integer' => ':attributeは整数で入力して下さい。',
            'hourly_wage.min' => ':attributeは:min以上で入力して下さい。',
            'hourly_wage.max' => ':attributeは:max以下で入力して下さい。',
            'numeric' => ':attributeは数値で入力して下さい。',
            'paid_leave_working_time.min' => ':attributeは:min以上で入力して下さい。',
            'paid_leave_working_time.max' => ':attributeは:max以下で入力して下さい。',
            'multiple_of' => ':attributeは:value単位で入力して下さい。',
            'commuting_allowance_amount.min' => ':attributeは:min以上で入力して下さい。',
            'commuting_allowance_amount.max' => ':attributeは:max以下で入力して下さい。',
            'required_if' => '交通費の支給形態が「なし」以外の場合、:attributeは必須です。',
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
            'hourly_wage' => '時給単価',
            'paid_leave_working_time' => '有給時稼働時間',
            'commuting_allowance_type' => '交通費支給形態',
            'commuting_allowance_amount' => '交通費金額',
        ];
    }
}
