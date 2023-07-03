<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PunchManualBeginOnlyRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'work_day' => 'required|date',
            'employee_id' => 'required|exists:employees,employee_id',
            'begin_time' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'required' => ":attributeは必須です。",
            'date' => ':attributeは日付を設定して下さい。',
            'exists' => ":attributeが存在しません。",
        ];
    }

    public function attributes()
    {
        return [
            'work_day' => '出勤日',
            'employee_id' => '従業員',
            'begin_time' => '出勤時間',
        ];
    }
}
