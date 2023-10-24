<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemporaryCompanyRequest extends FormRequest
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
            'temporary_company_name' => 'required|max:20',
            'hourly_rate' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'integer' => ':attributeは数値で入力して下さい。',
            'min' => ":attributeは:min以上で入力して下さい。",
        ];
    }

    public function attributes()
    {
        return [
            'temporary_company_name' => '派遣会社名',
            'hourly_rate' => '時給単価',
        ];
    }
}
