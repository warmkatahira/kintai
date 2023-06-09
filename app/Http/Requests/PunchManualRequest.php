<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PunchManualRequest extends FormRequest
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
            'finish_time' => 'required',
            'out_time' => 'nullable|required_with:return_time',
            'return_time' => 'nullable|required_with:out_time',
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
            'finish_time' => '退勤時間',
            'out_time' => '外出時間',
            'return_time' => '戻り時間',
        ];
    }
}
