<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IpLimitCreateRequest extends FormRequest
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
            'ip' => 'required|unique:ip_limits,ip,'.$this->ip_limit_id.',ip_limit_id',
            'note' => 'required|max:20',
            'is_available' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attributeは必須です。',
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'unique' => ':attributeは既に使用されています。',
            'boolean' => ':attributeが正しくありません。',
        ];
    }

    public function attributes()
    {
        return [
            'ip' => 'IP',
            'note' => '備考',
            'is_available' => '有効/無効',
        ];
    }
}
