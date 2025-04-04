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
            'ip' => 'required|max:15',
            'base_id' => 'required|exists:bases,base_id',
            'note' => 'nullable|max:20',
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
            'exists' => ':attributeが存在しません。',
        ];
    }

    public function attributes()
    {
        return [
            'ip' => 'IP',
            'base_id' => '拠点',
            'note' => '備考',
            'is_available' => '有効/無効',
        ];
    }
}
